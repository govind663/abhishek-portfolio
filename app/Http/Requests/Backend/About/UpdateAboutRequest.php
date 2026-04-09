<?php

namespace App\Http\Requests\Backend\About;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',

            'profile_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'experience' => 'required|string|max:100',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',

            'role' => 'required|string|max:255',
            'database' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'freelance' => 'required|string|max:100',

            'extra_description' => 'required|string',

            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'name.max' => __('Name must not be greater than 255 characters.'),
            'name.string' => __('Name must be a string.'),

            'subtitle.required' => __('Subtitle is required.'),
            'subtitle.max' => __('Subtitle must not be greater than 255 characters.'),
            'subtitle.string' => __('Subtitle must be a string.'),

            'description.required' => __('Description is required.'),
            'description.string' => __('Description must be a string.'),

            'profile_image.required' => __('Profile image is required.'),
            'profile_image.image' => __('Profile image must be an image file.'),
            'profile_image.mimes' => __('Profile image must be a file of type: jpg, jpeg, png, webp.'),
            'profile_image.max' => __('Profile image must not be greater than 2MB.'),

            'experience.required' => __('Experience is required.'),
            'experience.max' => __('Experience must not be greater than 100 characters.'),
            'experience.string' => __('Experience must be a string.'),

            'specialization.required' => __('Specialization is required.'),
            'specialization.max' => __('Specialization must not be greater than 255 characters.'),
            'specialization.string' => __('Specialization must be a string.'),

            'phone.required' => __('Phone number is required.'),
            'phone.max' => __('Phone number must not be greater than 20 characters.'),
            'phone.string' => __('Phone number must be a string.'),

            'location.required' => __('Location is required.'),
            'location.max' => __('Location must not be greater than 255 characters.'),
            'location.string' => __('Location must be a string.'),

            'role.required' => __('Role is required.'),
            'role.max' => __('Role must not be greater than 255 characters.'),
            'role.string' => __('Role must be a string.'),

            'database.required' => __('Database information is required.'),
            'database.max' => __('Database information must not be greater than 255 characters.'),
            'database.string' => __('Database information must be a string.'),

            'email.required' => __('Email is required.'),
            'email.email' => __('Email must be a valid email address.'),
            'email.max' => __('Email must not be greater than 255 characters.'),

            'freelance.required' => __('Freelance status is required.'),
            'freelance.max' => __('Freelance status must not be greater than 100 characters.'),
            'freelance.string' => __('Freelance status must be a string.'),

            'extra_description.required' => __('Extra description is required.'),
            'extra_description.string' => __('Extra description must be a string.'),

            'status.required' => __('Status is required.'),
            'status.in' => __('Status must be active or inactive.'),
        ];
    }
}
