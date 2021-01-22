<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $user = $this->route('users');

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email','string',
                Rule::unique('users')->ignore($user ? $user->id : 0)
            ],
        ];
    }
}
