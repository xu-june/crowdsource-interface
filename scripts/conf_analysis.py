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

import argparse

parser = argparse.ArgumentParser()
parser.add_argument("--csv", help="CSV file containing the subsets")
parser.add_argument("--output_dir", help="Path to the output directory")

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


log_path = join("D:", os.sep, "Dropbox", "TeachableObjectRecognizer", "CrowdsourcingStudy", "logs")
if platform == "darwin":
    log_path = join(os.sep, "Users", "jonggihong", "Dropbox", "TeachableObjectRecognizer", "CrowdsourcingStudy", "logs")

output_path = join(os.sep, log_path, "recognitions", "numbered_outputs")
print(log_path)

plist = []
objects = []
test_all_result = [[],[]] # train1, train2
test_0_result = [[],[]] # train1, train2
test_result = [[],[]] # train1, train2
accuracy_output = {}

with open(join(os.sep, log_path, 'participant_list.csv')) as csvfile:
    plist_reader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(plist_reader, None)
    for row in plist_reader:
        plist.append(int(row[0]))

"""
with open(join(os.sep, log_path, 'object.csv')) as csvfile:
    object_reader = csv.reader(csvfile, delimiter=',', quotechar='|')
    next(object_reader, None)
    for row in object_reader:
        # print(removeSpecialInList(row[3:6]))
        objects.append(removeSpecialInList(row[3:6]))
"""
# print(objects)

truth_clean = {} # train1, train2
rec_path = join(os.sep, log_path, 'recognition_results.csv')
with open(rec_path) as recfile:
    rec_reader = csv.reader(recfile, delimiter=',', quotechar='|')
    for row in rec_reader:
        truth_clean["p"+row[1]+'_'+row[3]+'_'+removeSpecial(row[7])+'_'+row[5]] = removeSpecial(row[6])
# print(truth_clean['p20_test0_fanta_1'])

# load all recognition results
for pid in plist:
    participant_path = join(os.sep, output_path, 'p'+str(pid))
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
            row = next(result_reader)  # test_all, prediction
            test_all.append(removeSpecialInList(row))

            row = next(result_reader)  # test 0
            row = next(result_reader)  # test 0, truth
            test_0.append(removeSpecialInListFromKL(row, pid))
            row = next(result_reader)  # test 0, prediction
            test_0.append(removeSpecialInList(row))
            row = next(result_reader)  # test
            row = next(result_reader)  # test, truth
            test.append(removeSpecialInListFromKL(row, pid))
            row = next(result_reader)  # test, prediction
            test.append(removeSpecialInList(row))

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
print(len(test_all_result[0]), test_all_result[0])


precision_all_list = [[], []]
recall_all_list = [[], []]
fscore_all_list = [[], []]
precision_0_list = [[], []]
recall_0_list = [[], []]
fscore_0_list = [[], []]
accuracy_all_list = []
accuracy_0_list = []
for index in range(0, 2):
    for i in range(len(plist)):
        pKey = str(plist[i]) + '_test' + str(index + 1)
        result_all = test_all_result[index][i]
        precision, recall, fscore, support = precision_recall_fscore_support(result_all[0], result_all[1], average='macro')
        precision2, recall2, fscore2, support2 = precision_recall_fscore_support(result_all[0], result_all[1], average='weighted')
        print(precision, precision2)

        accuracy_all_list.append({'model': 'train' + str(index + 1), 'precision': precision, 'recall': recall, 'fscore': fscore})
        precision_all_list[index].append(precision)
        recall_all_list[index].append(recall)
        fscore_all_list[index].append(fscore)
        accuracy_output[pKey]['precision_all'] = precision
        accuracy_output[pKey]['recall_all'] = recall
        accuracy_output[pKey]['fscore_all'] = fscore

        result_0 = test_0_result[index][i]
        precision, recall, fscore, support = precision_recall_fscore_support(result_0[0], result_0[1], average='macro')

        accuracy_0_list.append({'model': 'train' + str(index + 1), 'precision': precision, 'recall': recall, 'fscore': fscore})
        precision_0_list[index].append(precision)
        recall_0_list[index].append(recall)
        fscore_0_list[index].append(fscore)
        accuracy_output[pKey]['precision_0'] = precision
        accuracy_output[pKey]['recall_0'] = recall
        accuracy_output[pKey]['fscore_0'] = fscore

print('precision_all', np.mean(precision_all_list[0]), np.mean(precision_all_list[1]))
print('recall_all', np.mean(recall_all_list[0]), np.mean(recall_all_list[1]))
print('fscore_all', np.mean(fscore_all_list[0]), np.mean(fscore_all_list[1]))
# print('fscore_all_median', np.median(fscore_all_list[0]), np.median(fscore_all_list[1]))
print()

# print('precision_0', np.mean(precision_0_list[0]), np.mean(precision_0_list[1]))
# print('recall_0', np.mean(recall_0_list[0]), np.mean(recall_0_list[1]))
# print('fscore_0', np.mean(fscore_0_list[0]), np.mean(fscore_0_list[1]))
# print('fscore_0_median', np.median(fscore_0_list[0]), np.median(fscore_0_list[1]))

df_all = pd.DataFrame(accuracy_all_list)
df_0 = pd.DataFrame(accuracy_0_list)

# ax = sns.boxplot(x="model", y="fscore", data=df_all)
# plt.show()
#
# ax = sns.boxplot(x="model", y="fscore", data=df_0)
# plt.show()




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


# print(len(subset_20_result[0]), len(subset_5_result[0]), len(subset_1_result[0]))

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
pInfo = {}
with open(join(os.sep, log_path, 'participant_info.csv')) as csvfile:
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
output_path = join(os.sep, log_path, "r", "performance_result.csv")

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

