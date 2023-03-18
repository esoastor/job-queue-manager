#!/usr/bin/env bash
OLD_TAG=$(git describe --tags --abbrev=0)
LAST_NUMBER=${OLD_TAG: -1}
if [ $LAST_NUMBER -eq 9 ]; then
  NEW_LAST_NUMBER="00"
else
  NEW_LAST_NUMBER=$(($LAST_NUMBER + 1))
fi
NEW_TAG="${OLD_TAG::-1}${NEW_LAST_NUMBER}"

git tag $NEW_TAG
git push origin $NEW_TAG
git tag --delete $OLD_TAG
git push --delete origin $OLD_TAG