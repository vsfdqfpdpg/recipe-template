<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ChangeUserPasswordRequest;
use App\Http\Requests\Frontend\UpdateProfileRequest;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route("home");
        }
        return view("frontend.user.profile", ['user' => $user]);
    }

    public function user(Request $request, User $user)
    {
        return view("frontend.user.profile", ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        if ($user != null) {
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $path = $request->file("avatar")->store("avatars");
                $user->avatar = $path;
            }
            $user->first_name = $validated["first_name"];
            $user->last_name = $validated["last_name"];
            $user->save();
        }
        return redirect()->route("profile");
    }

    /**
     * Change user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(ChangeUserPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        if ($user != null) {
            $user->password = Hash::make($validated["password"]);
            $user->save();
        }

        \Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route("login");
    }

    public function recipes(Request $request)
    {
        $user = auth()->user();
        $recipes = Recipe::with(["user", "favourites", "comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.favourites", "comments.user", "comments.comments.favourites", "comments.comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.comments.user", "favourites"])->where("user_id", $user->id)->orderByDesc('id')->paginate(20);
        return view('frontend.user.recipes', ['recipes' => $recipes, 'user' => $user]);
    }

    public function userRecipes(Request $request, User $user)
    {
        $relation = Recipe::with(["user", "favourites", "comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.favourites", "comments.user", "comments.comments.favourites", "comments.comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.comments.user", "favourites"]);
        $authId = auth()->id();

        if ($authId != $user->id) {
            $recipes = $relation->where("user_id", $user->id)->where("status", "!=", "REJECTED")->orderByDesc('id')->paginate(20);
        } else {
            $recipes = $relation->where("user_id", $user->id)->paginate(20);
        }
        return view('frontend.user.recipes', ['recipes' => $recipes, 'user' => $user]);
    }
}
