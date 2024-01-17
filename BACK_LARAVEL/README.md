# Laravel University Management System

This is a Laravel-based project for managing student information and grades within a university.


## Installation

```bash
# Clone the repository
git clone https://github.com/your-username/your-project.git

# Navigate to the project directory
cd BACK_LARAVEL

# Install PHP dependencies
composer install

# Copy the .env.example file to .env
cp .env.example .env

# Generate the application key
php artisan key:generate

# Add link to storage in public folder
php artisan storage:link

# Configure the database in the .env file
# ...

# Migrate and seed the database
php artisan migrate --seed


# Serve the application
php artisan serve
