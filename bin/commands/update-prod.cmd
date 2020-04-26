git status
REM git diff <file>
git stash
git pull
REM composer update --no-dev --optimize-autoloader
REM composer install --no-dev --optimize-autoloader

php bin/console doctrine:migrations:migrate
php bin/console doctrine:migrations:execute --up 20200424184143
php bin/console doctrine:migrations:version --add --all rem attention !If you don't want to use this workflow and instead create your schema via doctrine:schema:create, you can tell Doctrine to skip all existing migrations:

php bin/console doctrine:migrations:status --show-versions

APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear