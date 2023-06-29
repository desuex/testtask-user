## Simple PHP user management application

Requires PHP 8.1+ (with ext PDO) and composer.

### Installation
* Install composer dependencies with `composer install`
* Make sure `./database` is writable
* Prepare `.env` file: `cp .env.example .env`
* run `php console/init_database.php`

### Usage

Now you can run the web server with PHP Built-in Server:

`php -S localhost:8080 -t www `

Now open http://localhost:8080 in browser.


### Tests
Run tests: 

`php vendor/phpunit/phpunit/phpunit --no-configuration tests`
