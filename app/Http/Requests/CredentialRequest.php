<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class CredentialRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userTable = 'users';

        return [
            'email' => 'required|string|email|'.
                'unique:' . $userTable . '|'.
                'exists:' . $userTable,

            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email required',
            'email.exists'      => 'Email is invalid',
            'password.required' => 'Password required',
        ];
    }


}
