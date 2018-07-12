"""
Techable Object Recognizer for crowd-study
- consists of one deep learning model
- 1) object classifier

written by Kyungjun Lee
"""
from TOR_classifier import Classifier
# retrain function
from retrain import retrain_model

import cv2


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
              debug = False):
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
