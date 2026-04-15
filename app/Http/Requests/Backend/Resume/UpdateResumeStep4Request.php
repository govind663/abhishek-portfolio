<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeStep4Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (Normalize & Clean Input)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $experiencesInput = $this->experiences ?? [];

        if (!is_array($experiencesInput)) {
            $experiencesInput = [];
        }

        $experiences = array_map(function ($exp) {

            $detailsInput = $exp['details'] ?? [];

            if (!is_array($detailsInput)) {
                $detailsInput = [];
            }

            return [
                'id'          => $exp['id'] ?? null,
                'designation' => $this->clean($exp['designation'] ?? null),
                'company'     => $this->clean($exp['company'] ?? null),
                'location'    => $this->clean($exp['location'] ?? null),

                'start_date'  => !empty($exp['start_date']) ? $exp['start_date'] : null,
                'end_date'    => !empty($exp['end_date']) ? $exp['end_date'] : null,

                'is_current'  => filter_var(
                    $exp['is_current'] ?? false,
                    FILTER_VALIDATE_BOOLEAN
                ),

                'details' => array_values(array_map(function ($detail) {
                    return [
                        'id'          => $detail['id'] ?? null,
                        'description' => $this->clean($detail['description'] ?? null),
                    ];
                }, $detailsInput)),
            ];
        }, $experiencesInput);

        $this->merge([
            'experiences' => array_values($experiences)
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
            'experiences' => ['required', 'array', 'min:1'],

            'experiences.*.id' => [
                'nullable',
                'integer',
                'exists:experiences,id'
            ],

            'experiences.*.designation' => [
                'required',
                'string',
                'max:255'
            ],

            'experiences.*.company' => [
                'required',
                'string',
                'max:255'
            ],

            'experiences.*.location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'experiences.*.start_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            'experiences.*.end_date' => [
                'nullable',
                'date',
            ],

            'experiences.*.is_current' => [
                'required',
                'boolean'
            ],

            /*
            |--------------------------------------------------------------------------
            | NESTED DETAILS
            |--------------------------------------------------------------------------
            */
            'experiences.*.details' => [
                'required',
                'array',
                'min:1'
            ],

            'experiences.*.details.*.id' => [
                'nullable',
                'integer',
                'exists:experience_details,id'
            ],

            'experiences.*.details.*.description' => [
                'required',
                'string',
                'max:1000'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION LOGIC
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->experiences ?? [] as $index => $exp) {

                $start = $exp['start_date'] ?? null;
                $end   = $exp['end_date'] ?? null;

                // End date must be >= start date
                if (!empty($start) && !empty($end)) {
                    if (strtotime($end) < strtotime($start)) {
                        $validator->errors()->add(
                            "experiences.$index.end_date",
                            __('End date must be after or equal to start date')
                        );
                    }
                }

                // Current job → end_date must be null
                if (!empty($exp['is_current']) && !empty($end)) {
                    $validator->errors()->add(
                        "experiences.$index.end_date",
                        __('End date must be empty for current job')
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
            'experiences.required' => __('Experience details are required'),
            'experiences.array'    => __('Experience must be a valid array'),
            'experiences.min'      => __('At least one experience is required'),

            'experiences.*.id.integer' => __('Invalid experience ID'),
            'experiences.*.id.exists'  => __('Selected experience does not exist'),

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
            'experiences.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'experiences.*.end_date.date' => __('End date must be a valid date'),

            'experiences.*.is_current.required' => __('Current job status is required'),
            'experiences.*.is_current.boolean'  => __('Current job must be true or false'),

            'experiences.*.details.required' => __('Experience details are required'),
            'experiences.*.details.array'    => __('Experience details must be a valid array'),
            'experiences.*.details.min'      => __('At least one experience detail is required'),

            'experiences.*.details.*.id.integer' => __('Invalid detail ID'),
            'experiences.*.details.*.id.exists'  => __('Selected detail does not exist'),

            'experiences.*.details.*.description.required' => __('Description is required'),
            'experiences.*.details.*.description.string'   => __('Description must be a valid string'),
            'experiences.*.details.*.description.max'      => __('Description must not exceed 1000 characters'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value ? trim(preg_replace('/\s+/', ' ', $value)) : null;
    }
}