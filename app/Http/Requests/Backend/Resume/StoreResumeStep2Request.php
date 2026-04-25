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
    | PREPARE DATA (CLEAN + SAFE NORMALIZATION)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $educationsInput = $this->educations ?? [];

        if (!is_array($educationsInput)) {
            $educationsInput = [];
        }

        $educations = array_map(function ($edu) {

            return [
                'degree'      => $this->clean($edu['degree'] ?? null),
                'field'       => $this->clean($edu['field'] ?? null),
                'institution' => $this->clean($edu['institution'] ?? null),
                'university'  => $this->clean($edu['university'] ?? null),
                'location'    => $this->clean($edu['location'] ?? null),

                'start_date'  => !empty($edu['start_date'])
                    ? (string) $edu['start_date']
                    : null,

                'end_date'    => !empty($edu['end_date'])
                    ? (string) $edu['end_date']
                    : null,
            ];

        }, $educationsInput);

        $this->merge([
            'educations' => array_values($educations)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        return [
            'educations' => ['required', 'array', 'min:1'],

            'educations.*.degree' => [
                'required',
                'string',
                'max:255'
            ],

            'educations.*.field' => [
                'nullable',
                'string',
                'max:255'
            ],

            'educations.*.institution' => [
                'required',
                'string',
                'max:255'
            ],

            'educations.*.university' => [
                'nullable',
                'string',
                'max:255'
            ],

            'educations.*.location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'educations.*.start_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            // ✔ SAFE (no wildcard dependency bug)
            'educations.*.end_date' => [
                'nullable',
                'date'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION (LOGICAL RULES)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->educations ?? [] as $index => $edu) {

                $start = $edu['start_date'] ?? null;
                $end   = $edu['end_date'] ?? null;

                // ✔ End date must be >= start date
                if (!empty($start) && !empty($end)) {

                    if (strtotime($end) < strtotime($start)) {
                        $validator->errors()->add(
                            "educations.$index.end_date",
                            __('End date must be after or equal to start date')
                        );
                    }
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

            'educations.*.degree.required' => __('Degree is required'),
            'educations.*.degree.max'      => __('Degree must not exceed 255 characters'),

            'educations.*.field.max' => __('Field must not exceed 255 characters'),

            'educations.*.institution.required' => __('Institution is required'),
            'educations.*.institution.max'      => __('Institution must not exceed 255 characters'),

            'educations.*.university.max' => __('University must not exceed 255 characters'),

            'educations.*.location.max' => __('Location must not exceed 255 characters'),

            'educations.*.start_date.required' => __('Start date is required'),
            'educations.*.start_date.date'     => __('Start date must be a valid date'),
            'educations.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'educations.*.end_date.date' => __('End date must be a valid date'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER (CLEAN INPUT)
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }
}