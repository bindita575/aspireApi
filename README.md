##System Requirements PHP 8 Laravel 9 NPM Mysql Composer git
##Installation steps

run git clone https://github.com/bindita575/aspireApi.git
After clonning the repository, need to follow below steps
go to the folder where repo has been cloned.
create .env file from .env.example.
create database and add the config to the .env
run "composer install command"
run "npm install" to install npm packages
php artisan migrate
php artisan db:seed --class=RoleSeeder
php artisan db:seed
run "php artisan serve" to start the server after that we can access the project using http://127.0.0.1:8000/


##After INstallation you can use APIs