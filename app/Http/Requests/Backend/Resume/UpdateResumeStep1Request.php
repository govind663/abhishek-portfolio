<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PRE-PROCESS INPUT (CLEAN DATA)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'name'     => $this->clean($this->name),
            'title'    => $this->clean($this->title),
            'email'    => strtolower(trim((string) $this->email)),
            'phone'    => trim((string) $this->phone),
            'location' => $this->clean($this->location),
            'summary'  => trim((string) $this->summary),
            'status'   => trim((string) $this->status),
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
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'summary' => [
                'required',
                'string',
                'min:10',
                'max:2000'
            ],

            'phone' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[0-9+\-\s()]+$/',
                Rule::unique('resumes', 'phone')
                    ->ignore($resumeId)
                    ->whereNull('deleted_at'),
            ],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('resumes', 'email')
                    ->ignore($resumeId)
                    ->whereNull('deleted_at'),
            ],

            'location' => [
                'required',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'in:active,inactive'
            ]
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
            'name.required' => __('Name is required'),
            'name.max'      => __('Name must not exceed 255 characters'),

            'title.required' => __('Title is required'),
            'title.max'      => __('Title must not exceed 255 characters'),

            'summary.required' => __('Summary is required'),
            'summary.min'      => __('Summary must be at least 10 characters'),
            'summary.max'      => __('Summary must not exceed 2000 characters'),

            'email.required' => __('Email is required'),
            'email.email'    => __('Please enter a valid email address'),
            'email.max'      => __('Email must not exceed 255 characters'),
            'email.unique'   => __('This email address is already associated with another resume'),

            'phone.required' => __('Phone number is required'),
            'phone.regex'    => __('Phone number format is invalid'),
            'phone.min'      => __('Phone number must be at least 8 characters'),
            'phone.max'      => __('Phone number must not exceed 20 characters'),
            'phone.unique'   => __('This phone number is already associated with another resume'),

            'location.required' => __('Location is required'),
            'location.max'      => __('Location must not exceed 255 characters'),

            'status.required' => __('Status is required'),
            'status.in'       => __('Status must be either active or inactive'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTION (Clean Input)
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value ? trim(preg_replace('/\s+/', ' ', (string) $value)) : null;
    }
}