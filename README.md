### Prerequisites

- PHP >=8.2
- Composer

### Installation

To make the project work correctly follow this steps:

1. Download the release zip archive
2. Extract it in any folder
3. Run `composer install` to download all the dependencies
4. Create a `.env` file in project root
5. Copy and paste all the content from `.env.example` in that file
6. Generate an application key with `php artisan key:generate`
7. Create a `database.sqlite` file in database folder
8. Run migrations with `php artisan migrate:fresh` to make DB work properly
9. Start local server with `php artisan serve` (if this does not work you can try `php -S localhost:8000 -t public/`)
10. Visit http://localhost:8000
