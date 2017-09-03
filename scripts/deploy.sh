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
echo "1. Pulling latest code...";
echo "------------------------";

APP_PATH="/var/www/eatvora/${FOLDER}";

cd ${APP_PATH}

# Force to use remote commits
echo "git checkout ${BRANCH}";
git checkout ${BRANCH}

echo "git fetch origin ${BRANCH}";
git fetch origin ${BRANCH}

echo "git reset --hard FETCH_HEAD";
git reset --hard FETCH_HEAD

echo "git clean -df";
git clean -df

composer install -n --no-dev --prefer-dist

# Build static assets
echo "-------------------------";
echo "2. Install Laravel Dependencies...";
echo "-------------------------";

echo "composer install";
composer install

echo "";

# Build static assets
echo "-------------------------";
echo "3. Build static assets...";
echo "-------------------------";

echo "yarn install";
yarn install

echo "yarn run production --force";
yarn run production

# Migrate
echo "-------------------------";
echo "4. Migrate Database...";
echo "-------------------------";


echo "php artisan migrate --force";
php artisan migrate --force

echo "";
echo "✨ Finished!";
