<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (CLEAN INPUT)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        if ($this->has('educations')) {
            $educations = array_map(function ($edu) {
                return [
                    'degree'      => isset($edu['degree']) ? trim($edu['degree']) : null,
                    'field'       => isset($edu['field']) ? trim($edu['field']) : null,
                    'institution' => isset($edu['institution']) ? trim($edu['institution']) : null,
                    'university'  => isset($edu['university']) ? trim($edu['university']) : null,
                    'location'    => isset($edu['location']) ? trim($edu['location']) : null,
                    'start_date'  => $edu['start_date'] ?? null,
                    'end_date'    => $edu['end_date'] ?? null,
                ];
            }, $this->educations);

            $this->merge(['educations' => $educations]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        return [
            'educations' => 'required|array|min:1',

            'educations.*.degree'      => 'required|string|max:255',
            'educations.*.field'       => 'nullable|string|max:255',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.university'  => 'nullable|string|max:255',
            'educations.*.location'    => 'nullable|string|max:255',

            // ✅ improved date validation
            'educations.*.start_date'  => 'required|date|before_or_equal:today',

            // ❗ FIXED BUG HERE
            'educations.*.end_date'    => 'nullable|date|after_or_equal:start_date',
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
            'educations.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'educations.*.end_date.date' => __('End date must be a valid date'),
            'educations.*.end_date.after_or_equal' => __('End date must be after or equal to start date'),
        ];
    }
}