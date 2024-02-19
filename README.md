# Laravel Discrete API Base

Laravel API for discrete Frontend. Base Version (needed for all futher verstions).<br>
Supports Sanctum token authentication.<br>
Inscludes: register, authenticate, logout, reset password, email verification and profile/avatar support.<br>

## Requirements

- none

## Installation

`composer require "makeit-app/laravel-discrete-api-base"`

## Setup

- Add records to the `.env`-file
```ini
APP_FRONTEND_DOMAIN=publicdomain.com
APP_FRONTEND_URL="https://${APP_FRONTEND_DOMAIN}"
APP_FRONTEND_LOGIN_URL="${APP_FRONTEND_URL}/login"
APP_FRONTEND_DASHBOARD_URL="${APP_FRONTEND_URL}/dashboard"
APP_URL="https://backend.${APP_FRONTEND_DOMAIN}"

```

- `php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="install" --force` - overwrite User model
- `php artisan vendor:publish --provider="MakeIT\\DiscreteApi\\Base\\Providers\\DiscreteApiBaseServiceProvider" --tag="nova" --force` - full version of `App\Models\User.php`, meaning the installed `make-it-app/laravel-user-roles` package and this installable package.<br>

**THEN**

`php artisan makeit:discreteapi:base:install` - installer for modificable descendant classes of the package. Follow installer instructions.<br>

Then see `app/**/DiscreteApi/Base/*` filesystem structure.

## Routes

- POST   `api/register`             - register user (first user = super-administrator
- POST   `api/login`                - login
- POST   `api/logout`               - logout
- PUT    `api/password/forgot`      - request password reset link
- PUT    `api/password/reset`       - reset password
- POST   `api/email/verification-notification` - request verification link
- GET    `api/email/verify/<UUID>/<TOKEN>?expires=<UNIX_TIMESTAMP>&signature=<HASH>` - onetime verification link
- GET    `api/user`                 - user data
- DELETE `api/user`                 - delete myself
- DELETE `api/user/force/<UUID>`    - delete specified user (super only)
- PUT    `api/user/profile`         - update profile
- GET    `api/user/profile/avatar`  - get avatar image
- POST   `api/user/profile/avatar`  - update avatar image
- DELETE `api/user/profile/avatar`  - remove avatar image
