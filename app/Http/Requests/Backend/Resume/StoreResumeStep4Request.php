<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeStep4Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (Normalize Input)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'experiences' => array_values($this->experiences ?? [])
        ]);
    }

    public function rules(): array
    {
        return [
            'experiences' => 'required|array|min:1',

            'experiences.*.designation' => 'required|string|max:255',
            'experiences.*.company'     => 'required|string|max:255',
            'experiences.*.location'    => 'nullable|string|max:255',

            'experiences.*.start_date'  => 'required|date',
            'experiences.*.end_date'    => 'nullable|date|after_or_equal:experiences.*.start_date',

            'experiences.*.is_current'  => 'required|boolean',

            /*
            |--------------------------------------------------------------------------
            | NESTED DETAILS
            |--------------------------------------------------------------------------
            */
            'experiences.*.details' => 'required|array|min:1',

            'experiences.*.details.*.description' => 'required|string|max:1000',
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

            'experiences.*.designation.required' => __('Designation is required'),
            'experiences.*.designation.string'   => __('Designation must be a valid string'),
            'experiences.*.designation.max'      => __('Designation must not exceed 255 characters'),

            'experiences.*.company.required' => __('Company name is required'),
            'experiences.*.company.string'   => __('Company name must be a valid string'),
            'experiences.*.company.max'      => __('Company name must not exceed 255 characters'),

            'experiences.*.location.string' => __('Location must be a valid string'),
            'experiences.*.location.max'    => __('Location must not exceed 255 characters'),

            'experiences.*.start_date.required' => __('Start date is required'),
            'experiences.*.start_date.date'     => __('Start date must be a valid date'),

            'experiences.*.end_date.date' => __('End date must be a valid date'),
            'experiences.*.end_date.after_or_equal' => __('End date must be after or equal to start date'),

            'experiences.*.is_current.required' => __('Current job status is required'),
            'experiences.*.is_current.boolean'  => __('Current job must be true or false'),

            'experiences.*.details.required' => __('Experience details are required'),
            'experiences.*.details.array'    => __('Experience details must be a valid array'),
            'experiences.*.details.min'      => __('At least one detail is required'),

            'experiences.*.details.*.description.required' => __('Description is required'),
            'experiences.*.details.*.description.string'   => __('Description must be a valid string'),
            'experiences.*.details.*.description.max'      => __('Description must not exceed 1000 characters'),
        ];
    }
}