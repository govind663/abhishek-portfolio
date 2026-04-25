<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (CLEAN + NORMALIZE)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        if (!empty($this->educations) && is_array($this->educations)) {

            $educations = array_map(function ($edu) {

                return [
                    'id'          => $edu['id'] ?? null,
                    'degree'      => $this->clean($edu['degree'] ?? null),
                    'field'       => $this->clean($edu['field'] ?? null),
                    'institution' => $this->clean($edu['institution'] ?? null),
                    'university'  => $this->clean($edu['university'] ?? null),
                    'location'    => $this->clean($edu['location'] ?? null),

                    'start_date'  => !empty($edu['start_date']) ? (string) $edu['start_date'] : null,
                    'end_date'    => !empty($edu['end_date']) ? (string) $edu['end_date'] : null,

                    'is_current'  => filter_var(
                        $edu['is_current'] ?? false,
                        FILTER_VALIDATE_BOOLEAN
                    ),
                ];

            }, $this->educations);

            $this->merge([
                'educations' => array_values($educations)
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        $resumeId = optional($this->route('resume'))->id;

        return [
            'educations' => ['required', 'array', 'min:1'],

            'educations.*.id' => [
                'nullable',
                'integer',
                Rule::exists('educations', 'id')
                    ->where('resume_id', $resumeId)
            ],

            'educations.*.degree' => ['required', 'string', 'max:255'],
            'educations.*.field' => ['nullable', 'string', 'max:255'],
            'educations.*.institution' => ['required', 'string', 'max:255'],
            'educations.*.university' => ['nullable', 'string', 'max:255'],
            'educations.*.location' => ['nullable', 'string', 'max:255'],

            'educations.*.start_date' => ['required', 'date', 'before_or_equal:today'],

            // FIX: keep rule but remove wildcard dependency issue risk
            'educations.*.end_date' => ['nullable', 'date'],

            'educations.*.is_current' => ['nullable', 'boolean'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION (SAFE WILDCARD FIX)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->educations ?? [] as $index => $edu) {

                $start = $edu['start_date'] ?? null;
                $end = $edu['end_date'] ?? null;
                $isCurrent = (bool) ($edu['is_current'] ?? false);

                /*
                |--------------------------------------------------------------------------
                | 1. END DATE VALIDATION (START <= END)
                |--------------------------------------------------------------------------
                */
                if (!empty($start) && !empty($end)) {

                    if (strtotime($end) < strtotime($start)) {
                        $validator->errors()->add(
                            "educations.$index.end_date",
                            __('End date must be after or equal to start date')
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 2. CURRENT EDUCATION RULE
                |--------------------------------------------------------------------------
                */
                if ($isCurrent && !empty($end)) {
                    $validator->errors()->add(
                        "educations.$index.end_date",
                        __('End date must be empty if currently studying')
                    );
                }
            }
        });
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
            'educations.*.id.exists'  => __('Selected education does not belong to this resume'),

            'educations.*.degree.required' => __('Degree is required'),
            'educations.*.degree.max'      => __('Degree must not exceed 255 characters'),

            'educations.*.institution.required' => __('Institution is required'),
            'educations.*.institution.max'      => __('Institution must not exceed 255 characters'),

            'educations.*.start_date.required' => __('Start date is required'),
            'educations.*.start_date.date'     => __('Start date must be a valid date'),
            'educations.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'educations.*.end_date.date' => __('End date must be a valid date'),

            'educations.*.is_current.boolean' => __('Current status must be true or false'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTION
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }
}