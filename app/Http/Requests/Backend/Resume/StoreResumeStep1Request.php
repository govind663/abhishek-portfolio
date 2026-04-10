<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeStep1Request extends FormRequest
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
            'name'     => trim($this->name),
            'title'    => trim($this->title),
            'email'    => strtolower(trim($this->email)),
            'phone'    => trim($this->phone),
            'location' => trim($this->location),
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
            'name'     => 'required|string|max:255',
            'title'    => 'required|string|max:255',
            'summary'  => 'required|string|min:10', // ✅ minimum length added

            'email'    => 'required|email:rfc,dns|max:255', // ✅ stronger email validation

            // ✅ improved phone validation
            'phone'    => [
                'required',
                'max:20',
                'regex:/^[0-9+\-\s()]+$/'
            ],

            'location' => 'required|string|max:255',
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
            'name.required'     => __('Name is required'),
            'name.string'       => __('Name must be a valid string'),
            'name.max'          => __('Name must not exceed 255 characters'),

            'title.required'    => __('Title is required'),
            'title.string'      => __('Title must be a valid string'),
            'title.max'         => __('Title must not exceed 255 characters'),

            'summary.required'  => __('Summary is required'),
            'summary.string'    => __('Summary must be a valid text'),
            'summary.min'       => __('Summary must be at least 10 characters'),

            'email.required'    => __('Email is required'),
            'email.email'       => __('Please enter a valid email address'),
            'email.max'         => __('Email must not exceed 255 characters'),

            'phone.required'    => __('Phone number is required'),
            'phone.regex'       => __('Phone number format is invalid'),
            'phone.max'         => __('Phone number must not exceed 20 characters'),

            'location.required' => __('Location is required'),
            'location.string'   => __('Location must be a valid string'),
            'location.max'      => __('Location must not exceed 255 characters'),
        ];
    }
}