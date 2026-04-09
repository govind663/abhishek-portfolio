<?php

namespace App\Http\Requests\Backend\Skill;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillRequest extends FormRequest
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
            'percentage' => 'required|integer|min:0|max:100',
            'group' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
    * Custom messages for validation errors.
    *
    * @return array<string, string>
    */
    public function messages(): array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name field must be a string.'),
            'name.max' => __('The name field may not be greater than 255 characters.'),

            'percentage.required' => __('The percentage field is required.'),
            'percentage.integer' => __('The percentage field must be an integer.'),
            'percentage.min' => __('The percentage field must be at least 0.'),
            'percentage.max' => __('The percentage field may not be greater than 100.'),
            
            'group.required' => __('The group field is required.'),
            'group.string' => __('The group field must be a string.'),
            'group.max' => __('The group field may not be greater than 255 characters.'),

            'status.required' => __('The status field is required.'),
            'status.in' => __('The status field must be either active or inactive.'),
        ];
    }
}
