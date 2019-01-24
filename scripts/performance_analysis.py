import csv
import os
import re
import numpy as np
import seaborn as sns
import pandas as pd
import matplotlib.pyplot as plt

from sklearn.metrics import precision_recall_fscore_support
from os import listdir
from os.path import isfile, join
from pathlib import Path
from scipy import stats
from sys import platform

import sys
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("--log_dir", help="CSV file containing the subsets")
parser.add_argument("--data", help="Data to analyze")
parser.add_argument("--output_dir", help="Path to the output directory")
parser.add_argument("--type", help="Analysis type")
# parser.add_argument("--to_csv", dest='to_csv', action='store_true', help="Flag to ouptut csvs")

args = parser.parse_args()

def removeSpecial(text):
    result = text
    result = re.sub(r'xx..', '', result)
    result = re.sub('[^A-Za-z0-9]+', '', result)
    return result.lower()

def removeSpecialInList(list):
    for i in range(len(list)):
        list[i] = removeSpecial(list[i])
    return list

def removeSpecialInListFromKL(list, pid):
    result = []
    for i in range(len(list)):
        words = list[i].split("_")
        result.append(truth_clean["p"+str(pid) + "_" + words[0]+"_"+removeSpecial(words[1])+"_"+words[2]])
    return result

def getObjIndex(id, obj):
    oList = objects[id]
    for i in range(len(oList)):
        # print(i, oList)
        if oList[i] == obj:
            return i

def computeAccuracy(truth, prediction):
    return -1

def getCorrectConfidence(truth, pred, conf):
    correct_conf = [float(c) for t, p, c in zip(truth, pred, conf) if t == p]
    return correct_conf

def getWrongConfidence(truth, pred, conf):
    wrong_conf = [float(c) for t, p, c in zip(truth, pred, conf) if t != p]
    return wrong_conf

def computeAvgConfidence(truth, pred, conf, is_correct=True):
    if is_correct:
        ret_conf = getCorrectConfidence(truth, pred, conf)
    else:
        ret_conf = getWrongConfidence(truth, pred, conf)    
    
    if len(ret_conf) == 0:
        return 0., [0., ]
    return sum(ret_conf) / len(ret_conf), ret_conf


"""    
log_path = join("D:", os.sep, "Dropbox", "TeachableObjectRecognizer", "CrowdsourcingStudy", "logs")
if platform == "darwin":
    log_path = join(os.sep, "Users", "jonggihong", "Dropbox", "TeachableObjectRecognizer", "CrowdsourcingStudy", "logs")

output_path = join(os.sep, log_path, "recognitions", "numbered_outputs")
print(log_path)
"""
if not args.log_dir or not args.data or not args.output_dir or not args.type:
    parser.print_help()
    sys.exit()

log_path = args.log_dir
output_path = args.output_dir
print("log path:", log_path)
print("output path:", output_path)

plist = []
categories = []
objects = []
test_all_result = [[],[]] # train1, train2
test_0_result = [[],[]] # train1, train2
test_result = [[],[]] # train1, train2
accuracy_output = {}

with open(join(log_path, 'participant_list.csv')) as csvfile:
    plist_reader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(plist_reader, None)
    for row in plist_reader:
        plist.append(int(row[0]))

with open(join(log_path, 'object.csv')) as csvfile:
    object_reader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(object_reader, None)
    for row in object_reader:
        # print(removeSpecialInList(row[3:6]))
        categories.append(removeSpecial(row[2]))
        objects.append(removeSpecialInList(row[3:6]))

print(categories)
# print(objects)

truth_clean = {} # train1, train2
rec_path = join(log_path, 'recognition_results.csv')
with open(rec_path) as recfile:
    rec_reader = csv.reader(recfile, delimiter=',', quotechar='|')
    for row in rec_reader:
        truth_clean["p"+row[1]+'_'+row[3]+'_'+removeSpecial(row[7])+'_'+row[5]] = removeSpecial(row[6])
# print(truth_clean['p20_test0_fanta_1'])

# load all recognition results
data_path = join(log_path, args.data)
for pid in plist:
    participant_path = join(data_path, 'p'+str(pid))
    # print(listdir(participant_path))
    onlyfiles = [f for f in listdir(participant_path) if isfile(join(participant_path, f))]
    # print(onlyfiles)

    for file in onlyfiles:
        with open(join(participant_path, file)) as resultfile:
            result_reader = csv.reader(resultfile, delimiter=',', quotechar='|')
            test_all = []
            test_0 = []
            test = []

            row = next(result_reader) # test_all
            row = next(result_reader)  # test_all, truth
            test_all.append(removeSpecialInListFromKL(row, pid))
            row = next(result_reader)  # test_all, prediction
            test_all.append(removeSpecialInList(row))
            row = next(result_reader)  # test_all, confidence
            test_all.append(row)

            row = next(result_reader)  # test 0
            row = next(result_reader)  # test 0, truth
            test_0.append(removeSpecialInListFromKL(row, pid))
            row = next(result_reader)  # test 0, prediction
            test_0.append(removeSpecialInList(row))
            row = next(result_reader)  # test 0, confidence
            test_0.append(row)

            row = next(result_reader)  # test
            row = next(result_reader)  # test, truth
            test.append(removeSpecialInListFromKL(row, pid))
            row = next(result_reader)  # test, prediction
            test.append(removeSpecialInList(row))
            row = next(result_reader)  # test, confidence
            test.append(row)

            if 'train1' in file:
                test_all_result[0].append(test_all)
                test_0_result[0].append(test_0)
                test_result[0].append(test)
                accuracy_output[str(pid) + '_test1'] = {'pid': pid, 'model': 'test1'}
            else:
                test_all_result[1].append(test_all)
                test_0_result[1].append(test_0)
                test_result[1].append(test)
                accuracy_output[str(pid) + '_test2'] = {'pid': pid, 'model': 'test2'}

# print(len(test_all_result[0]), test_all_result[0])

precision_all_list = [[], []]
recall_all_list = [[], []]
fscore_all_list = [[], []]
precision_0_list = [[], []]
recall_0_list = [[], []]
fscore_0_list = [[], []]
accuracy_all_list = []
accuracy_0_list = []
conf_all_list = [[], []]
conf_output = {}
correct_conf_output = {}
wrong_conf_all_list = [[], []]
w_conf_output = {}
wrong_conf_output = {}
for index in range(0, 2):
    phase = "test%d" % (index+1)
    # print(phase)
    test_output = []
    wrong_output = []
    p_output = []
    conf_list = {}
    wrong_conf_list = {}
    for i in range(len(plist)):
        pKey = str(plist[i]) + '_test' + str(index + 1)
        result_all = test_all_result[index][i]
        precision, recall, fscore, support = precision_recall_fscore_support(result_all[0], result_all[1], average='macro')
        precision2, recall2, fscore2, support2 = precision_recall_fscore_support(result_all[0], result_all[1], average='weighted')
        # print(precision, precision2)
        confidence, _ = computeAvgConfidence(result_all[0], result_all[1], result_all[2])
        w_confidence, _ = computeAvgConfidence(result_all[0], result_all[1], result_all[2], is_correct=False)

        accuracy_all_list.append({'model': 'train' + str(index + 1), 'precision': precision, 'recall': recall, 'fscore': fscore})
        precision_all_list[index].append(precision)
        recall_all_list[index].append(recall)
        fscore_all_list[index].append(fscore)
        accuracy_output[pKey]['precision_all'] = precision
        accuracy_output[pKey]['recall_all'] = recall
        accuracy_output[pKey]['fscore_all'] = fscore
        accuracy_output[pKey]['confidence_all'] = confidence
        accuracy_output[pKey]['wrong_confidence_all'] = w_confidence

        result_0 = test_0_result[index][i]
        precision, recall, fscore, support = precision_recall_fscore_support(result_0[0], result_0[1], average='macro')
        confidence, _ = computeAvgConfidence(result_0[0], result_0[1], result_0[2])
        w_confidence, _ = computeAvgConfidence(result_0[0], result_0[1], result_0[2], is_correct=False)

        accuracy_0_list.append({'model': 'train' + str(index + 1), 'precision': precision, 'recall': recall, 'fscore': fscore})
        precision_0_list[index].append(precision)
        recall_0_list[index].append(recall)
        fscore_0_list[index].append(fscore)
        accuracy_output[pKey]['precision_0'] = precision
        accuracy_output[pKey]['recall_0'] = recall
        accuracy_output[pKey]['fscore_0'] = fscore
        accuracy_output[pKey]['confidence_0'] = confidence
        accuracy_output[pKey]['wrong_confidence_0'] = w_confidence

        result = test_result[index][i]
        confidence, correct_conf = computeAvgConfidence(result[0], result[1], result[2])
        w_confidence, wrong_conf = computeAvgConfidence(result[0], result[1], result[2], is_correct=False)
        accuracy_output[pKey]['confidence'] = confidence
        accuracy_output[pKey]['wrong_confidence'] = w_confidence

        # print("%d: %f confidence" % (i+1, accuracy_output[pKey]['confidence']))

        p_output.append(plist[i])
        test_output.append(confidence)
        conf_list[plist[i]] = correct_conf
        wrong_output.append(w_confidence)
        wrong_conf_list[plist[i]] = wrong_conf

    conf_output['pid'] = p_output
    conf_output[phase] = test_output
    correct_conf_output['pid'] = p_output
    correct_conf_output[phase] = conf_list
    phase_cnt = "%s_cnt" % (phase)
    correct_conf_output[phase_cnt] = len(conf_list)
    w_conf_output['pid'] = p_output
    w_conf_output[phase] = wrong_output
    wrong_conf_output['pid'] = p_output
    wrong_conf_output[phase] = wrong_conf_list


# print('precision_all', np.mean(precision_all_list[0]), np.mean(precision_all_list[1]))
# print('recall_all', np.mean(recall_all_list[0]), np.mean(recall_all_list[1]))
# print('fscore_all', np.mean(fscore_all_list[0]), np.mean(fscore_all_list[1]))
# print('fscore_all_median', np.median(fscore_all_list[0]), np.median(fscore_all_list[1]))
print()

t1_conf_dist = conf_output["test1"]
t2_conf_dist = conf_output["test2"]

t1_stat, t1_pval = stats.normaltest(t1_conf_dist)
t2_stat, t2_pval = stats.normaltest(t2_conf_dist)

print("for test1:", t1_stat, t1_pval)
print("for test2:", t2_stat, t2_pval)

alpha = 0.05
if t1_pval < alpha:
    print("The null hypothesis that a sample for test1 comes from a normal distribution can be rejected.")
if t2_pval < alpha:
    print("The null hypothesis that a sample for test2 comes from a normal distribution can be rejected.")

cat = pd.Series(categories)


"""
avg_df = pd.DataFrame.from_dict(conf_output)
avg_df = pd.melt(avg_df, id_vars="pid", var_name="test", value_name="confidence score")
print(avg_df)
sns.set(style="ticks")
g = sns.factorplot(x="pid", y="confidence score", hue="test", data=avg_df, kind="bar",
                    height=6, aspect=3, palette="colorblind", legend=False)
for ax in g.axes.flat:
    # print(ax)
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
plt.legend(loc='center right')
plt.tight_layout()
plt.show()

avg_df = pd.DataFrame.from_dict(w_conf_output)
avg_df = pd.melt(avg_df, id_vars="pid", var_name="test", value_name="confidence score")
print(avg_df)
sns.set(style="ticks")
g = sns.factorplot(x="pid", y="confidence score", hue="test", data=avg_df, kind="bar",
                    height=6, aspect=3, palette="colorblind", legend=False)
for ax in g.axes.flat:
    # print(ax)
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
plt.legend(loc='center right')
plt.tight_layout()
plt.show()
"""

if args.type == "correct":
    # draw figures for confidence score
    # test1_df = pd.DataFrame.from_dict(correct_conf_output["test1"], orient='index').transpose()
    test1_df = pd.DataFrame.from_dict(correct_conf_output["test1"], orient='index')
    average = test1_df.mean(axis='columns')
    test1_df['count'] = test1_df.count(axis='columns')
    test1_df['average'] = average
    test1_df['category'] = cat.values
    # print(test1_df.count(axis='columns'))
    # test1_df = test1_df.sort_values(['count'])
    # count_df = test1_df[['count']].reset_index().rename(columns={"index":"pid"})
    # count_df.index.name = "pid"
    # print(count_df)
    # test1_df = test1_df.drop(columns=['count']).transpose()
    test1_df = test1_df.reset_index().rename(columns={"index":"pid"})
    print(test1_df)
    sns.set(style="ticks")
    test1_df.to_csv("test1_correct.csv")

    """
    # confidence distribution
    f, ax = plt.subplots(figsize=(18, 6))
    ax = sns.boxplot(data=test1_df, palette="colorblind")
    ax2 = ax.twinx()
    sns.pointplot(x="pid", y="count", data=count_df, ax=ax2, markers="x", linestyles="--",
                  order=count_df["pid"].tolist(), color="#bb3f3f")
    ax.set_title('Test1')
    ax.set_ylabel('confidence')
    ax.set_xlabel('pid')
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
    plt.tight_layout()
    plt.show()
    """

    # average confidence
    g = sns.factorplot(x="count", y="average", hue="category", kind="point", join=False,
                       data=test1_df, height=4, aspect=3, palette="colorblind")
                       #palette=sns.color_palette("coolwarm", 13)
    # for ax in g.axes.flat:
    #     # print(ax)
    #     for label in ax.get_xticklabels():
    #         label.set_rotation(90)
    #         label.set_horizontalalignment('right')
    # plt.legend(loc='lower center', ncol=8)
    plt.tight_layout()
    # plt.savefig("test1.png")
    plt.show()

    g = sns.factorplot(x="count", y="average", col="category", kind="point",
                       data=test1_df, height=3, aspect=3.5)
    plt.tight_layout()
    plt.show()



    # test2_df = pd.DataFrame.from_dict(correct_conf_output["test2"], orient='index').transpose()
    test2_df = pd.DataFrame.from_dict(correct_conf_output["test2"], orient='index')
    average = test2_df.mean(axis='columns')
    test2_df['count'] = test2_df.count(axis='columns')
    test2_df['average'] = average
    test2_df['category'] = cat.values
    # test2_df = test2_df.sort_values(['count'])
    # count_df = test2_df[['count']].reset_index().rename(columns={"index":"pid"})
    # print(count_df)
    # test2_df = test2_df.drop(columns=['count']).transpose()
    test2_df = test2_df.reset_index().rename(columns={"index":"pid"})
    print(test2_df)
    test2_df.to_csv("test2_correct.csv")

    sns.set(style="ticks")


    """
    # confidence distribution
    f, ax = plt.subplots(figsize=(18, 6))
    ax = sns.boxplot(data=test2_df, palette="colorblind")
    ax2 = ax.twinx()
    sns.pointplot(x="pid", y="count", data=count_df, ax=ax2, markers="x", linestyles="--",
                  order=count_df["pid"].tolist(), color="#bb3f3f")
    ax.set_title('Test2')
    ax.set_ylabel('confidence')
    ax.set_xlabel('pid')
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
    plt.tight_layout()
    plt.show()
    """

    g = sns.factorplot(x="count", y="average", hue="category", kind="point", join=False,
                       data=test2_df, height=4, aspect=3, palette="colorblind")
    # for ax in g.axes.flat:
    #     # print(ax)
    #     for label in ax.get_xticklabels():
    #         label.set_rotation(90)
    #         label.set_horizontalalignment('right')
    # plt.legend(loc='lower center', ncol=8)
    plt.tight_layout()
    # plt.savefig("test2.png")
    plt.show()

    g = sns.factorplot(x="count", y="average", col="category", kind="point",
                       data=test2_df, height=3, aspect=3.5)
    plt.tight_layout()
    plt.show()

elif args.type == "wrong":
    # draw figures for wrong confidence score
    # test1_df = pd.DataFrame.from_dict(wrong_conf_output["test1"], orient='index').transpose()
    test1_df = pd.DataFrame.from_dict(wrong_conf_output["test1"], orient='index')
    average = test1_df.mean(axis='columns')
    test1_df['count'] = test1_df.count(axis='columns')
    print(test1_df)
    sns.set(style="ticks")
    """
    # confidence distribution
    test1_df = test1_df.sort_values(['count'])
    count_df = test1_df[['count']].reset_index().rename(columns={"index":"pid"})
    f, ax = plt.subplots(figsize=(18, 6))
    ax = sns.boxplot(data=test1_df.drop(columns=['count']).transpose(),
                     palette="colorblind")
    ax2 = ax.twinx()
    sns.pointplot(x="pid", y="count", ax=ax2, markers="x", linestyles="--",
                  data=count_df, order=count_df["pid"].tolist(), color="#bb3f3f")
    ax.set_title('Test1 Wrong Prediction')
    ax.set_ylabel('confidence')
    ax.set_xlabel('pid')
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
    plt.tight_layout()
    plt.show()
    """

    # categorical confidence 
    test1_df['average'] = average
    test1_df['category'] = cat.values
    test1_df = test1_df.reset_index().rename(columns={"index":"pid"})
    test1_df.to_csv("test1_wrong.csv")
    g = sns.factorplot(x="count", y="average", hue="category", kind="point", join=False,
                       data=test1_df, height=4, aspect=3, palette="colorblind")
    # for ax in g.axes.flat:
    #     # print(ax)
    #     for label in ax.get_xticklabels():
    #         label.set_rotation(90)
    #         label.set_horizontalalignment('right')
    # plt.legend(loc='lower center', ncol=8)
    plt.tight_layout()
    # plt.savefig("test1_wrong.png")
    plt.show()

    g = sns.factorplot(x="count", y="average", col="category", kind="point",
                       data=test1_df, height=3, aspect=3.5)
    plt.tight_layout()
    plt.show()
    

    # draw figures for wrong confidence score
    # test2_df = pd.DataFrame.from_dict(wrong_conf_output["test2"], orient='index').transpose()
    test2_df = pd.DataFrame.from_dict(wrong_conf_output["test2"], orient='index')
    average = test2_df.mean(axis='columns')
    test2_df['count'] = test2_df.count(axis='columns')
    print(test2_df)
    sns.set(style="ticks")

    """
    # confidence distribution
    test2_df = test2_df.sort_values(['count'])
    count_df = test2_df[['count']].reset_index().rename(columns={"index":"pid"})
    f, ax = plt.subplots(figsize=(18, 6))
    ax = sns.boxplot(data=test2_df.drop(columns=['count']).transpose(),
                     palette="colorblind")
    ax2 = ax.twinx()
    sns.pointplot(x="pid", y="count", ax=ax2, markers="x", linestyles="--",
                  data=count_df, order=count_df["pid"].tolist(), color="#bb3f3f")
    ax.set_title('Test2 Wrong Prediction')
    ax.set_ylabel('confidence')
    ax.set_xlabel('pid')
    for label in ax.get_xticklabels():
        label.set_rotation(90)
        label.set_horizontalalignment('right')
    plt.tight_layout()
    plt.show()
    """

    # categorical confidence
    test2_df['average'] = average
    test2_df['category'] = cat.values
    test2_df = test2_df.reset_index().rename(columns={"index":"pid"})
    test2_df.to_csv("test2_wrong.csv")
    g = sns.factorplot(x="count", y="average", hue="category", kind="point", join=False,
                       data=test2_df, height=4, aspect=3, palette="colorblind")
    # for ax in g.axes.flat:
    #     # print(ax)
    #     for label in ax.get_xticklabels():
    #         label.set_rotation(90)
    #         label.set_horizontalalignment('right')
    # plt.legend(loc='lower center', ncol=8)
    plt.tight_layout()
    # plt.savefig("test2_wrong.png")
    plt.show()

    g = sns.factorplot(x="count", y="average", col="category", kind="point",
                       data=test2_df, height=3, aspect=3.5)
    plt.tight_layout()
    plt.show()


# print('precision_0', np.mean(precision_0_list[0]), np.mean(precision_0_list[1]))
# print('recall_0', np.mean(recall_0_list[0]), np.mean(recall_0_list[1]))
# print('fscore_0', np.mean(fscore_0_list[0]), np.mean(fscore_0_list[1]))
# print('fscore_0_median', np.median(fscore_0_list[0]), np.median(fscore_0_list[1]))

# df_all = pd.DataFrame(accuracy_all_list)
# df_0 = pd.DataFrame(accuracy_0_list)

# ax = sns.boxplot(x="model", y="fscore", data=df_all)
# plt.show()
#
# ax = sns.boxplot(x="model", y="fscore", data=df_0)
# plt.show()



"""
# load subset recognition results
subset_path = join(os.sep, log_path, "recognitions", "subset_numbered_outputs")
subset_20_result = [[], []] #train1, train2
subset_5_result = [[], []] #train1, train2
subset_1_result = [[], []] #train1, train2

for pid in plist:

    participant_path = join(os.sep, subset_path, 'p'+str(pid))
    # print(listdir(participant_path))
    onlyfiles = [f for f in listdir(participant_path) if isfile(join(participant_path, f))]
    # print(onlyfiles)

    for file in onlyfiles:
        with open(join(os.sep, participant_path, file)) as resultfile:
            result_reader = csv.reader(resultfile, delimiter=',', quotechar='|')
            test_all = []
            test_0 = []
            test = []
            row = next(result_reader) # test_all
            row = next(result_reader)  # test_all, truth
            test_all.append(removeSpecialInListFromKL(row, pid))
            # test_all.append(removeSpecialInList(row))
            row = next(result_reader)  # test_all, prediction
            test_all.append(removeSpecialInList(row))

            row = next(result_reader)  # test 0
            row = next(result_reader)  # test 0, truth
            test_0.append(removeSpecialInListFromKL(row, pid))
            # test_0.append(removeSpecialInList(row))
            row = next(result_reader)  # test 0, prediction
            test_0.append(removeSpecialInList(row))

            row = next(result_reader) # test
            row = next(result_reader) # test, truth
            test.append(removeSpecialInListFromKL(row, pid))
            # test.append(removeSpecialInList(row))
            row = next(result_reader)  # test, prediction
            test.append(removeSpecialInList(row))

            if 'train1' in file:
                if '_20' in file:
                    subset_20_result[0].append(test)
                elif '_5' in file:
                    subset_5_result[0].append(test)
                elif '_1' in file:
                    subset_1_result[0].append(test)
            else:
                if '_20' in file:
                    subset_20_result[1].append(test)
                elif '_5' in file:
                    subset_5_result[1].append(test)
                elif '_1' in file:
                    subset_1_result[1].append(test)
"""
# print(len(subset_20_result[0]), len(subset_5_result[0]), len(subset_1_result[0]))

"""
precision_list = [[], []]
recall_0_list = [[], []]
fscore_0_list = [[], []]
accuracy_20_list = []
accuracy_5_list = []
accuracy_1_list = []
accuracy_list = []
for index in range(0, 2):
    for pIndex in range(len(plist)):
        result_20 = subset_20_result[index][pIndex]
        result_5 = subset_5_result[index][pIndex]
        result_1 = subset_1_result[index][pIndex]
        result = test_result[index][pIndex]
        acc_element = {'pid': pIndex+1, 'model': 'train' + str(index + 1)}

        precision20, recall20, fscore20, support20 = precision_recall_fscore_support(result_20[0], result_20[1], average='macro')
        accuracy_20_list.append({'pid': pIndex+1, 'model': 'train' + str(index + 1), 'precision': precision20, 'recall': recall20, 'fscore': fscore20})

        precision5, recall5, fscore5, support5 = precision_recall_fscore_support(result_5[0], result_5[1], average='macro')
        accuracy_5_list.append({'pid': pIndex+1, 'model': 'train' + str(index + 1), 'precision': precision5, 'recall': recall5, 'fscore': fscore5})

        precision1, recall1, fscore1, support1 = precision_recall_fscore_support(result_1[0], result_1[1], average='macro')
        accuracy_1_list.append({'pid': pIndex+1, 'model': 'train' + str(index + 1), 'precision': precision1, 'recall': recall1, 'fscore': fscore1})

        precision, recall, fscore, support = precision_recall_fscore_support(result[0], result[1], average='macro')
        accuracy_list.append({'pid': pIndex+1, 'model': 'train' + str(index + 1), 'precision': precision, 'recall': recall, 'fscore': fscore})

        # common = [i for i, j in zip(result_20[1], result_5[1]) if i == j]
        # print(len(common))

        pKey = str(plist[pIndex]) + '_test' + str(index + 1)
        accuracy_output[pKey]['precision20'] = precision20
        accuracy_output[pKey]['recall20'] = recall20
        accuracy_output[pKey]['fscore20'] = fscore20
        accuracy_output[pKey]['precision5'] = precision5
        accuracy_output[pKey]['recall5'] = recall5
        accuracy_output[pKey]['fscore5'] = fscore5
        accuracy_output[pKey]['precision1'] = precision1
        accuracy_output[pKey]['recall1'] = recall1
        accuracy_output[pKey]['fscore1'] = fscore1
        accuracy_output[pKey]['precision'] = precision
        accuracy_output[pKey]['recall'] = recall
        accuracy_output[pKey]['fscore'] = fscore
"""

# # measures = ['precision', 'recall', 'fscore']
# measures = ['fscore']
# print('subset-all')
# for v in measures:
#     values1 = [accuracy[v] for accuracy in accuracy_list if accuracy['model'] == 'train1']
#     values2 = [accuracy[v] for accuracy in accuracy_list if accuracy['model'] == 'train2']
#     print(v+'_test', np.mean(values1), np.mean(values2))
# print()
#
# print('subset-20')
# for v in measures:
#     values1 = [accuracy[v] for accuracy in accuracy_20_list if accuracy['model'] == 'train1']
#     values2 = [accuracy[v] for accuracy in accuracy_20_list if accuracy['model'] == 'train2']
#     print(v+'_test', np.mean(values1), np.mean(values2))
# print()
#
# print('subset-5')
# for v in measures:
#     values1 = [accuracy[v] for accuracy in accuracy_5_list if accuracy['model'] == 'train1']
#     values2 = [accuracy[v] for accuracy in accuracy_5_list if accuracy['model'] == 'train2']
#     print(v+'_test', np.mean(values1), np.mean(values2))
# print()
#
# print('subset-1')
# for v in measures:
#     values1 = [accuracy[v] for accuracy in accuracy_1_list if accuracy['model'] == 'train1']
#     values2 = [accuracy[v] for accuracy in accuracy_1_list if accuracy['model'] == 'train2']
#     print(v+'_test', np.mean(values1), np.mean(values2))
# print()
#
#
# df_20 = pd.DataFrame(accuracy_20_list)
# df_5 = pd.DataFrame(accuracy_5_list)
# df_1 = pd.DataFrame(accuracy_1_list)
# df = pd.DataFrame(accuracy_list)
#
#
# meanpointprops = dict(marker='o', markeredgecolor='red', markerfacecolor='red')

# ax = sns.boxplot(x="model", y="fscore", data=df_20, showmeans=True, meanprops=meanpointprops)
# plt.show()

# ax = sns.boxplot(x="model", y="fscore", data=df_5, showmeans=True, meanprops=meanpointprops)
# plt.show()

# ax = sns.boxplot(x="model", y="fscore", data=df_1, showmeans=True, meanprops=meanpointprops)
# plt.show()

# ax = sns.boxplot(x="model", y="fscore", data=df, showmeans=True, meanprops=meanpointprops)
# plt.show()



# reading participants' background information
# bq1: use a mobile device
# bq2: take pictures using a mobile phone
# bq3: Use apps that can recognize type of objects, food, or plants through the camera
# bq4: What do you believe artificial intelligence is?
# bq5: How would you classify your level of familiarity with machine learning?
# bq6: How would you train your phone to recognize your facial expressions (e.g., happy, sad, calm, angry)?
# bq7: How would you test if it works well?
"""
pInfo = {}
with open(join(log_path, 'participant_info.csv')) as csvfile:
    plist_reader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(plist_reader, None)
    for row in plist_reader:
        pInfo[int(row[0])] = {'age': int(row[1]), 'gender': row[2], 'occupation': row[3], 'dom_hand': row[4], 'has_vi': row[5], 'has_mi': row[6]
                                 , 'bq1': row[7], 'bq2': row[8], 'bq3': row[9], 'bq4': row[10], 'bq5': row[11], 'bq6': row[12], 'bq7': row[13]
                                }


# write the scores to a file
lines = [[accuracy['pid'], accuracy['model'], pInfo[accuracy['pid']]['bq5'],
                                            accuracy['precision'], accuracy['recall'], accuracy['fscore'],
                                            accuracy['precision20'], accuracy['recall20'], accuracy['fscore20'],
                                            accuracy['precision5'], accuracy['recall5'], accuracy['fscore5'],
                                            accuracy['precision1'], accuracy['recall1'], accuracy['fscore1'],
                                            accuracy['precision_all'], accuracy['recall_all'], accuracy['fscore_all'],
                                            accuracy['precision_0'], accuracy['recall_0'], accuracy['fscore_0']] for ptid, accuracy in accuracy_output.items()]
output_path = join(log_path, "r", "performance_result.csv")

with open(output_path, 'w', newline='') as csvfile:
    csv_writer = csv.writer(csvfile, delimiter=',', quotechar='|', quoting=csv.QUOTE_MINIMAL)
    csv_writer.writerow(['PID', 'Phase', 'knowledge',
                         'precision', 'recall', 'fscore',
                         'precision20', 'recall20', 'fscore20',
                         'precision5', 'recall5', 'fscore5',
                         'precision1', 'recall1', 'fscore1',
                         'precision_all', 'recall_all', 'fscore_all',
                         'precision_0', 'recall_0', 'fscore_0'])
    for line in lines:
        csv_writer.writerow(line)
"""

# show graph of accuracy for knowledge levels
# dataWithInfo = []
#
# for ptid, accuracy in accuracy_output.items():
#     item = {'pid': accuracy['pid'], 'precision': accuracy['precision'], 'recall': accuracy['recall'], 'fscore': accuracy['fscore'], 'knowledge': pInfo[accuracy['pid']]['bq5']}
#     dataWithInfo.append(item)
#     # print(item)
#
# df = pd.DataFrame(dataWithInfo)
# meanpointprops = dict(marker='o', markeredgecolor='red', markerfacecolor='red')
# ax = sns.boxplot(x="knowledge", y="fscore", data=df, showmeans=True, meanprops=meanpointprops)
# plt.show()



print('finish')

