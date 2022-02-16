#!/bin/bash

composer fix-style

git add .

echo -e "\033[32m 请输入 commit 备注： \033[0m"
read message
git commit -m "$message"

echo -e "\033[32m 当前分支： \033[0m"
branch=`git rev-parse --abbrev-ref HEAD`
echo -e "\033[31m $branch \033[0m"

git push origin $branch

echo -e "\033[32m 提交成功！ \033[0m"

