<?php

namespace App\Http\Requests\Backend\Feature;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'color' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => __('The title field is required.'),
            'title.string' => __('The title must be a string.'),
            'title.max' => __('The title may not be greater than 255 characters.'),

            'icon.required' => __('The icon field is required.'),
            'icon.string' => __('The icon must be a string.'),
            'icon.max' => __('The icon may not be greater than 100 characters.'),

            'color.required' => __('The color field is required.'),
            'color.string' => __('The color must be a string.'),
            'color.max' => __('The color may not be greater than 50 characters.'),

            'status.required' => __('The status field is required.'),
            'status.in' => __('The status must be either active or inactive.'),
        ];
    }
}
