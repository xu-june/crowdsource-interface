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
from concurrent.futures import ThreadPoolExecutor


# configuration for Flask upload
UPLOAD_DIR = "images"
IMAGE_EXTENSIONS = set(['png', 'jpg', 'jpeg'])
ZIP_EXTENSIONS = set(['zip'])
# initializer the Flask application
receiver = Flask(__name__)

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
@input  : uuid (string)
@output : N/A
"""
def stop_recognizer(uuid):
  global TORs
  # we may have to rely on python's GCC
  TORs[uuid].stop_all()


"""
resume the recognizer
@input  : N/A
@output : N/A
"""
def resume_recognizer(uuid, classifier_model, classifier_label):
  global TORs
  # we may have to rely on python's GCC
  TORs[uuid].resume_all(classifier_model, classifier_label)


"""
reload the latest recognizer
@input  :
@output :
"""
"""
def reload_recognizer(classifier_model, classifier_label):
  new_c_model, new_c_label = get_latest_classifier(classifier_model_dir)
  if classifier_model < new_c_model and classifier_label < new_c_label:
    print("found the latest classifier: %s and %s" % (classifier_model, classifier_label))
    stop_recognizer()
    classifier_model = new_c_model
    classifier_label = new_c_label
    resume_recognizer(classifier_model, classifier_label)
"""


"""
pick the latest version of classifier
@input  : model dir (string)
@output : classifier model dir (string), classifier label dir (string)
"""
"""
def get_latest_classifier(model_dir):
  # get all .pb files in the model dir
  latest_model = latest_label = ""
  for each in os.listdir(model_dir):
    if each.endswith(".pb"):
      if latest_model == "":
        latest_model = each
      elif latest_model < each:
        latest_model = each
    elif each.endswith(".txt"):
      if latest_label == "":
        latest_label = each
      elif latest_label < each:
        latest_model = each

  # get their paths
  latest_model = os.path.join(model_dir, latest_model)
  latest_label = os.path.join(model_dir, latest_label)

  return latest_model, latest_label
"""


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
  # stop the recognizer if alive
  stop_recognizer(uuid)

  # trigger the retrain
  new_model, new_label = TORs[uuid].retrain(uuid, phase)
  if new_model != "" and new_label != "":
    global models, labels
    models[uuid] = new_model
    labels[uuid] = new_label
    return True
  else:
    global classifier_model, classifier_label
    models[uuid] = classifier_model
    labels[uuid] = classifier_label
    return False


"""
receive an image from a client to save
@input  : N/A
@output : Response {count of images in the label}
"""
# route http posts to this method
@receiver.route('/save', methods = ['POST'])
def save_image():
  global input_label
  label = input_label
  r = request
  print("request:", r)
  # convert string of image data to uint8
  raw = np.fromstring(r.data, np.uint8)
  # decode image
  image = cv2.imdecode(raw, cv2.IMREAD_COLOR)
  # get object image
  obj_img = localize_object(image)

  if not label:
    print("Error: no label")
    return jsonify(label = "N/A", count = "N/A")

  # save the image
  cnt = save_image_with_label(obj_img, label)
  
  # build JSON response containing the output label and probability
  return jsonify(label = label, count = str(cnt))


"""
receive a label from a client for retraining
@input  : N/A
@output : Response {count of images in the label}
"""
# route http posts to this method
@receiver.route('/label', methods = ['POST'])
def save_label():
  global input_label

  r = request
  print("request:", r)
  # get json body
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(label = "N/A", count = "N/A")

  label = body['label']
  if not label:
    print("Error: no label")
    return jsonify(label = "N/A", count = "N/A")

  # get the label
  input_label = label

  # save the image
  cnt, _ = count_images_with_label(label)
  
  # build JSON response containing the output label and probability
  return jsonify(label = label, count = str(cnt))


"""
initialize a recognzer for a user
@input  : N/A
@output : Response {result = True/False}
"""
# route http posts to this method
# TO TEST: curl -d '{"uuid": "1234"}' -H "Content-Type: application/json" -X POST http://0.0.0.0:5000/init
@receiver.route('/init', methods = ['POST'])
def init():
  global TORs, input_label, classifier_model, classifier_label, debug

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

  # run this initialization in a thread
  # so that the initialization may not block the FLASK server processing
  future = thread_pool.submit(init_recognizer,
                              classifier_model,
                              classifier_label,
                              debug)

  # retrieve the result of the initialization
  TORs[uuid] = future.result()
  # TORs[uuid] = init_recognizer(classifier_model, classifier_label, debug)
  
  # build JSON response containing the result
  return jsonify(result = "True")


"""
download a zip file that contains images
@input  : N/A
@output : Response {result = True/False}
"""
# route http posts to this method
# TO TEST: curl -v -X POST -F "uuid=1234" -F "file=@test1.zip" http://0.0.0.0:5000/upload
@receiver.route('/upload', methods = ['POST'])
def download():
  r = request
  print("request:", r)
  
  # request must contain "file"
  if "file" not in r.files:
    print("Error: invalid request")
    return jsonify(result = "False")
  
  # retrieve the file
  file = r.files['file']
  if file.filename == '':
    print("Error: invalid filename")
    return jsonify(result = "False")

  # request must contain "uuid" in the form
  uuid = str(r.form.get("uuid"))
  if not uuid or uuid == '':
    print("Error: invalid request")
    return jsonify(result = "False")

  # print(uuid)
  target_dir = os.path.join(UPLOAD_DIR, uuid)
  # check the destination path is existed
  # if not, created all necessary dirs
  if not os.path.exists(target_dir):
    os.makedirs(target_dir)

  if file and is_zip(file.filename):
    target_file = os.path.join(target_dir, file.filename)
    file.save(target_file)
    with zipfile.ZipFile(target_file, "r") as zip_f:
      print("unzipping %s into %s" % (file.filename, target_dir))
      zip_f.extractall(target_dir)
  
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
    zip_file_path = os.path.join(zip_dir, zip_file.filename)
    zip_file.save(zip_file_path)
    with zipfile.ZipFile(zip_file_path, "r") as zip_f:
      print("unzipping %s into %s" % (zip_file.filename, target_dir))
      zip_f.extractall(target_dir)
    return True
  else:
    return False


"""
check recognizer
  1) if not initialized, initialize it
  2) if not resumed, resume it
@input  : N/A
@output : N/A
"""
def check_recognizer(uuid):
  # get the global recognizer variable
  global TORs, classifier_model, classifier_label
  try:
    recognizer = TORs[uuid]
    # if turned off, turn it on
    if not recognizer:
      TORs[uuid] = init_recognizer(classifier_model, classifier_label, debug)
    # check whether recognizer is available
    elif not recognizer.is_alive():
      """
      # get the latest classifier model and label
      new_c_model, new_c_label = get_latest_classifier(classifier_model_dir)
      if new_c_model > classifier_model and new_c_label > classifier_label:
        classifier_model = new_c_model
        classifier_label = new_c_label
      """
      # now resuming it
      resume_recognizer(uuid, classifier_model, classifier_label)

  except KeyError:
    # initialize it if not
    TORs[uuid] = init_recognizer(classifier_model, classifier_label, debug)


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

  check_recognizer(uuid)

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
  future = thread_pool.submit(test_image, uuid, phase, image)
  # output = test_images(uuid, phase)
  # build JSON response containing the output label and probability
  return jsonify(future.result())


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
  global TORs

  r = request
  print("request:", r)
  # request must contain "file"
  if "file" not in r.files:
    print("Error: invalid request")
    return jsonify(uuid = "N/A",
                  result = "False")
  
  # retrieve the file
  file = r.files['file']
  uuid = str(r.form.get("uuid"))
  phase = str(r.form.get("phase"))
  if file.filename == '' or \
      not uuid or uuid == '' or \
      not phase or phase == '':
    print("Error: invalid request")
    return jsonify(uuid = "N/A",
                  result = "False")

  # now unzip the received zip file
  if not unzip_file(uuid, phase, file):
    print("Error: failed to unzip %s" % (file.filename))
    return jsonify(uuid = uuid,
                  result = "False")

  # spawn a new process for training its classifier
  # sarmap = a variant of map, to take multiple arguments
  # FYI, https://stackoverflow.com/questions/5442910/python-multiprocessing-pool-map-for-multiple-arguments
  # proc_pool.starmap(retrain_classifier, [(uuid, phase)])
  thread_pool.submit(retrain_classifier, uuid, phase)
  # result = "True" if future.result() else "False"

  # return "True" if we "trigger" the retraining
  return jsonify(uuid = uuid,
                result = "True")

"""
test images received from a client
@input  : N/A
@output : Response {
            labels: a set of labels,
            probs: a set of probabilities
          }
"""
"""
# route http posts to this method
# TO TEST:
# curl -d '{"uuid":"1234", "phase": "test1"}' -H "Content-Type: application/json" -X POST http://0.0.0.0:5000/test
@receiver.route('/test', methods = ['POST'])
def test():
  # TODO: revamp this function to recognize images
  r = request
  print("request:", r)
  # get json body
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(labels = "N/A", probs = "N/A")

  # get phase: test1, test2, or test3
  phase = body['phase']
  uuid = body['uuid']
  if not phase or not uuid:
    print("Error: not sufficient parameter")
    return jsonify(labels = "N/A", probs = "N/A")
  
  # recognize image
  output = test_image(uuid, phase)
  # build JSON response containing the output label and probability
  return jsonify(output)
"""

"""
response = {'label': label, 'prob': prob}

return Response(response = json.dumps(response_pickled),
                status = 200,
                mimetype = "application/json")
"""


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
"""
@receiver.route('/train', methods = ['POST'])
def retrain_classifier():

  r = request
  print("request:", r)
  # get json body
  # TODO: receive UUID from the survey server
  body = r.json
  if not body:
    print("Error: invalid request")
    return jsonify(classifier_model = "N/A", classifier_label = "N/A")

  phase = body['phase']
  uuid = body['uuid']
  if not phase or not uuid:
    print("Error: not sufficient parameter")
    return jsonify(uuid = uuid,
                  classifier_model = "N/A",
                  classifier_label = "N/A")
  else:
    global TORs
    # stop the recognizer if alive
    stop_recognizer(uuid)
    # trigger the retrain
    new_model, new_label = TORs[uuid].retrain(uuid, phase)
    if new_model != "" and new_label != "":
      global models, labels
      models[uuid] = new_model
      labels[uuid] = new_label
    else:
      global classifier_model, classifier_label
      models[uuid] = classifier_model
      labels[uuid] = classifier_label

  # build JSON response containing the output label and probability
  return jsonify(uuid = uuid,
                classifier_model = models[uuid],
                classifier_label = labels[uuid])
"""


# global variables
input_label = None
debug = True
classifier_model_dir = "models/"
classifier_model = os.path.join(classifier_model_dir, "classifier_graph.pb")
classifier_label = os.path.join(classifier_model_dir, "classifier_labels.txt")
TORs = {}
models = {}
labels = {}
# proc_pool = Pool(8) # 8 is the number of GPUs
thread_pool = ThreadPoolExecutor(8)


# run the RESTful server
receiver.run(host = "0.0.0.0", port = 5000)

