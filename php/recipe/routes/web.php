<?php

use App\Http\Controllers\Frontend\FavouriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\RecipeController;
use App\Http\Controllers\Frontend\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [RecipeController::class, 'index'])->name('home');

Route::auth(["verify" => true]);

Route::admin();

Route::prefix('/user')->group(function () {
    Route::get("/", [UserController::class, "index"])->name('profile');
    Route::get("/recipes", [UserController::class, "recipes"])->name('profile.recipes');
    Route::post("/update", [UserController::class, "update"])->name('profile.update');
    Route::post("/change", [UserController::class, "change"])->name('profile.change');
    Route::get("/{user}", [UserController::class, "user"])->name("profile.user");
    Route::get("/{user}/recipes", [UserController::class, "userRecipes"])->name("profile.user.recipes");
});

Route::recipes();

Route::prefix('/favourite')->group(function () {
    Route::post("/", [FavouriteController::class, "store"])->name("favourite");
    Route::delete("/", [FavouriteController::class, "destroy"])->name("favourite.destroy");
});
