<?php

namespace App\Http\Requests\Backend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rule = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],

            'password_confirmation' => [
                'required',
            ],
        ];
    /**
     * Return the validation error messages.
     *
     * @return array<string, string>
     */
        return $rule;
    }

    public function messages()
    {
        return [

            'name.required' => __('Username is required'),
            'name.string' => __('Name should be a string'),
            'name.max' => __('Name should not exceed 255 characters'),

            'email.required' => __('Email Id is required'),
            'email.email' => __('Please enter a valid Email address'),
            'email.unique' => __('This email is already registered'),

            'password.required' => __('Password is required'),
            'password.confirmed' => __('Password and Confirm Password do not match'),

            'password_confirmation.required' => __('Confirm Password is required'),

        ];
    }
}