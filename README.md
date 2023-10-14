# Laravel Discrete API Base

Laravel API for discrete Frontend. Base Version (needed for all futher verstions).<br>
Supports Sanctum token authentication.<br>
Inscludes: register, authenticate, logout, reset password, email verification and profile/avatar support.<br>

## Requirements

`composer require make-it-app/laravel-user-roles` - User's Role Subsystem.<br>
Just visit the https://github.com/makeit-app/laravel-user-roles and follow installation instructions.<br>
This package must be installed and configured manually AS ROOT PACKAGE before this one for the Laravel project, with all due care.

## Installation

`composer require "make-it-app/laravel-discrete-api-base"`

## Setup

- `php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="migrations"` - if you plan to modify migrations<br>
<br>
- `php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="lang"` - if you plan modyfy localization files<br>
<br>
- `php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="user-model" --force` - full version of `App\Models\User.php`,
...meaning the installed `make-it-app/laravel-user-roles` package and this installable package.<br>
<br>
`php artisan makeit:discreteapi:base:install` - installer for modificable descendant classes of the package. Follow installer instructions!<br>

Then see `app/**/DiscreteApi/Base/*` filesystem structure.
