# read csvs
test1_correct <- read.csv(file="test1_correct.csv", header=TRUE, sep=",")
test2_correct <- read.csv(file="test2_correct.csv", header=TRUE, sep=",")
test1_wrong <- read.csv(file="test1_wrong.csv", header=TRUE, sep=",")
test2_wrong <- read.csv(file="test2_wrong.csv", header=TRUE, sep=",")

# non-parametric test
# Wilcoxon Signed-Rank test
wilcox.test(test1_correct$average, test2_correct$average, paired=TRUE)
wilcox.test(test1_wrong$average, test2_wrong$average, paired=TRUE)

# Wilcoxon Signed-Rank test for each category
t1_c_bottle = test1_correct[ which(test1_correct$category == "bottle"), ]
t2_c_bottle = test2_correct[ which(test2_correct$category == "bottle"), ]
t1_w_bottle = test1_wrong[ which(test1_wrong$category == "bottle"), ]
t2_w_bottle = test2_wrong[ which(test2_wrong$category == "bottle"), ]
wilcox.test(t1_c_bottle$average, t2_c_bottle$average, paired=TRUE)
wilcox.test(t1_w_bottle$average, t2_w_bottle$average, paired=TRUE)

t1_c_cereal = test1_correct[ which(test1_correct$category == "cereal"), ]
t2_c_cereal = test2_correct[ which(test2_correct$category == "cereal"), ]
t1_w_cereal = test1_wrong[ which(test1_wrong$category == "cereal"), ]
t2_w_cereal = test2_wrong[ which(test2_wrong$category == "cereal"), ]
wilcox.test(t1_c_cereal$average, t2_c_cereal$average, paired=TRUE)
wilcox.test(t1_w_cereal$average, t2_w_cereal$average, paired=TRUE)

t1_c_drink = test1_correct[ which(test1_correct$category == "drink"), ]
t2_c_drink = test2_correct[ which(test2_correct$category == "drink"), ]
t1_w_drink = test1_wrong[ which(test1_wrong$category == "drink"), ]
t2_w_drink = test2_wrong[ which(test2_wrong$category == "drink"), ]
wilcox.test(t1_c_drink$average, t2_c_drink$average, paired=TRUE)
wilcox.test(t1_w_drink$average, t2_w_drink$average, paired=TRUE)

t1_c_spice = test1_correct[ which(test1_correct$category == "spice"), ]
t2_c_spice = test2_correct[ which(test2_correct$category == "spice"), ]
t1_w_spice = test1_wrong[ which(test1_wrong$category == "spice"), ]
t2_w_spice = test2_wrong[ which(test2_wrong$category == "spice"), ]
wilcox.test(t1_c_spice$average, t2_c_spice$average, paired=TRUE)
wilcox.test(t1_w_spice$average, t2_w_spice$average, paired=TRUE)

t1_c_snack = test1_correct[ which(test1_correct$category == "snack"), ]
t2_c_snack = test2_correct[ which(test2_correct$category == "snack"), ]
t1_w_snack = test1_wrong[ which(test1_wrong$category == "snack"), ]
t2_w_snack = test2_wrong[ which(test2_wrong$category == "snack"), ]
wilcox.test(t1_c_snack$average, t2_c_snack$average, paired=TRUE)
wilcox.test(t1_w_snack$average, t2_w_snack$average, paired=TRUE)
