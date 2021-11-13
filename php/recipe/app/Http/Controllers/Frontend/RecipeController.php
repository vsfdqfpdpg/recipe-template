<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RecipeStoreRequest;
use App\Http\Requests\Frontend\UpdateRecipeRequest;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipes = Recipe::with(["user", "favourites", "comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.favourites", "comments.user", "comments.comments.favourites", "comments.comments" => function ($query) {
            $query->orderBy("created_at", "desc");
        }, "comments.comments.user", "favourites"])->where("status", "!=", "REJECTED")->orderByDesc('id')->paginate(20);
        $user = auth()->user();
        return view('frontend.recipe.list', ['recipes' => $recipes, 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("frontend.recipe.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeStoreRequest $request)
    {
        $validated = $request->validated();
        $path = $request->file('image')->store('avatars');

        $recipe = Recipe::create([
            "name" => $validated["name"],
            "preserve" => $validated["preserve"] == "true",
            "cooking_style" => $validated["cooking_style"],
            "category" => implode(",", $validated["category"]),
            "image" => $path,
            "description" => $validated["description"],
            "duration" => $validated["duration"],
            "status" => "PENDING",
            "user_id" => auth()->id()
        ]);

        return redirect()->route("recipe");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        $recipe->load(
            ["user", "favourites", "comments" => function ($query) {
                $query->orderBy("created_at", "desc");
            }, "comments.favourites", "comments.user", "comments.comments.favourites", "comments.comments" => function ($query) {
                $query->orderBy("created_at", "desc");
            }, "comments.comments.user", "favourites"]
        );
        return view("frontend.recipe.view", ['recipe' => $recipe]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        return view("frontend.recipe.edit", ['recipe' => $recipe]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $validated = $request->validated();

        $recipe->name = $validated["name"];
        $recipe->preserve = $validated["preserve"] == "true";
        $recipe->cooking_style = $validated["cooking_style"];
        $recipe->category = implode(",", $validated["category"]);
        $recipe->description = $validated["description"];
        $recipe->duration = $validated["duration"];

        if ($request->hasFile("image") && $request->file("image")->isValid()) {
            $path = $request->file('image')->store('avatars');
            $recipe->image = $path;
        }

        $recipe->save();
        return redirect()->route("recipe");
    }

    public function delete(Request $request, Recipe $recipe)
    {
        return view("frontend.recipe.delete", ["recipe" => $recipe]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route("recipe");
    }

    public function comment(Request $request, Recipe $recipe)
    {
        $validator = Validator::make($request->all(), ["comment" => "required"]);
        $validatated = $validator->validated();

        $comment = $recipe->comments()->create([
            "user_id" => auth()->id(),
            "comment" => $validatated["comment"]
        ]);

        return ["user" => auth()->user(), "comment" => $comment];
    }

    public function deleteComment(Request $request, Comment $comment)
    {
        $validator = Validator::make(['id' => $comment->user_id], ["id" => function ($attribute, $value, $fail) {
            if ($value !== auth()->id()) {
                $fail('You do not have permission to delete this comment.');
            }
        },]);

        $validator->validated();
        // $comment->favourites()->delete();
        // $comment->comments()->delete();
        return $comment->delete();
    }

    public function subComment(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), ["comment" => "required"]);
        $validatated = $validator->validated();

        $comment = $comment->comments()->create([
            "user_id" => auth()->id(),
            "comment" => $validatated["comment"]
        ]);

        return ["user" => auth()->user(), "comment" => $comment];
    }

    public function publish(Request $request, Recipe $recipe)
    {
        $recipe->status =  "PASS";
        $recipe->save();
        return $recipe;
    }

    public function unpublish(Request $request, Recipe $recipe)
    {
        $recipe->status =  "PENDING";
        $recipe->save();
        return $recipe;
    }

    public function reject(Request $request, Recipe $recipe)
    {
        $recipe->status =  "REJECTED";
        $recipe->save();
        return $recipe;
    }

    public function restore(Request $request, Recipe $recipe)
    {
        $recipe->status =  "PENDING";
        $recipe->save();
        return $recipe;
    }
}
