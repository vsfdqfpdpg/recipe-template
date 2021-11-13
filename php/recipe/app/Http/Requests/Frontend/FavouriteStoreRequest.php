<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class FavouriteStoreRequest extends FormRequest
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
            'id' => 'required',
            'type' => ['required', function ($attribute, $value, $fail) {
                if (!class_exists("App\Models\\" . ucfirst($value))) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }]
        ];
    }
}
