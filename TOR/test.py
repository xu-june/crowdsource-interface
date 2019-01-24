"""
script to test all models again
"""

import os
import sys
import time
import shlex
import argparse

import numpy as np
import subprocess as sp

# import re
# import cv2
# import csv


# from TOR import Recognizer

parser = argparse.ArgumentParser()
parser.add_argument("--model_dir", help="Path to a model directory")
parser.add_argument("--img_dir", help="Path to the image dataset")
parser.add_argument("--output_dir", help="Path to the output directory")

args = parser.parse_args()

debug = True
ACCEPTABLE_AVAILABLE_MEMORY = 11171

# https://github.com/yselivonchyk/TensorFlow_DCIGN/blob/master/utils.py
def _output_to_list(output):
  return output.decode('ascii').split('\n')[:-1]


def get_idle_gpu(leave_unmasked=0, random=True):
  try:
    command = "nvidia-smi --query-gpu=memory.free --format=csv"
    memory_free_info = _output_to_list(sp.check_output(command.split()))[1:]
    memory_free_values = [int(x.split()[0]) for i, x in enumerate(memory_free_info)]
    available_gpus = [i for i, x in enumerate(memory_free_values) if x > ACCEPTABLE_AVAILABLE_MEMORY]

    if len(available_gpus) <= leave_unmasked:
      print('Found only %d usable GPUs in the system' % len(available_gpus))
      return -1

    if random:
      available_gpus = np.asarray(available_gpus)
      np.random.shuffle(available_gpus)

    gpu_to_use = available_gpus[0]
    print("Using GPU: ", gpu_to_use)
    
    return int(gpu_to_use)
    """
    # update CUDA variable
    gpus = available_gpus[:leave_unmasked]
    setting = ','.join(map(str, gpus))
    os.environ["CUDA_VISIBLE_DEVICES"] = setting
    print('Left next %d GPU(s) unmasked: [%s] (from %s available)'
          % (leave_unmasked, setting, str(available_gpus)))
    """
  except FileNotFoundError as e:
    print('"nvidia-smi" is probably not installed. GPUs are not masked')
    print(e)
    return -1
  except sp.CalledProcessError as e:
    print("Error on GPU masking:\n", e.output)
    return -1


def main():
  if not args.model_dir or not args.img_dir or not args.output_dir:
    parser.print_help()
    sys.exit()

  train_phases = ["train1", "train2"]
  test_phases = ["test0", "test1", "test2"]
  skip_pids = ["p209"]

  model_dir = args.model_dir
  participants = [each for each in os.listdir(model_dir)
                        if os.path.isdir(os.path.join(model_dir, each))]
  img_dir = args.img_dir
  output_dir = args.output_dir

  for p in participants:
    p_output = os.path.join(output_dir, p)
    if p in skip_pids or os.path.exists(p_output):
      if debug: print("Notice: skipping ", p)
      continue
    for train in train_phases:
      model_f = "t1_" + train + "_graph.pb"
      label_f = "t1_" + train + "_labels.txt"
      p_model = os.path.join(model_dir, p, model_f)
      p_label = os.path.join(model_dir, p, label_f)

      if not os.path.exists(p_model) or not os.path.exists(p_label):
        continue

      if debug: print("Notice: found ", p_model, p_label)

      # check the GPU availability
      gpu_to_use = get_idle_gpu()
      # now call the script for training
      # gpu_to_use = -1

      script_to_run = """python3 TOR.py \
                      --pid %s \
                      --model %s \
                      --label %s \
                      --img_dir %s \
                      --output_dir %s \
                      --gpu_id %d""" % \
                      (p, p_model, p_label, img_dir, p_output, gpu_to_use)

      if debug:
        print("Notice: GPU %s will be using" % (gpu_to_use))
        print("Notice: running ", script_to_run)

      # os.system(script_to_run)
      command = shlex.split(script_to_run)
      print(command)
      sp.Popen(command)
      # delay
      time.sleep(3)

      """
      # now load the trained model
      recognizer = Recognizer(p_model, p_label)

      # retrieve all of the testing images first
      # i.e., test0 + test1 + test2
      gt_all = []
      pred_all = []
      gt_test0 = []
      pred_test0 = []
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
            pred_label, _ = recognizer.do(img)
            # save the output
            gt_all.append(re_obj)
            pred_all.append(pred_label)
            if test == "test0":
              gt_test0.append(re_obj)
              pred_test0.append(pred_label)

      # the testing is finished, so delete the recognizer
      del recognizer
      recognizer = None

      # save the testing output here
      if not os.path.exists(p_output):
        os.makedirs(p_output)

      p_out = p + '+' + train + ".csv"
      p_out_csv = os.path.join(p_output, p_out)
      with open(p_out_csv, 'a+', newline='') as csvfile:
        writer = csv.writer(csvfile)
        writer.writerow("test all")
        writer.writerow(gt_all)
        writer.writerow(pred_all)
        writer.writerow("test0")
        writer.writerow(gt_test0)
        writer.writerow(pred_test0)
      """

  print("Done")


if __name__ == "__main__":
  main()

