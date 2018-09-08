"""
Techable Object Recogznier Server
- receive images from a client via RESTful protocol
- recognize them
- send back to the client recognition results

written by Kyungjun Lee
"""
from TOR import Recognizer

# code mostly adapted from
# https://gist.github.com/kylehounslow/767fb72fde2ebdd010a0bf4242371594
from flask import Flask, request, jsonify#, Response, json

from collections import defaultdict

import numpy as np
import cv2

import os
import json
import zipfile

# from multiprocessing import Pool
# from concurrent.futures import ThreadPoolExecutor
import threading

# garbage collector
import gc


# configuration for Flask upload
UPLOAD_DIR = "images"
IMAGE_EXTENSIONS = set(['png', 'jpg', 'jpeg'])
ZIP_EXTENSIONS = set(['zip'])
# initializer the Flask application
receiver = Flask(__name__)

# lock for the global variables
# https://hackernoon.com/synchronization-primitives-in-python-564f89fee732
# https://docs.python.org/3/library/threading.html
TOR_lock = threading.RLock()
q_lock = threading.RLock()


"""
initialize recognizer
@input  : classifier model name (string),
          classifier label name (string),
          debugging flag (bool)
@output : recognizer instance (object)
"""
def init_recognizer(classifer_model, classifier_label, debug):
  return Recognizer(classifier_model = classifier_model,
                    classifier_label = classifier_label,
                    debug = debug)


"""
stop the recognizer
@input  : uuid (string), terminating (boolean)
@output : N/A
"""
def stop_recognizer(uuid, terminating = False):
  # global TORs, models, labels, in_training
  global TORs
  # we may have to rely on python's GCC
  try:
    if TORs[uuid] is not None:
      TORs[uuid].stop_all()
      # TORs.pop(uuid, None)
      if terminating:
        # lock.acquire()
        del TORs[uuid]
        # del models[uuid]
        # del labels[uuid]
        # del in_training[uuid]
        # lock.release()
        # TORs[uuid] = None
  except KeyError:
    print("ERROR: couldn't find TOR of", uuid)


"""
resume the recognizer
@input  : N/A
@output : N/A
"""
def resume_recognizer(uuid, classifier_model, classifier_label):
  global TORs
  # we may have to rely on python's GCC
  TORs[uuid].resume_all(classifier_model, classifier_label)


def get_model_and_label(uuid, phase):
  global classifier_model_dir, classifier_model, classifier_label
  if phase == "test0":
    return classifier_model, classifier_label
  else:
    if phase == "test1":
      # as of now, only the first trial (t1) is considered
      model_name = "t1_train1_graph.pb"
      label_name = "t1_train1_label.pb"
    elif phase == "test2":
      model_name = "t1_train2_graph.pb"
      label_name = "t1_train2_label.pb"
    else:
      print("ERROR: no recognized phase")
      return classifier_model, classifier_label

    model = os.path.join(classifier_model_dir, uuid, model_name)
    label = os.path.join(classifier_model_dir, uuid, label_name)
    if os.path.exists(model) and os.path.exists(label):
      return model, label
    else:
      print("ERROR: couldn't find the recognizer for", uuid, phase)
      return classifier_model, classifier_label
    

"""
check recognizer
  1) if not initialized, initialize it
  2) if not resumed, resume it
@input  : uuid, phase
@output : N/A
"""
def check_recognizer(uuid, phase):
  # get the global recognizer variable
  global TORs,classifier_model, classifier_label, debug, TOR_lock
  try:
    recognizer = TORs[uuid]
    # if turned off, turn it on
    if recognizer is None:
      model, label = get_model_and_label(uuid, phase)
      TOR_lock.acquire()
      TORs[uuid] = init_recognizer(model, label, debug)
      TOR_lock.release()
    # check whether recognizer is available
    elif not recognizer.is_alive():
      # now resuming it
      resume_recognizer(uuid, model, label)
  except KeyError:
    # initialize it if not
    TOR_lock.acquire()
    TORs[uuid] = init_recognizer(classifier_model, classifier_label, debug)
    # models[uuid] = classifier_model
    # labels[uuid] = classifier_label
    TOR_lock.release()


"""
check whether a received file is allowed
@input  : filename (string)
@output : True/False (boolean)
"""
def is_image(filename):
  return '.' in filename and \
          filename.rsplit('.', 1)[1].lower() in IMAGE_EXTENSIONS


"""
check whether a received file is allowed
@input  : filename (string)
@output : True/False (boolean)
"""
def is_zip(filename):
  return '.' in filename and \
          filename.rsplit('.', 1)[1].lower() in ZIP_EXTENSIONS

"""
count the number of images with label
@input  : label (string)
@output : # of images in the label path (int), \
          label directory (string)
"""
def count_images_with_label(label):
  # a folder for the given label
  label_dir = os.path.join(UPLOAD_DIR, label)

  # create the folder if not existed
  if not os.path.exists(label_dir):
    os.makedirs(label_dir)
    if debug: print("created ", label_dir)

  # count how many images are there
  num_imgs = len([name for name in os.listdir(label_dir) \
              if is_image(name)])

  if debug: print("total: %d in %s" % (num_imgs, label_dir))

  return num_imgs, label_dir


"""
save an image for retraining
@input  : image (numpy array), label (string)
@output : # of images for the label (int)
"""
def save_image_with_label(image, label):
  # first count how many images are there
  num_imgs, label_dir = count_images_with_label(label)

  # image file name: label_num.jpg
  img_name = label + "_" + str(num_imgs) + ".jpg"
  # save the image
  cv2.imwrite(os.path.join(label_dir, img_name), image)
  if debug: print("%s saved in %s" % (img_name, label_dir))

  # increment num_imgs and return
  return num_imgs + 1

"""
check if a given directory is existed
if not, create all necessary folders
@input  : dir_path (string)
@output : N/A
"""
def check_dir(dir_path):
  if not os.path.exists(dir_path):
    # os.makedirs does not return any value
    os.makedirs(dir_path)


"""
check and save an image file
@input  : uuid (string), phase (string), img_file (file object)
@output : image (numpy array)
"""
def check_and_save_image(uuid, phase, img_file):
  image = None
  img_dir = os.path.join(UPLOAD_DIR, uuid, phase)
  if img_file and is_image(img_file.filename):
    # check whether the image directory is existed
    check_dir(img_dir)
    # first save the image
    img_path = os.path.join(img_dir, img_file.filename)
    img_file.save(img_path)
    # then read it as numpy array
    image = cv2.imread(img_path)

  return image


"""
retrain a classifier that is associated with uuid and phase
@input  : uuid (string), phase (string)
@output : boolean (True/False)
"""
def retrain_classifier(uuid, phase):
  global on_training, training_q, q_lock
  # stop the recognizer if alive
  stop_recognizer(uuid)
  # trigger the retrain in a subprocess due to its os environ change
  tr_script = "python3 retrain.py --uuid %s --phase %s" % (uuid, phase)
  os.system(tr_script)
  # new_model, new_label = TORs[uuid].retrain(uuid, phase)
  # reset the training flag and pop itself from the training queue
  q_lock.acquire()
  on_training = False
  # check the first uuid in the training queue
  # it should be the same as the uuid given here
  at_first = training_q[0]
  if at_first != (uuid, phase):
    print("Error: why the popped uuid is not matched with ")
  else:
    training_q.pop(0)
    print("Notice: ", uuid, "removed from the training queue")
    if len(training_q) > 0:
      # automatically trigger the training again
      next_uuid, next_phase = training_q[0]
      on_training = True
      t = threading.Thread(target = retrain_classifier, args = (next_uuid, next_phase))
      t.start()
  q_lock.release()


"""
initialize a recognzer for a user
@input  : N/A
@output : Response {result = True/False}
"""
# route http posts to this method
# TO TEST: curl -d '{"uuid": "1234"}' -H "Content-Type: application/json" -X POST http://0.0.0.0:5000/init
@receiver.route('/init', methods = ['POST'])
def init():
  # global TORs, classifier_model, classifier_label, debug, models, labels, in_training
  global TORs, classifier_model, classifier_label, debug, TOR_lock

  r = request
  print("request:", r)
  # get json body - contains "uuid"
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(result = "False")

  uuid = body['uuid']
  if not uuid:
    print("Error: no uuid")
    return jsonify(result = "False")

  # initialize a recognizer
  print("NOTICE: Starting a recognizer for ", uuid)
  TOR_lock.acquire()
  TORs[uuid] = init_recognizer(classifier_model, classifier_label, debug)
  # models[uuid] = classifier_model
  # labels[uuid] = classifier_label
  # in_training[uuid] = False
  TOR_lock.release()
  
  # build JSON response containing the result
  return jsonify(result = "True")


"""
stop a recognzer for a user
@input  : N/A
@output : Response {result = True/False}
"""
# route http posts to this method
# TO TEST: curl -d '{"uuid": "1234"}' -H "Content-Type: application/json" -X POST http://0.0.0.0:5000/stop
@receiver.route('/stop', methods = ['POST'])
def stop():
  r = request
  print("request:", r)
  # get json body - contains "uuid"
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(result = "False")

  uuid = body['uuid']
  if not uuid:
    print("Error: no uuid")
    return jsonify(result = "False")

  # stop the recognizer associated with a given UUID
  print("NOTICE: Stopping a recognizer for ", uuid)
  stop_recognizer(uuid, terminating = True)
  # trigger the python's garbage collector
  gc.collect()
  
  # build JSON response containing the result
  return jsonify(result = "True")


"""
unzip a given zip file
@input  : zip_file (FileStorage)
@output : True/False (boolean)
"""
def unzip_file(uuid, phase, zip_file):
  # set target directory
  zip_dir = os.path.join(UPLOAD_DIR, uuid)
  target_dir = os.path.join(zip_dir, phase)

  if zip_file and is_zip(zip_file.filename):
    check_dir(target_dir)
    zip_file_path = os.path.join(zip_dir, zip_file.filename)
    zip_file.save(zip_file_path)
    with zipfile.ZipFile(zip_file_path, "r") as zip_f:
      print("unzipping %s into %s" % (zip_file.filename, target_dir))
      zip_f.extractall(target_dir)
    return True
  else:
    return False


"""
list only images in a directory
@input  : dir (string)
@output : images (an array of string)
"""
def list_images(dir):
  return [f for f in os.listdir(dir) if is_image(f)]


"""
test images in a directory
@input  : uuid (string - user's unique id),
          phase (string - the phase in the study),
          image (numpy array)
@output : output (dictionary - {label : prob} for each object)
"""
def test_image(uuid, phase, image):
  global TORs

  output = {"uuid": uuid}
  if image is not None:
    # check if there is a recognizer for the given UUID
    check_recognizer(uuid)
    # recognize the input image
    label, prob = TORs[uuid].do(image)
    # prepare the output
    output["label"] = label
    output["prob"] = str(prob)
  
  return output


"""
test an image
@input  : uuid (string - user's unique id),
          phase (string - the phase in the study)
@output : output (dictionary - {label : prob} for each object)
"""
def test_images(uuid, phase):
  global TORs

  check_recognizer(uuid, phase)

  img_dir = os.path.join(UPLOAD_DIR, uuid, phase)
  labels = set()
  probs = defaultdict(float)
  cnt = defaultdict(int)
  # recognize all of the images in a directory
  for img in list_images(img_dir):
    # read image
    image = cv2.imread(os.path.join(img_dir, img), cv2.IMREAD_COLOR)
    # recognize the input image
    label, prob = TORs[uuid].do(image)
    labels.add(label)
    probs[label] += prob
    cnt[label] += 1
  
  output = {"uuid": uuid}
  for label in labels:
    output[label] = probs[label] / cnt[label]
  
  return output


"""
test images received from a client
@input  : N/A
@output : Response {
            labels: a set of labels,
            probs: a set of probabilities
          }
"""
# route http posts to this method
# TO TEST: curl -v -X POST -F "uuid=1234" -F "phase=test1" -F "file=@test.jpg" http://0.0.0.0:5000/test
@receiver.route('/test', methods = ['POST'])
def test():
  r = request
  print("request:", r)
  # request must contain "file"
  if "file" not in r.files:
    print("Error: invalid request")
    return jsonify(uuid = "N/A",
                  labels = "N/A",
                  probs = "N/A")
  
  # retrieve the file
  file = r.files['file']
  uuid = str(r.form.get("uuid"))
  phase = str(r.form.get("phase"))
  if file.filename == '' or \
      not uuid or uuid == '' or \
      not phase or phase == '':
    print("Error: invalid request")
    return jsonify(uuid = "N/A",
                  labels = "N/A",
                  probs = "N/A")
  """
  # now unzip the received zip file
  if not unzip_file(uuid, phase, file):
    print("Error: failed to unzip %s" % (file.filename))
    return jsonify(uuid = "N/A",
                  labels = "N/A",
                  probs = "N/A")
  """
  # now check and save the image
  image = check_and_save_image(uuid, phase, file)
  if image is None:
    print("Error: something is wrong with the given image")
    return jsonify(uuid = uuid,
                  labels = "N/A",
                  probs = "N/A")
  
  # recognize image in a thread
  # so that the recognition may not block the FLASK server processing
  result = test_image(uuid, phase, image)
  # output = test_images(uuid, phase)
  # build JSON response containing the output label and probability
  return jsonify(result)


"""
check how many participants waiting in the queue berfore the given uuid
@input  : N/A
@output : Response {
            result: True/False,
            before_me: the number of users waiting in the queue
          }
"""
# route http posts to this method
# TO TEST: curl -d '{"uuid": "1234"}' -H "Content-Type: application/json" -X POST http://0.0.0.0:5000/check
@receiver.route('/check', methods = ['POST'])
def check():
  global training_q

  r = request
  print("request:", r)
  # get json body - contains "uuid"
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(result = "False", before_me = "-1")

  uuid = body['uuid']
  phase = body['phase']
  if not uuid or not phase:
    print("Error: no uuid or no phase")
    return jsonify(result = "False", before_me = "-1")

  # if this user is in the first element of the queue
  # start the retraining!
  before_me = training_q.index((uuid, phase))
  if before_me == 0:
    return jsonify(result = "True", before_me = str(before_me))
  else:
    # otherwise, simply return "False" and the number of users in the queue before herself
    return jsonify(result = "False", before_me = str(before_me))


"""
train classifiers with images received from a client
@input  : N/A
@output : Response {
            uuid: uuid,
            classifier_model: classifier model filename,
            classifier_label: classifier label text filename
          }
"""
# route http posts to this method
# TO TEST: curl -v -X POST -F "uuid=1234" -F "phase=train1" -F "file=@train1.zip" http://0.0.0.0:5000/train
@receiver.route('/train', methods = ['POST'])
def train():
  global on_training, q_lock

  r = request
  print("request:", r)
  # request must contain "file"
  if "file" not in r.files:
    print("Error: invalid request")
    return jsonify(uuid = "N/A", result = "False", before_me = "-1")
  
  # retrieve the file
  file = r.files['file']
  uuid = str(r.form.get("uuid"))
  phase = str(r.form.get("phase"))
  if file.filename == '' or \
      not uuid or uuid == '' or \
      not phase or phase == '':
    print("Error: invalid request")
    return jsonify(uuid = "N/A", result = "False", before_me = "-1")

  # now unzip the received zip file
  if not unzip_file(uuid, phase, file):
    print("Error: failed to unzip %s" % (file.filename))
    return jsonify(uuid = uuid, result = "False", before_me = "-1")

  # spawn a new process for training its classifier
  # sarmap = a variant of map, to take multiple arguments
  # FYI, https://stackoverflow.com/questions/5442910/python-multiprocessing-pool-map-for-multiple-arguments
  # proc_pool.starmap(retrain_classifier, [(uuid, phase)])
  # with ThreadPoolExecutor(max_workers=1) as thread_pool:
    # thread_pool.submit(retrain_classifier, uuid, phase)
  # if not in_training[uuid]:
  #   lock.acquire()
  #   in_training[uuid] = True
  #   lock.release()
  #   # https://stackoverflow.com/questions/2846653/how-to-use-threading-in-python
  #   t = threading.Thread(target = retrain_classifier, args = (uuid, phase))
  #   # t.daemon = True
  #   t.start()
  # if on_training == True:
  #   print("Error: on training? why?")
  #   return jsonify(uuid = uuid, result = "False")

  # training_q consists of tuples: (uuid, phase)
  # there should be at most one tuple associated with uuid
  in_training = [each for each in training_q if each[0] == uuid]
  if len(in_training) == 0:
    q_lock.acquire()
    # first put the uid into the training queue
    training_q.append((uuid, phase))
    q_lock.release()

  # if this user is in the first element of the queue
  # start the retraining if first
  try:
    before_me = training_q.index((uuid, phase))
    if before_me == 0 and not on_training:
      q_lock.acquire()
      on_training = True
      q_lock.release()
      # now trigger the training here
      t = threading.Thread(target = retrain_classifier, args = (uuid, phase))
      t.start()
      return jsonify(uuid = uuid, result = "True", before_me = str(before_me))
    else:
      # otherwise, simply return "False" and the number of users in the queue before herself
      return jsonify(uuid = uuid, result = "False", before_me = str(before_me))
  except ValueError:
    # otherwise, simply return "False" and the number of users in the queue before herself
    return jsonify(uuid = uuid, result = "False", before_me = "-1")


# global variables
input_label = None
debug = True
classifier_model_dir = "models/"
classifier_model = os.path.join(classifier_model_dir, "classifier_graph.pb")
classifier_label = os.path.join(classifier_model_dir, "classifier_labels.txt")
TORs = {}
training_q = []
on_training = False
# models = {}
# labels = {}
# in_training = {}
# proc_pool = Pool(8) # 8 is the number of GPUs
# thread_pool = ThreadPoolExecutor(max_workers = 8)

# run the RESTful server
receiver.run(host = "0.0.0.0", port = 5000, threaded = True)

