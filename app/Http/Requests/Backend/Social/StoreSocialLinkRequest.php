<?php

namespace App\Http\Requests\Backend\Social;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => 'required|string|max:100',
            'icon' => 'required|string|max:100',
            'url' => 'required|url',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required' => __('Platform is required'),
            'platform.string' => __('Platform must be a string'),
            'platform.max' => __('Platform must be less than 100 characters'),

            'icon.required' => __('Icon is required'),
            'icon.string' => __('Icon must be a string'),
            'icon.max' => __('Icon must be less than 100 characters'),

            'url.required' => __('URL is required'),
            'url.url' => __('URL must be valid'),

            'status.required' => __('Status is required'),
            'status.in' => __('Status must be either active or inactive'),
        ];
    }
}