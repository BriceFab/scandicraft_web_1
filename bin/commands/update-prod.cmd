git status
REM git diff <file>
git stash
git pull
REM composer install --no-dev --optimize-autoloader
clear cache: APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear