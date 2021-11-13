### Install laravel/ui

```
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
```

### Update to bootstrap 5

[laravel 8 bootstrap 5](https://stackoverflow.com/questions/69383890/how-to-add-bootstrap-jquery-and-popper-js-to-laravel-8-project-using-laravel-m)

```
npm install bootstrap@latest @popperjs/core --save-dev

window.bootstrap = require('bootstrap');
```

### Customize Laravel Registration Form With Additional Fields

```
create database php_recipe_template default charset=utf8mb4;
php artisan make:migration add_first_name_and_last_name_field_to_users_table --table=users
```

### Create Role and Permission model

```


php artisan make:model "Backend/Role" -a
php artisan make:model "Backend/Permission" -a
php artisan make:model "Backend/RoleUser" -a
php artisan make:model "Backend/PermissionRole" -a
php artisan make:controller "Backend/UserController" -m User -r
php artisan make:controller 'Backend/RecipeController' -m Recipe
```

### Make request form validation

```
php artisan make:request "Backend/PermissionStoreRequest"
php artisan make:request "Backend/RoleStoreRequest"
```

https://dev.to/dendihandian/laravel-many-to-many-pivot-relationship-a67
https://laravel-news.com/laravel-validation-101-controllers-form-requests-and-rules
https://laraveldaily.com/pivot-tables-and-many-to-many-relationships/

### Assign role and permission

```
php artisan make:controller "Backend/AdminController"
php artisan make:request "Backend/UserAssignRoleRequest"
php artisan make:request "Backend/RoleAssignPermissionRequest"
```

### Create a hasUserRole middleware

```
php artisan make:middleware UserHasRole
```

### Register a middleware

```
app/Http/Kernel.php
'has.role' => \App\Http\Middleware\UserHasRole::class,
```

### Create a recipe model

```
php artisan make:model Recipe -a
composer dump-autoload
```

### Make a route admin server provider

```
php artisan make:provider AdminRouteServerProvider
```

### Create a recipe store request

```
php artisan make:request 'Frontend/RecipeStoreRequest'
```

### Make a link

```
config/filesystems.php
public_path('avatars') => storage_path('app/avatars'),

php artisan storage:link
```

### Make a user controller

```
php artisan make:controller 'Frontend/UserController' -m User
php artisan make:request "Frontend/UpdateProfileRequest"
php artisan make:request "Frontend/ChangeUserPasswordRequest"
php artisan make:model Comment -a
php artisan make:model Favourite -a
```

### Use route macro

```
php artisan make:provider RecipeRouteServiceProvider

class RecipeRouteServiceProvider{
    public function boot(){
        Route::mixin(new RecipeRouteMethods);
    }
}


register service provider in config/app.php

'providers' => [
    App\Providers\AppServiceProvider::class,
    App\Providers\AdminRouteServiceProvider::class,
    App\Providers\RecipeRouteServiceProvider::class,
]

app/Routes/RecipeRouteMethods.php

class RecipeRouteMethods{
    public function recipes(){
        return function(){
            $this->group(['prefix'=>'recipes', 'middleware'=> [], 'namespace' => 'App\Http\Controllers'], function(){
                $this->get('/', 'RecipeController@index')->name('recipes');
            });
        };
    }
}

web.php
// Load route mixin routes
Route::recipes();
```

https://aregsar.com/blog/2020/laravel-7-auth-route-registration-under-the-hood/
