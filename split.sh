#!/bin/bash
#
# This script is used to split the master version of elcodi/elcodi
# into several independent repositories. It now uses git filter-branch
# to execute the split. The same result, with a little more security,
# can be achieved by using "subtree split" in git v1.8
#
pushd /tmp
rm -rf symfony.PaymentSuite.tmp
git clone git@github.com:PaymentSuite/paymentsuite.git symfony.PaymentSuite.tmp
pushd symfony.PaymentSuite.tmp
for i in $(ls -1 src/PaymentSuite/); do
    git subtree split --prefix=src/PaymentSuite/$i/ --branch split-branch
    git checkout split-branch
    git remote add rewrite git@github.com:PaymentSuite/$i.git
    echo $i
    git push rewrite split-branch:master
    git checkout master
    git branch -D split-branch
    git remote rm rewrite
done
rm -rf /tmp/symfony.PaymentSuite.tmp
popd
popd
