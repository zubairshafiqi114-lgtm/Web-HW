============================================================
TASK 1 - PREPARE PHP AND COMPOSER
============================================================

# Check PHP version
php -v

# Check Composer version
composer --version


============================================================
TASK 2 - INSTALL LARAVEL AND CREATE A PROJECT
============================================================

# Go to your user folder
cd /d "%USERPROFILE%"

# Create a new Laravel 12 project
composer create-project laravel/laravel laravel-lab "12.*"

# Enter the project folder
cd laravel-lab

# Check Laravel version
php artisan --version


============================================================
TASK 3 - CREATE THE HOME BLADE VIEW
============================================================

# File:
# resources/views/home.blade.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My First Laravel Page</title>
</head>
<body>

    <h1>Welcome to My Laravel Website</h1>

    <p>Student: Zubair Shafiqi</p>

    <p>Course: {{ $course }}</p>

    <p>This is my first Blade view.</p>

    <a href="{{ url('/about') }}">About Me</a>

</body>
</html>


============================================================
TASK 4 - CREATE THE ROUTES
============================================================

# File:
# routes/web.php

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'course' => 'Web Information Systems'
    ]);
});

Route::get('/about', function () {
    return view('about');
});


============================================================
TASK 4 - CONFIGURE .ENV
============================================================

# File:
# .env

SESSION_DRIVER=file
CACHE_STORE=file


============================================================
TASK 4 - CLEAR CONFIGURATION
============================================================

php artisan config:clear


============================================================
TASK 5 - CREATE THE ABOUT BLADE VIEW
============================================================

# File:
# resources/views/about.blade.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Me</title>
</head>
<body>

    <h1>About Me</h1>

    <p>My name is Zubair Shafiqi.</p>

    <p>Student ID: 28</p>

    <p>I want to learn how to build web applications using Laravel.</p>

    <p>I also want to improve my programming skills and understand how web applications work.</p>

    <a href="{{ url('/') }}">Back to Home</a>

</body>
</html>


============================================================
RUN THE LARAVEL PROJECT
============================================================

# Run this command inside the laravel-lab folder

php artisan serve


============================================================
OPEN THE WEBSITE
============================================================

# Home page
http://127.0.0.1:8000

# About page
http://127.0.0.1:8000/about
