<?php

namespace App\Http\Requests\Backend\PageTitle;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePageTitleRequest extends FormRequest
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
            'page_name' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
    * Get custom error messages for validation failures.
    * @return array<string, string>
    */  
    public function messages(): array
    {
        return [
            'page_name.required' => __('The page name field is required.'),
            'page_name.string' => __('The page name must be a string.'),
            'page_name.max' => __('The page name may not be greater than 100 characters.'),

            'title.required' => __('The title field is required.'),
            'title.string' => __('The title must be a string.'),
            'title.max' => __('The title may not be greater than 255 characters.'),

            'description.required' => __('The description field is required.'),
            'description.string' => __('The description must be a string.'),

            'status.required' => __('The status field is required.'),
            'status.in' => __('The status must be either active or inactive.'),
        ];
    }
}
