<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeStep3Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA (SAFE + NORMALIZED + FILTER EMPTY)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $skillsInput = $this->skills ?? [];

        if (!is_array($skillsInput)) {
            $skillsInput = [];
        }

        $skills = collect($skillsInput)
            ->map(function ($skill) {
                return [
                    'id'           => $skill['id'] ?? null,
                    'skill_name'   => $this->clean($skill['skill_name'] ?? null),
                    'category'     => $this->clean($skill['category'] ?? null),

                    // ✅ null-safe handling
                    'icon_path'    => isset($skill['icon_path'])
                        ? trim((string) $skill['icon_path'])
                        : null,

                    'icon_viewbox' => !empty($skill['icon_viewbox'])
                        ? $this->clean($skill['icon_viewbox'])
                        : '0 0 24 24',

                    'icon_fill'    => !empty($skill['icon_fill'])
                        ? $this->clean($skill['icon_fill'])
                        : '#000',
                ];
            })
            // 🔥 REMOVE EMPTY ROWS (STRICT)
            ->filter(function ($skill) {
                return !empty($skill['skill_name']) &&
                       !empty($skill['category']) &&
                       !empty($skill['icon_path']);
            })
            ->values()
            ->toArray();

        $this->merge([
            'skills' => $skills
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */
    public function rules(): array
    {
        // ✅ SAFE route handling (object OR id)
        $resumeId = optional($this->route('resume'))->id 
                    ?? $this->route('id');

        return [
            'skills' => ['required', 'array', 'min:1'],

            // 🔐 SECURE ID VALIDATION (ownership + soft delete safe)
            'skills.*.id' => [
                'nullable',
                'integer',
                Rule::exists('technical_skills', 'id')
                    ->where(function ($query) use ($resumeId) {
                        $query->where('resume_id', $resumeId)
                              ->whereNull('deleted_at');
                    }),
            ],

            'skills.*.skill_name' => [
                'required',
                'string',
                'max:255'
            ],

            'skills.*.category' => [
                'required',
                'string',
                'max:255'
            ],

            // 🔐 SAFE SVG VALIDATION
            'skills.*.icon_path' => [
                'required',
                'string',
                'max:1000',
                'regex:/^[MmLlHhVvCcSsQqTtAaZz0-9,\.\-\s]+$/'
            ],

            'skills.*.icon_viewbox' => [
                'nullable',
                'regex:/^\d+\s\d+\s\d+\s\d+$/'
            ],

            'skills.*.icon_fill' => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION (DUPLICATE + EDGE CASES)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $names = [];

            foreach ($this->skills ?? [] as $index => $skill) {

                $name = strtolower(trim($skill['skill_name'] ?? ''));
                $path = $skill['icon_path'] ?? '';

                // ✅ Duplicate skill check (case insensitive)
                if ($name && in_array($name, $names, true)) {
                    $validator->errors()->add(
                        "skills.$index.skill_name",
                        __('Duplicate skill not allowed')
                    );
                }

                if ($name) {
                    $names[] = $name;
                }

                // 🚨 extra safety (edge-case)
                if ($path !== null && strlen($path) > 1000) {
                    $validator->errors()->add(
                        "skills.$index.icon_path",
                        __('SVG path too large')
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
            'skills.required' => __('Skills are required'),
            'skills.array'    => __('Skills must be a valid array'),
            'skills.min'      => __('At least one skill is required'),

            'skills.*.id.integer' => __('Invalid skill ID'),
            'skills.*.id.exists'  => __('Skill does not belong to this resume'),

            'skills.*.skill_name.required' => __('Skill name is required'),
            'skills.*.skill_name.max'      => __('Skill name must not exceed 255 characters'),

            'skills.*.category.required' => __('Category is required'),
            'skills.*.category.max'      => __('Category must not exceed 255 characters'),

            'skills.*.icon_path.required' => __('Icon path is required'),
            'skills.*.icon_path.regex'    => __('Invalid SVG path format'),

            'skills.*.icon_viewbox.regex' => __('ViewBox must be like: 0 0 24 24'),

            'skills.*.icon_fill.regex' => __('Fill must be valid HEX color (#000 or #000000)'),
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