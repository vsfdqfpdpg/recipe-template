<?php

namespace App\Routes;


class AdminRouteMethods
{
    public function admin()
    {
        return function () {
            $this->group(['namespace' => 'App\Http\Controllers\Backend', 'middleware' => ["auth", "has.role:Admin"], 'prefix' => "/admin"], function () {
                $this->get("/", "AdminController@index")->name("admin.home");
                $this->users();
                $this->roles();
                $this->permissions();
                $this->recipes();
            });
        };
    }


    public function users()
    {
        return function () {
            $this->get("/users/{user}/role", "UserController@assign")->name("admin.users.role");
            $this->post("/users/{user}/role", "UserController@storeAssign")->name("admin.users.assignRole");
            $this->get("/users", "UserController@index")->name("admin.users");
        };
    }

    public function roles()
    {
        return function () {
            $this->get("/roles/create", "RoleController@create")->name("admin.roles.create");
            $this->post("/roles/store", "RoleController@store")->name("admin.roles.store");
            $this->get("/roles/{role}/assign", "RoleController@assign")->name("admin.roles.assign");
            $this->post("/roles/{role}/store-assign", "RoleController@storeAssign")->name("admin.roles.assignPermission");
            $this->get("/roles/{role}/edit", "RoleController@edit")->name("admin.roles.edit");
            $this->post("/roles/{role}/update", "RoleController@update")->name("admin.roles.update");
            $this->get("/roles/{role}/delete", "RoleController@delete")->name("admin.roles.delete");
            $this->post("/roles/{role}/destroy", "RoleController@destroy")->name("admin.roles.destroy");
            $this->get("/roles/{role}", "RoleController@show")->name("admin.roles.show");
            $this->get("/roles", "RoleController@index")->name("admin.roles");
        };
    }

    public function permissions()
    {
        return function () {
            $this->get("/permissions/create", "PermissionController@create")->name("admin.permissions.create");
            $this->post("/permissions/store", "PermissionController@store")->name("admin.permissions.store");
            $this->get("/permissions/{permission}/edit", "PermissionController@edit")->name("admin.permissions.edit");
            $this->post("/permissions/{permission}/update", "PermissionController@update")->name("admin.permissions.update");
            $this->get("/permissions/{permission}/delete", "PermissionController@delete")->name("admin.permissions.delete");
            $this->post("/permissions/{permission}/destroy", "PermissionController@destroy")->name("admin.permissions.destroy");
            $this->get("/permissions", "PermissionController@index")->name("admin.permissions");
        };
    }

    public function recipes()
    {
        return function () {
            $this->get("/recipes", "RecipeController@index")->name('admin.recipes');
        };
    }
}
