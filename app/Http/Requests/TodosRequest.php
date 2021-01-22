<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'is_done' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'is_done.boolean' => 'The is done field must be true = 1 or false = 0.'
        ];
    }
}
