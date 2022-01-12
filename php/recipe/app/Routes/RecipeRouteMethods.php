<?php

namespace App\Routes;

class RecipeRouteMethods
{
    public function recipes()
    {
        return function () {
            $this->group(['prefix' => '/recipe', 'namespace' => 'App\Http\Controllers\Frontend'], function () {
                $this->get('/', 'RecipeController@index')->name('recipe');
                $this->get("/create", 'RecipeController@create')->name("recipe.create");
                $this->get('/{recipe}', 'RecipeController@show')->name("recipe.show");
                $this->post('/{recipe}/publish', 'RecipeController@publish')->name("recipe.publish");
                $this->delete('/{recipe}/publish', 'RecipeController@unpublish')->name("recipe.unpublish");
                $this->post('/{recipe}/reject', 'RecipeController@reject')->name("recipe.reject");
                $this->delete('/{recipe}/reject', 'RecipeController@restore')->name("recipe.restore");
                $this->put("/{recipe}/update", 'RecipeController@update')->name("recipe.update");
                $this->get("/{recipe}/edit", 'RecipeController@edit')->name("recipe.edit");
                $this->get("/{recipe}/delete", 'RecipeController@delete')->name("recipe.delete");
                $this->delete("/{recipe}", 'RecipeController@destroy')->name("recipe.destroy");
                $this->post("/", 'RecipeController@store')->name("recipe.store");
                $this->post('/{recipe}/comment', 'RecipeController@comment')->name("recipe.comment");
                $this->delete('/{comment}/comment', 'RecipeController@deleteComment')->name("recipe.comment.delete");
                $this->post('/{comment}/sub-comment', 'RecipeController@subComment')->name("recipe.sub-comment");
            });
        };
    }
}
