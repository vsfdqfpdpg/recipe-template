<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecipeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'preserve' => 'required|in:true,false',
            'cooking_style' => ['required', Rule::in(array_keys(\COOKING_STYLE))],
            'category' => ['required', "array", Rule::in(array_keys(\CATEGORY))],
            'image' => 'required|image',
            'description' => 'required',
            'duration' => "required|numeric"
        ];
    }
}
