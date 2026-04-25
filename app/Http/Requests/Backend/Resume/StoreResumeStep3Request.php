<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeStep3Request extends FormRequest
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
        $skillsInput = $this->skills ?? [];

        if (!is_array($skillsInput)) {
            $skillsInput = [];
        }

        $skills = collect($skillsInput)
            ->map(function ($skill) {

                return [
                    'skill_name'   => $this->clean($skill['skill_name'] ?? null),
                    'category'     => $this->clean($skill['category'] ?? null),

                    // ✅ SAFE TRIM (avoid null -> "")
                    'icon_path'    => isset($skill['icon_path'])
                        ? trim((string) $skill['icon_path'])
                        : null,

                    // ✅ DEFAULT ONLY IF EMPTY
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
        return [
            'skills' => ['required', 'array', 'min:1'],

            'skills.*.skill_name' => ['required', 'string', 'max:255'],
            'skills.*.category'   => ['required', 'string', 'max:255'],

            // 🔐 STRICT SVG PATH VALIDATION
            'skills.*.icon_path' => [
                'required',
                'string',
                'max:1000',
                'regex:/^[MmLlHhVvCcSsQqTtAaZz0-9,.\-\s]+$/'
            ],

            // 🔐 VIEWBOX VALIDATION (4 numbers only)
            'skills.*.icon_viewbox' => [
                'nullable',
                'regex:/^\d+\s\d+\s\d+\s\d+$/'
            ],

            // 🔐 HEX COLOR VALIDATION
            'skills.*.icon_fill' => [
                'nullable',
                'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM VALIDATION (NO DUPLICATE SKILLS)
    |--------------------------------------------------------------------------
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $names = [];

            foreach ($this->skills ?? [] as $index => $skill) {

                $name = strtolower(trim($skill['skill_name'] ?? ''));

                if ($name && in_array($name, $names, true)) {
                    $validator->errors()->add(
                        "skills.$index.skill_name",
                        __('Duplicate skill not allowed')
                    );
                }

                if ($name) {
                    $names[] = $name;
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
            'skills.min'      => __('At least one valid skill is required'),

            'skills.*.skill_name.required' => __('Skill name is required'),
            'skills.*.category.required'   => __('Skill category is required'),

            'skills.*.icon_path.required' => __('SVG path is required'),
            'skills.*.icon_path.regex'    => __('Invalid SVG path format'),

            'skills.*.icon_viewbox.regex' => __('ViewBox must be like: 0 0 24 24'),

            'skills.*.icon_fill.regex' => __('Color must be valid hex (e.g. #000 or #000000)'),
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