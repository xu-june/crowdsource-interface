"""
Techable Object Recognizer for crowd-study
- consists of one deep learning model
- 1) object classifier

written by Kyungjun Lee
"""
from TOR_classifier import Classifier
# retrain function
from retrain import retrain_model

import os
import sys
import argparse

import re
import csv
import cv2


parser = argparse.ArgumentParser()
parser.add_argument("--pid", help="User's pid")
parser.add_argument("--model", help="Path to a model")
parser.add_argument("--label", help="Path to a model")
parser.add_argument("--img_dir", help="Path to the image dataset")
parser.add_argument("--output_dir", help="Path to the output directory")
parser.add_argument(
    "--gpu_id",
    type=int,
    default=-1,
    help="GPU index to use")

args = None
debug = True

"""
Recognize class
- recognizes an object of interest in an image
- using localizer and classifier
"""
class Recognizer:
  # constructor
  def __init__(self,
              classifier_model,
              classifier_label,
              gpu_to_use = -1,
              debug = False):
    # allocating TF graph to only one GPU
    #https://stackoverflow.com/questions/37893755/tensorflow-set-cuda-visible-devices-within-jupyter
    os.environ["CUDA_DEVICE_ORDER"] = "PCI_BUS_ID"   # see issue #152
    if gpu_to_use >= 0: 
      os.environ["CUDA_VISIBLE_DEVICES"] = str(gpu_to_use)
    else:
      # only using CPUs
      os.environ["CUDA_VISIBLE_DEVICES"] = ""
    
    # flag for debugging mode
    self.debug = debug
    # initialize a classifier
    self.classifier = Classifier(
                        model = classifier_model,
                        label_file = classifier_label,
                        input_layer = "Placeholder",
                        output_layer = "final_result",
                        debug = debug)

  """
  stop the recognizer
  @input  : N/A
  @output : N/A
  """
  def stop_all(self):
    if self.classifier:
      del self.classifier
      self.classifier = None
    

  """
  resume the recognizer
  @input  : new classifier model file (string),
            new classifier label file (string)
  @output : N/A
  """
  def resume_all(self, classifier_model, classifier_label):
    # re-initialize a classifier
    if not self.classifier:
      self.classifier = Classifier(
                          model = classifier_model,
                          label_file = classifier_label,
                          input_layer = "Placeholder",
                          output_layer = "final_result",
                          debug = self.debug)

  """
  recognize an image
  @input  : image_file (numpy array)
  @output : label (string), probability (float)
  """
  def do(self, image):
    print("Start classifying the object")
    output_label, output_prob = self.classifier.do(image)
    print("object: %s, confidence: %.4f" % (output_label, output_prob))

    return output_label, output_prob


  """
  check whether the localizer and classifier alive
  @input  : N/A
  @output : True/False (bool)
  """
  def is_alive(self):
    if self.classifier:
      return True
    else:
      return False


  """
  retrain the model, especially the classifier
  @input  : uuid (user's unique id), phase (string)
  @output : new classifier model name (string)
  """
  def retrain(self, uuid, phase):
    print("Start retraining the model")
    new_classifier_model, new_classifier_label = retrain_model(uuid, phase)
    return new_classifier_model, new_classifier_label


def is_image(filename):
  return '.' in filename and \
          filename.rsplit('.', 1)[1].lower() in ("jpg", "png", "jpeg")


def main():
  if not args.pid or not args.model or not args.label:
    parser.print_help()
    sys.exit()

  p = args.pid
  img_dir = args.img_dir
  p_output = args.output_dir
  # now load the trained model
  recognizer = Recognizer(args.model, args.label, args.gpu_id)

  # retrieve all of the testing images first
  # i.e., test0 + test1 + test2
  gt_all = []
  pred_all = []
  conf_all = []
  gt_test0 = []
  pred_test0 = []
  conf_test0 = []
  gt_test = []
  pred_test = []
  conf_test = []
  test_phases = ["test0", "test1", "test2"]
  model = os.path.basename(args.model)
  model_phase = model.split('_')[0]

  if not os.path.exists(p_output):
    os.makedirs(p_output)

  for test in test_phases:
    p_test = os.path.join(img_dir, p, "t1", test)
    if not os.path.exists(p_test):
      continue
    
    if debug: print("Notice: found ", p_test)

    # get the list of objects
    objects = [each for each in os.listdir(p_test)
                     if os.path.isdir(os.path.join(p_test, each))]
    for obj in objects:
      obj_dir = os.path.join(p_test, obj)
      # make it lowercase and remove the special characters
      # so that it can be mathced with the output of our classifier
      re_obj = re.sub('\W+', ' ', obj).lower()
      for f in os.listdir(obj_dir):
        if not is_image(f):
          continue
        image = os.path.join(obj_dir, f)
        if debug:
          print("Notice: testing on ", image)
        # read image
        img = cv2.imread(os.path.join(obj_dir, image))
        pred_label, pred_conf = recognizer.do(img)
        # save the output
        filename = f.split('.')[0]
        gt = test + '_' + re_obj + '_' + filename
        gt_all.append(gt)
        pred_all.append(pred_label)
        conf_all.append(pred_conf)
        if test == "test0":
          gt_test0.append(gt)
          pred_test0.append(pred_label)
          conf_test0.append(pred_conf)
        elif test[-1] == model_phase[-1]:
          gt_test.append(gt)
          pred_test.append(pred_label)
          conf_test.append(pred_conf)

  # save the testing output here
  p_out = p + '+' + model.split('.')[0] + ".csv"
  p_out_csv = os.path.join(p_output, p_out)
  with open(p_out_csv, 'a+', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(["test all"])
    writer.writerow(gt_all)
    writer.writerow(pred_all)
    writer.writerow(conf_all)
    writer.writerow(["test0"])
    writer.writerow(gt_test0)
    writer.writerow(pred_test0)
    writer.writerow(conf_test0)
    writer.writerow(["test"])
    writer.writerow(gt_test)
    writer.writerow(pred_test)
    writer.writerow(conf_test)


if __name__ == "__main__":
  args = parser.parse_args()
  main()

