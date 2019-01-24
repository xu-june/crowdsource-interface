"""
scrip to train models with the subset selections
"""

import os
import sys
import shutil
import argparse

import pandas as pd

parser = argparse.ArgumentParser()
parser.add_argument("--csv", help="CSV file containing the subsets")
parser.add_argument("--img_dir", help="Path to the image dataset")
parser.add_argument("--output_dir", help="Path to the output directory")

args = parser.parse_args()

debug = True


def copy_subset(pid, trial, phase, obj_name, subset):
  # images/p94/t1/train1
  src_dir = os.path.join(args.img_dir, pid, 't'+trial, phase, obj_name)
  if not os.path.exists(src_dir):
    print("ERROR: image folder not existed", src_dir)
    return
  
  subsets = subset.split(':')
  subset_num = str(len(subsets))
  
  dst_dir = os.path.join(args.output_dir, pid, phase, subset_num, obj_name)
  if not os.path.exists(dst_dir):
    os.makedirs(dst_dir)

  if debug:
    print("Notice: copying images from ", src_dir, " to ", dst_dir)

  for each in subsets:
    img_file = each + '.png'
    src_file = os.path.join(src_dir, img_file)
    dst_file = os.path.join(dst_dir, img_file)
    shutil.copyfile(src_file, dst_file)

  return 


def main():
  if not args.csv or not args.img_dir or not args.output_dir:
    parser.print_help()
    sys.exit()

  if not os.path.exists(args.csv):
    print("ERROR: not existed,", args.csv)
    sys.exit()

  df = pd.read_csv(args.csv)
  """
  participant_id,trial,category,name1,name2,name3,
  subset_training1_2_20,subset_training1_2_5,subset_training1_2_1,
  subset_training2_2_20,subset_training2_2_5,subset_training2_2_1,
  subset_training1_3_20,subset_training1_3_5,subset_training1_3_1,
  subset_training2_3_20,subset_training2_3_5,subset_training2_3_1,
  subset_training1_1_20,subset_training1_1_5,subset_training1_1_1,
  subset_training2_1_20,subset_training2_1_5,subset_training2_1_1
  """
  for row in df.itertuples():
    # print(row)
    # print(row)
    # print(row.Index)
    pid = 'p' + str(row.participant_id)
    if pid not in ["p258", "p259"]:
      continue
    trial = str(row.trial)
    obj1 = row.name1
    obj2 = row.name2
    obj3 = row.name3
    # train1
    copy_subset(pid, trial, "train1", obj1, row.subset1_1_20)
    copy_subset(pid, trial, "train1", obj1, row.subset1_1_5)
    copy_subset(pid, trial, "train1", obj1, str(row.subset1_1_1))
    copy_subset(pid, trial, "train1", obj2, row.subset1_2_20)
    copy_subset(pid, trial, "train1", obj2, row.subset1_2_5)
    copy_subset(pid, trial, "train1", obj2, str(row.subset1_2_1))
    copy_subset(pid, trial, "train1", obj3, row.subset1_3_20)
    copy_subset(pid, trial, "train1", obj3, row.subset1_3_5)
    copy_subset(pid, trial, "train1", obj3, str(row.subset1_3_1))
    # train2
    copy_subset(pid, trial, "train2", obj1, row.subset2_1_20)
    copy_subset(pid, trial, "train2", obj1, row.subset2_1_5)
    copy_subset(pid, trial, "train2", obj1, str(row.subset2_1_1))
    copy_subset(pid, trial, "train2", obj2, row.subset2_2_20)
    copy_subset(pid, trial, "train2", obj2, row.subset2_2_5)
    copy_subset(pid, trial, "train2", obj2, str(row.subset2_2_1))
    copy_subset(pid, trial, "train2", obj3, row.subset2_3_20)
    copy_subset(pid, trial, "train2", obj3, row.subset2_3_5)
    copy_subset(pid, trial, "train2", obj3, str(row.subset2_3_1))

  print("Done")


if __name__ == "__main__":
  main()