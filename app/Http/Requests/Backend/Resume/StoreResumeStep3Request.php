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
    | PREPARE DATA (Normalize Input)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'skills' => array_values($this->skills ?? [])
        ]);
    }

    public function rules(): array
    {
        return [
            'skills' => 'required|array|min:1',

            'skills.*.skill_name' => 'required|string|max:255',
            'skills.*.category'   => 'required|string|max:255',

            // SVG Path validation (basic safe string)
            'skills.*.icon_path' => 'required|string|max:1000',

            // optional SVG configs
            'skills.*.icon_viewbox' => 'nullable|string|max:100',
            'skills.*.icon_fill'    => 'nullable|string|max:50',
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
            'skills.required' => __('Skills are required'),
            'skills.array'    => __('Skills must be a valid array'),
            'skills.min'      => __('At least one skill is required'),

            'skills.*.skill_name.required' => __('Skill name is required'),
            'skills.*.skill_name.string'   => __('Skill name must be a valid string'),
            'skills.*.skill_name.max'      => __('Skill name must not exceed 255 characters'),

            'skills.*.category.required' => __('Skill category is required'),
            'skills.*.category.string'   => __('Skill category must be a valid string'),
            'skills.*.category.max'      => __('Skill category must not exceed 255 characters'),

            'skills.*.icon_path.required' => __('Skill icon path is required'),
            'skills.*.icon_path.string'   => __('Skill icon path must be a valid string'),
            'skills.*.icon_path.max'      => __('Skill icon path must not exceed 1000 characters'),

            'skills.*.icon_viewbox.string' => __('Icon viewbox must be a valid string'),
            'skills.*.icon_viewbox.max'    => __('Icon viewbox must not exceed 100 characters'),

            'skills.*.icon_fill.string' => __('Icon fill must be a valid string'),
            'skills.*.icon_fill.max'    => __('Icon fill must not exceed 50 characters'),
        ];
    }
}