# Laravel Advance Model
[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreazorzi/laravel-advance-model.svg?style=flat-square)](https://packagist.org/packages/andreazorzi/laravel-advance-model)
[![Total Downloads](https://img.shields.io/packagist/dt/andreazorzi/laravel-advance-model.svg?style=flat-square)](https://packagist.org/packages/andreazorzi/laravel-advance-model)

A Laravel package that generates complete CRUD resources with a single command. Creates models, controllers, views, routes, and more with Bootstrap 5 UI and htmx functionality for modern web applications.

## What This Package Does
This package extends Laravel's model generation by creating a complete set of files for CRUD operations:
- Model with factory
- Controller with advanced response methods
- Blade views with Bootstrap 5 styling
- Routes (both web and request)
- Modal components for UI interactions

All generated views use Bootstrap 5 for styling and htmx for seamless AJAX interactions.

## Requirements
### Frontend Dependencies
This package assumes you have the following assets available in your project:
- [Bootstrap 5](https://getbootstrap.com/) - for styling the generated tables and UI components
- [htmx](https://htmx.org/) - for handling AJAX requests
- [Toastify JS](https://www.npmjs.com/package/toastify-js) - for alert notifications
- [SweetAlert2](https://sweetalert2.github.io/) - for alert notifications

## Installation
1) ### Install the package:
    ```bash
    composer require andreazorzi/laravel-advance-model
    ```
2) ### Install frontend dependencies:
    ```bash
    npm install bootstrap@5 htmx.org toastify-js sweetalert2
    ```
    
## Setup
### Route Files Configuration
This package automatically adds routes to your route files. You need to add placeholder comments for this to work:
1) Create or modify `routes/request.php`:
```php
<?php

use Illuminate\Support\Facades\Route;
// End Controllers Imports

Route::prefix('admin')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::resource('users', UserController::class);
        // End Models Routes
    });
});
```

2) Modify `routes/web.php`:
```php
<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::view('users', 'backoffice.users')->name('backoffice.users');
        // End Models Routes
    });
});
```

Important: The package will automatically add new routes above these placeholder comments.

## Usage
### Basic Command
```bash
php artisan advance:create-model <ClassName> {--type=<creation_type>} {--force}
```

### Examples
```bash
# Create a complete User model with all files
php artisan advance:create-model User --type=complete

# Create only model and factory
php artisan advance:create-model Product --type=only-model

# Force overwrite existing files
php artisan advance:create-model Order --type=complete --force
```

### Creation Types
|       TYPE      | FILES GENERATED |
|-----------------|------------------------------------------------------------------------|
| only-model      |Model, Factory                                                          |
| with-controller |Model, Factory, Controller, Request classes                             |
| with-page       |Model, Factory, Controller, Request classes, Blade views, Filter, Routes|
| complete        |All of the above + Modal components                                     |

### Detailed File Generation Table
| CREATION TYPE   | MODEL | FACTORY | CONTROLLER | REQUESTS | VIEW | FILTER | WEB | MODAL |
|-----------------|-------|---------|------------|----------|------|--------|-----|-------|
| only-model      |   x   |    x    |            |          |      |        |     |       |
| with-controller |   x   |    x    |      x     |     x    |      |        |     |       |
| with-page       |   x   |    x    |      x     |     x    |   x  |    x   |  x  |       |
| complete        |   x   |    x    |      x     |     x    |   x  |    x   |  x  |   x   |

### Generated File Structure
When you run `php artisan advance:create-model User --type=complete`, the following files are created:
```
app/
├── Models/User.php
└── Http/
    └── Controllers/UserController.php

database/
└── factories/UserFactory.php

resources/
├── views/backoffice/users.blade.php
└── components/backoffice/modal/users.blade.php

routes/
├── web.php (modified)
└── request.php (modified)
```

### Advanced Controller Features
The generated controllers include several advanced response methods:

#### Alert Notifications
Display toast notifications using Toastify JS:
```php
// In your controller
public function store(StoreUserRequest $request)
{
    // Your logic here...
    
    return $this->alert([
        "status" => "success", // success, danger, info, warning
        "message" => "User created successfully!",
        "duration" => 3000, // milliseconds (-1 for no auto-close)
        "beforeshow" => 'console.log("Alert about to show");', // optional
        "callback" => 'refreshTable();', // optional: run after alert dismissed
    ]);
}
```

#### SweetAlert Modal
Display modal alerts using SweetAlert2:
```php
public function destroy(User $user)
{
    return $this->sweetAlert([
        "status" => "warning",
        "title" => "Delete User",
        "message" => "Are you sure you want to delete this user?",
        "confirm" => [
            "text" => "Yes, delete!",
            "color" => "#DC3545",
            "disable" => false
        ],
        "cancel" => [
            "text" => "Cancel",
            "color" => "#6C757D", 
            "disable" => true
        ],
        "beforeshow" => 'console.log("Modal opening");',
        "onsuccess" => 'deleteUser(' . $user->id . ');',
        "oncancel" => 'console.log("Cancelled");',
    ]);
}
```

#### Modal Components
Return Blade view modals for forms and content:
```php
public function edit(User $user)
{
    return $this->modal("users", ["user" => $user]);
}
```

This returns the view: `resources/views/components/backoffice/modal/users.blade.php`

## The MIT License (MIT)

Copyright © 2024 Andrea Zorzi <info@zorziandrea.com>

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the “Software”), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.