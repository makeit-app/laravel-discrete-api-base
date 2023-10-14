# Laravel Discrete API Base

Laravel API for discrete Frontend. Base Version (needed for all futher verstions).<br>
Supports Sanctum token authentication.<br>
Inscludes: register, authenticate, logout, reset password, email verification and profile/avatar support.<br>

## Requirements

`composer require make-it-app/laravel-user-roles` - User's Role Subsystem.<br>
Just visit the https://github.com/makeit-app/laravel-user-roles and follow installation instructions.<br>
This package must be installed and configured manually AS ROOT PACKAGE before this one for the Laravel project, with all due care.

## Installation

`composer require "make-it-app/laravel-discrete-api-base":"*"`

## Setup

`php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="config"` - if you plan modify config
`php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="migrations"` - if you plan to modify migrations
`php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="lang"` - if you plan modyfy localization files
