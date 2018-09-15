# RengFrom

An app for managing registration to structured events. Built on Symfony 4.

## Launching the app

1. To run this app you need to have a server with PHP 7.1, Node.js, PHP Composer, MySQL/MariaDB database and PHP web server (Apache, Nginx or any other).
2. In the server, clone this repo and navigate into the folder.
```Bash
git clone https://github.com/aivarasbaranauskas/RengForm.git
cd RengForm
```
3. Install all the dependencies.
```Bash
npm install
composer install --no-dev --optimize-autoloader
npx grunt
```
`.env` file update DATABASE_URL parameter to your database connection and change APP_ENV parameter to 'prod' (without quotes).
5. Create database and run its migrations to latest version.
```Bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
6. Clear the cache.
```Bash
php bin/console cache:clear --env=prod --no-debug
```
7. Configure you PHP web server to point to _/public_ folder and redirect all requests to _/public/index.php_. Example configurations can be found in [Symfony docs](https://symfony.com/doc/current/setup/web_server_configuration.html).

## Running development environment

1. To run development environment you will need a Vagrant. Install it first.
2. Clone this repo and navigate into the folder.
```Bash
git clone https://github.com/aivarasbaranauskas/RengForm.git
cd RengForm
```
3. Launch Vagrant. It will download and install all what is needed into the VM.
```Bash
vagrant up
```
4. SSH into VM and build project with it's dependencies.
```Bash
npm install
composer install --optimize-autoloader
npx grunt
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
5. That's it! You should be able to access the web through [rengform.test](rengform.test) .
