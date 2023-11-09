<?php

namespace Wave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
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
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'avatar.base64image' => 'The avatar must be a valid image.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . Auth::user()->id,
            'username' => 'sometimes|required|unique:users,username,' . Auth::user()->id,
            'avatar' => 'nullable|base64image'
        ];
    }
}
