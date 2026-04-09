<?php

namespace App\Http\Requests\Backend\Stat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatRequest extends FormRequest
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
            'count' => 'required|integer|min:0',
            'icon' => 'required|string|max:100',
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
            'title.required' => __('The title field is required.'),
            'title.max' => __('The title field must not exceed 255 characters.'),
            'title.string' => __('The title field must be a string.'),

            'count.required' => __('The count field is required.'),
            'count.integer' => __('The count field must be an integer.'),
            'count.min' => __('The count field must be at least 0.'),

            'icon.required' => __('The icon field is required.'),
            'icon.max' => __('The icon field must not exceed 100 characters.'),
            'icon.string' => __('The icon field must be a string.'),

            'status.required' => __('The status field is required.'),
            'status.in' => __('The status field must be either active or inactive.'),
        ];
    }
}
