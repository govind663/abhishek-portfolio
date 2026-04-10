<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeStep2Request extends FormRequest
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
            'educations' => array_values($this->educations ?? [])
        ]);
    }

    public function rules(): array
    {
        return [
            'educations' => 'required|array|min:1',

            // ID must exist if provided (important improvement 🔥)
            'educations.*.id' => 'nullable|integer|exists:educations,id',

            'educations.*.degree'      => 'required|string|max:255',
            'educations.*.field'       => 'nullable|string|max:255',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.university'  => 'nullable|string|max:255',
            'educations.*.location'    => 'nullable|string|max:255',

            'educations.*.start_date'  => 'required|date',
            'educations.*.end_date'    => 'nullable|date|after_or_equal:educations.*.start_date',
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
            'educations.required' => __('Education details are required'),
            'educations.array'    => __('Education must be a valid array'),
            'educations.min'      => __('At least one education record is required'),

            'educations.*.id.integer' => __('Invalid education ID'),
            'educations.*.id.exists'  => __('Selected education does not exist'),

            'educations.*.degree.required' => __('Degree is required'),
            'educations.*.degree.string'   => __('Degree must be a valid string'),
            'educations.*.degree.max'      => __('Degree must not exceed 255 characters'),

            'educations.*.field.string' => __('Field must be a valid string'),
            'educations.*.field.max'    => __('Field must not exceed 255 characters'),

            'educations.*.institution.required' => __('Institution is required'),
            'educations.*.institution.string'   => __('Institution must be a valid string'),
            'educations.*.institution.max'      => __('Institution must not exceed 255 characters'),

            'educations.*.university.string' => __('University must be a valid string'),
            'educations.*.university.max'    => __('University must not exceed 255 characters'),

            'educations.*.location.string' => __('Location must be a valid string'),
            'educations.*.location.max'    => __('Location must not exceed 255 characters'),

            'educations.*.start_date.required' => __('Start date is required'),
            'educations.*.start_date.date'     => __('Start date must be a valid date'),

            'educations.*.end_date.date' => __('End date must be a valid date'),
            'educations.*.end_date.after_or_equal' => __('End date must be after or equal to start date'),
        ];
    }
}