<?php

namespace App\Http\Requests\Backend\BrandDescription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandDescriptionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status'      => 'required|in:active,inactive',
        ];
    }

    /**
     * Summary of messages
     * @return array{description.max: array|string|null, description.required: array|string|null, description.string: array|string|null, logo.image: array|string|null, logo.max: array|string|null, logo.mimes: array|string|null, logo.required: array|string|null, status.in: array|string|null, status.required: array|string|null, title.max: array|string|null, title.required: array|string|null, title.string: array|string|null}
     */
    public function messages(): array
    {
        return [
            'logo.required' => __('Brand logo is required'),
            'logo.image' => __('Brand logo must be an image'),
            'logo.mimes' => __('Brand logo must be a file of type: jpg, jpeg, png, webp'),
            'logo.max' => __('Brand logo must be less than 2MB'),

            'title.required' => __('Title is required'),
            'title.string' => __('Title must be a string'),
            'title.max' => __('Title must be less than 255 characters'),

            'description.required' => __('Description is required'),
            'description.string' => __('Description must be a string'),
            'description.max' => __('Description must be less than 1000 characters'),

            'status.required' => __('Status is required'),
            'status.in' => __('Status must be either active or inactive'),
        ];
    }
}
