"""
scrip to train models with the subset selections
"""

import os
import csv
import sys
import time
import shlex
import argparse

import numpy as np
import subprocess as sp


parser = argparse.ArgumentParser()
parser.add_argument("--subset_dir", help="Path to a model directory")
parser.add_argument("--output_dir", help="Path to the output directory")

args = parser.parse_args()

debug = True
ACCEPTABLE_AVAILABLE_MEMORY = 11170

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
  if not args.subset_dir or not args.output_dir:
    parser.print_help()
    sys.exit()

  # subsets/p1/train1/20/...
  parent_dir = args.subset_dir
  pids = [each for each in os.listdir(parent_dir)
                if os.path.isdir(os.path.join(parent_dir, each))]
  for pid in pids:
    pid_dir = os.path.join(parent_dir, pid)
    output_dir = os.path.join(args.output_dir, pid)
    if pid not in ["p258", "p259"]:
      continue
    
    if not os.path.exists(output_dir):
      os.makedirs(output_dir)

    for phase in os.listdir(pid_dir):
      phase_dir = os.path.join(pid_dir, phase)
      for subset in os.listdir(phase_dir):
        subset_dir = os.path.join(phase_dir, subset)

        graph_file = "%s_graph_%s.pb" % (phase, subset)
        label_file = "%s_label_%s.txt" % (phase, subset)
        output_graph = os.path.join(output_dir, graph_file)
        output_labels = os.path.join(output_dir, label_file)
        if os.path.exists(output_graph) and os.path.exists(output_labels):
          print("Notice: already built", output_graph)
          continue

        # check the GPU availability
        gpu_to_use = get_idle_gpu()
        # now call the script for training
        # gpu_to_use = -1

        script_to_run = """python3 IAM_retrain.py \
                        --image_dir %s \
                        --output_graph %s \
                        --output_labels %s \
                        --how_many_training_steps 500 \
                        --learning_rate 0.01 \
                        --training_number all \
                        --validation_number 1 \
                        --testing_number 1 \
                        --train_batch_size 16 \
                        --test_batch_size 1 \
                        --validation_batch_size 1 \
                        --final_tensor_name final_result \
                        --gpu_to_use %d """ % \
                        (subset_dir, output_graph, output_labels, gpu_to_use)

        if debug:
          print("Notice: GPU %s will be using" % (gpu_to_use))
          print("Notice: running ", script_to_run)

        # os.system(script_to_run)
        command = shlex.split(script_to_run)
        print(command)
        sp.Popen(command)
        # delay
        time.sleep(9)

  print("Notice: DONE")


if __name__ == "__main__":
  main()
