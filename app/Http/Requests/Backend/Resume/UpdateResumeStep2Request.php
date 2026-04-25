<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class UpdateResumeStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (CLEAN + FILTER EMPTY)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $educationsInput = $this->educations ?? [];

        if (!is_array($educationsInput)) {
            $educationsInput = [];
        }

        $educations = collect($educationsInput)
            ->map(function ($edu) {
                return [
                    'id'          => $edu['id'] ?? null,
                    'degree'      => $this->clean($edu['degree'] ?? null),
                    'field'       => $this->clean($edu['field'] ?? null),
                    'institution' => $this->clean($edu['institution'] ?? null),
                    'university'  => $this->clean($edu['university'] ?? null),
                    'location'    => $this->clean($edu['location'] ?? null),

                    'start_date'  => !empty($edu['start_date']) ? $edu['start_date'] : null,
                    'end_date'    => !empty($edu['end_date']) ? $edu['end_date'] : null,

                    'is_current'  => filter_var(
                        $edu['is_current'] ?? false,
                        FILTER_VALIDATE_BOOLEAN
                    ),
                ];
            })
            // 🔥 REMOVE EMPTY ROWS
            ->filter(function ($edu) {
                return !empty($edu['degree']) ||
                       !empty($edu['institution']) ||
                       !empty($edu['start_date']);
            })
            ->values()
            ->toArray();

        $this->merge([
            'educations' => $educations
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        // ✅ SAFE: route model binding handle karega (object ya id dono)
        $resumeId = optional($this->route('resume'))->id 
                    ?? $this->route('id');

        return [
            'educations' => ['required', 'array', 'min:1'],

            'educations.*.id' => [
                'nullable',
                'integer',
                Rule::exists('educations', 'id')
                    ->where(function ($query) use ($resumeId) {
                        $query->where('resume_id', $resumeId)
                              ->whereNull('deleted_at');
                    }),
            ],

            'educations.*.degree' => ['required', 'string', 'max:255'],
            'educations.*.field' => ['nullable', 'string', 'max:255'],
            'educations.*.institution' => ['required', 'string', 'max:255'],
            'educations.*.university' => ['nullable', 'string', 'max:255'],
            'educations.*.location' => ['nullable', 'string', 'max:255'],

            'educations.*.start_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            'educations.*.end_date' => ['nullable', 'date'],

            'educations.*.is_current' => ['nullable', 'boolean'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION (SAFE LOGIC)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->educations ?? [] as $index => $edu) {

                $start = $edu['start_date'] ?? null;
                $end   = $edu['end_date'] ?? null;
                $isCurrent = (bool) ($edu['is_current'] ?? false);

                try {

                    // ✅ END DATE >= START DATE
                    if ($start && $end) {

                        $startDate = Carbon::parse($start);
                        $endDate   = Carbon::parse($end);

                        if ($endDate->lt($startDate)) {
                            $validator->errors()->add(
                                "educations.$index.end_date",
                                __('End date must be after or equal to start date')
                            );
                        }
                    }

                    // ✅ CURRENT EDUCATION RULE
                    if ($isCurrent && $end) {
                        $validator->errors()->add(
                            "educations.$index.end_date",
                            __('End date must be empty if currently studying')
                        );
                    }

                } catch (\Exception $e) {
                    // ignore (date rule already handle karega)
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
            'educations.min'      => __('At least one valid education record is required'),

            'educations.*.id.exists' => __('Invalid education record'),

            'educations.*.degree.required' => __('Degree is required'),
            'educations.*.institution.required' => __('Institution is required'),

            'educations.*.start_date.required' => __('Start date is required'),
            'educations.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'educations.*.end_date.date' => __('End date must be a valid date'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }
}