## Simple PHP user management application

Requires PHP 8.1 (with ext PDO) and composer.

### Installation

* Make sure `./database` is writable
* Prepare `.env` file: `cp .env.example .env`
* run `php console/init_database.php`

Now you can run the web server with PHP Built-in Server:

`php -S localhost:8080 -t www `

Now open http://localhost:8080 in browser.

