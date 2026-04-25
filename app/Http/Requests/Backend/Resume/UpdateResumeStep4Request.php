<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class UpdateResumeStep4Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (SAFE + CLEAN + FILTER)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $experiencesInput = $this->experiences ?? [];

        if (!is_array($experiencesInput)) {
            $experiencesInput = [];
        }

        $experiences = collect($experiencesInput)
            ->map(function ($exp) {

                $detailsInput = is_array($exp['details'] ?? null)
                    ? $exp['details']
                    : [];

                $details = collect($detailsInput)
                    ->map(function ($detail) {
                        return [
                            'id'          => $detail['id'] ?? null,
                            'description' => $this->clean($detail['description'] ?? null),
                        ];
                    })
                    ->filter(fn ($d) => !empty($d['description']))
                    ->values()
                    ->toArray();

                return [
                    'id'          => $exp['id'] ?? null,
                    'designation' => $this->clean($exp['designation'] ?? null),
                    'company'     => $this->clean($exp['company'] ?? null),
                    'location'    => $this->clean($exp['location'] ?? null),

                    'start_date'  => !empty($exp['start_date']) ? (string) $exp['start_date'] : null,
                    'end_date'    => !empty($exp['end_date']) ? (string) $exp['end_date'] : null,

                    'is_current'  => filter_var(
                        $exp['is_current'] ?? false,
                        FILTER_VALIDATE_BOOLEAN
                    ),

                    'details' => $details,
                ];
            })
            // 🔥 remove fully empty rows
            ->filter(function ($exp) {
                return !empty($exp['designation']) ||
                       !empty($exp['company']) ||
                       !empty($exp['start_date']);
            })
            ->values()
            ->toArray();

        $this->merge([
            'experiences' => $experiences
        ]);
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
            'experiences' => ['required', 'array', 'min:1'],

            // ✅ ownership + soft delete safe
            'experiences.*.id' => [
                'nullable',
                'integer',
                Rule::exists('experiences', 'id')
                    ->where('resume_id', $resumeId)
                    ->whereNull('deleted_at')
            ],

            'experiences.*.designation' => ['required', 'string', 'max:255'],
            'experiences.*.company'     => ['required', 'string', 'max:255'],
            'experiences.*.location'    => ['nullable', 'string', 'max:255'],

            'experiences.*.start_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            'experiences.*.end_date' => ['nullable', 'date'],

            'experiences.*.is_current' => ['required', 'boolean'],

            /*
            |--------------------------------------------------------------------------
            | DETAILS VALIDATION
            |--------------------------------------------------------------------------
            */
            'experiences.*.details' => ['required', 'array', 'min:1'],

            // ✅ simplified + safe ownership check
            'experiences.*.details.*.id' => [
                'nullable',
                'integer',
                Rule::exists('experience_details', 'id')
                    ->whereNull('deleted_at')
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
    | CUSTOM VALIDATION (SAFE + CARBON)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->experiences ?? [] as $index => $exp) {

                $start = $exp['start_date'] ?? null;
                $end   = $exp['end_date'] ?? null;
                $isCurrent = (bool) ($exp['is_current'] ?? false);

                try {

                    if ($start && $end) {
                        $startDate = Carbon::parse($start);
                        $endDate   = Carbon::parse($end);

                        if ($endDate->lt($startDate)) {
                            $validator->errors()->add(
                                "experiences.$index.end_date",
                                __('End date must be after or equal to start date')
                            );
                        }
                    }

                    if ($isCurrent && $end) {
                        $validator->errors()->add(
                            "experiences.$index.end_date",
                            __('End date must be empty for current job')
                        );
                    }

                    if (empty($exp['details'])) {
                        $validator->errors()->add(
                            "experiences.$index.details",
                            __('At least one valid description is required')
                        );
                    }

                } catch (\Exception $e) {
                    // ignore (handled by validation rules)
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */
    public function messages(): array
    {
        return [
            'experiences.required' => __('Experience details are required'),
            'experiences.min'      => __('At least one experience is required'),

            'experiences.*.id.exists' => __('Experience does not belong to this resume'),

            'experiences.*.designation.required' => __('Designation is required'),
            'experiences.*.company.required'     => __('Company name is required'),

            'experiences.*.start_date.required' => __('Start date is required'),
            'experiences.*.start_date.before_or_equal' => __('Start date cannot be in the future'),

            'experiences.*.end_date.date' => __('End date must be a valid date'),

            'experiences.*.details.min' => __('At least one experience detail is required'),

            'experiences.*.details.*.id.exists' => __('Invalid detail record'),

            'experiences.*.details.*.description.required' => __('Description is required'),
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