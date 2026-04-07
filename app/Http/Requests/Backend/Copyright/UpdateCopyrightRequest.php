<?php

namespace App\Http\Requests\Backend\Copyright;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCopyrightRequest extends FormRequest
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
            'copyright_text' => 'required|string|max:255',
            'status'         => 'required|in:active,inactive',
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
            'copyright_text.required' => __('Copyright text is required.'),
            'copyright_text.max'     => __('Copyright text must not be greater than 255 characters.'),
            'copyright_text.string'  => __('Copyright text must be a string.'),

            'status.required'         => __('Status is required.'),
            'status.in'               => __('Status must be either active or inactive.'),
        ];
    }
}
