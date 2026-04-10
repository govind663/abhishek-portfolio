<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeStep4Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'experiences' => 'required|array|min:1',

            // optional experience ID for update
            'experiences.*.id' => 'nullable|integer',

            'experiences.*.designation' => 'required|string|max:255',
            'experiences.*.company'     => 'required|string|max:255',
            'experiences.*.location'    => 'nullable|string|max:255',

            'experiences.*.start_date'  => 'required|date',
            'experiences.*.end_date'    => 'nullable|date',

            'experiences.*.is_current'  => 'required|boolean',

            /*
            |--------------------------------------------------------------------------
            | EXPERIENCE DETAILS (NESTED)
            |--------------------------------------------------------------------------
            */
            'experiences.*.details' => 'required|array|min:1',

            // optional detail ID (for update)
            'experiences.*.details.*.id' => 'nullable|integer',

            'experiences.*.details.*.description' => 'required|string',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM MESSAGES
    |--------------------------------------------------------------------------
    */
    public function messages(): array
    {
        return [
            'experiences.required' => __('Experience details are required'),
            'experiences.array'    => __('Experience must be a valid array'),
            'experiences.min'      => __('At least one experience is required'),

            'experiences.*.id.integer' => __('Invalid experience ID'),

            'experiences.*.designation.required' => __('Designation is required'),
            'experiences.*.designation.string'   => __('Designation must be a valid string'),
            'experiences.*.designation.max'      => __('Designation must not exceed 255 characters'),

            'experiences.*.company.required' => __('Company name is required'),
            'experiences.*.company.string'   => __('Company must be a valid string'),
            'experiences.*.company.max'      => __('Company must not exceed 255 characters'),

            'experiences.*.location.string' => __('Location must be a valid string'),
            'experiences.*.location.max'    => __('Location must not exceed 255 characters'),

            'experiences.*.start_date.required' => __('Start date is required'),
            'experiences.*.start_date.date'     => __('Start date must be a valid date'),

            'experiences.*.end_date.date' => __('End date must be a valid date'),

            'experiences.*.is_current.required' => __('Current job status is required'),
            'experiences.*.is_current.boolean'  => __('Current job must be true or false'),

            'experiences.*.details.required' => __('Experience details are required'),
            'experiences.*.details.array'    => __('Experience details must be a valid array'),
            'experiences.*.details.min'      => __('At least one experience detail is required'),

            'experiences.*.details.*.id.integer' => __('Invalid detail ID'),

            'experiences.*.details.*.description.required' => __('Description is required'),
            'experiences.*.details.*.description.string'   => __('Description must be a valid string'),
        ];
    }
}