#!/usr/bin/env bash
OLD_TAG=$(git describe --tags --abbrev=0)
NEW_LAST_NUMBER=$((${OLD_TAG:0-1} + 1))
NEW_TAG="${OLD_TAG::-1}${NEW_LAST_NUMBER}"

git tag $NEW_TAG
git push origin $NEW_TAG
git tag --delete $OLD_TAG
git push --delete origin $OLD_TAG