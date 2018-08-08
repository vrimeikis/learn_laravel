# Learn Laravel

Learn laravel from scratch. Project hac articles with author and categories.

## Requirements

### Tools

- composer
- Git
- npm

### Server

- `PHP` >= **7.1.3**
- `OpenSSL` PHP Extension
- `PDO` PHP Extension
- `Mbstring` PHP Extension
- `Tokenizer` PHP Extension
- `XML` PHP Extension
- `Ctype` PHP Extension
- `JSON` PHP Extension
- `cURL` PHP Extension

## Instructions

### Installation

- Create `mysql database` and login credentials.
- Run `git clone git@bitbucket.org:vrimeikis/learn-laravel.git`
- Go to project directory.
- Create `.env` file form `.env.example` file.
- Add your `database credentials` to `.env` file.
- Run `php artisan key:generate` command.
- Run `composer install` command.
- Run `php artisan migrate` command.
- Run `php artisan user:create` command.

### DEV

- If you have not virtual server, you can run `php artisan serve` command to create virtual serve.
