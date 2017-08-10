#!/bin/bash -e

BRANCH=$1;

FOLDER="";

echo "Branch is ${BRANCH}";

if [ "${BRANCH}" == "master" ]; then
    FOLDER="eatvora-web-production";
elif [ "${BRANCH}" == "develop" ]; then
    FOLDER="eatvora-web-staging";
fi;

if [ "${FOLDER}" == "" ]; then
    echo "It's not master or develop branch. No deployement process";
    exit;
fi;

echo "----------------------------------";
echo "Eatvora Deploying Branch ${BRANCH}";
echo "----------------------------------";
echo "";


# Pulling Latest Code
echo "------------------------";
echo "1. Pulling lates code...";
echo "------------------------";

APP_PATH="/var/www/eatvora/${FOLDER}";

cd ${APP_PATH}

git checkout ${BRANCH}
git pull origin ${BRANCH} --force

composer install -n --no-dev --prefer-dist

# Build static assets
echo "-------------------------";
echo "2. Build static assets...";
echo "-------------------------";

yarn install

yarn run production --force

php artisan migrate

echo "";
echo "✨ Finished!";
