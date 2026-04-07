<?php

namespace App\Http\Requests\Backend\Hero;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',

            'profile_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'background_image' => 'required|mimes:mp4|max:4096',
            'resume_file' => 'required|mimes:pdf|max:5120',

            'typed_items' => 'required|string',

            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Name is required'),
            'name.string' => __('Name must be a string'),
            'name.max' => __('Name must be less than 255 characters'),

            'subtitle.required' => __('Subtitle is required'),
            'subtitle.max' => __('Subtitle must be less than 255 characters'),
            'subtitle.string' => __('Subtitle must be a string'),

            'description.required' => __('Description is required'),
            'description.string' => __('Description must be a string'),

            'profile_image.required' => __('Profile image is required'),
            'profile_image.image' => __('Profile image must be an image file'),
            'profile_image.mimes' => __('Profile image must be a file of type: jpg, jpeg, png, webp'),
            'profile_image.max' => __('Profile image must be less than 2MB'),

            'background_image.required' => __('Background image is required'),
            'background_image.mimes' => __('Background image must be a file of type: mp4'),
            'background_image.max' => __('Background image must be less than 4MB'),

            'resume_file.required' => __('Resume file is required'),
            'resume_file.mimes' => __('Resume file must be a PDF file'),
            'resume_file.max' => __('Resume file must be less than 5MB'),

            'typed_items.required' => __('Typed items is required'),
            'typed_items.string' => __('Typed items must be a string'),

            'status.required' => __('Status is required'),
            'status.in' => __('Status must be either active or inactive'),
        ];
    }
}