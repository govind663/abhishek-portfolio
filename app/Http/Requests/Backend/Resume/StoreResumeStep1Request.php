<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'     => $this->clean($this->name),
            'title'    => $this->clean($this->title),
            'email'    => strtolower(trim((string) $this->email)),
            'phone'    => $this->normalizePhone($this->phone),
            'location' => $this->clean($this->location),
            'summary'  => trim((string) $this->summary),
            'status'   => strtolower(trim((string) $this->status)),
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
            'name' => ['required','string','max:255'],

            'title' => ['required','string','max:255'],

            'summary' => ['required','string','min:10','max:2000'],

            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique('resumes', 'email')
            ],

            'phone' => [
                'required',
                'string',
                // INDIA MOBILE VALIDATION
                'regex:/^(?:\+91|91)?[6-9]\d{9}$/',
                Rule::unique('resumes', 'phone')
            ],

            'location' => ['required','string','max:255'],

            'status' => [
                'required',
                Rule::in(['active', 'inactive'])
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
            'phone.regex' => __('Enter valid Indian mobile number (10 digits, starts with 6-9, optional +91)'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function clean($value)
    {
        return $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }

    private function normalizePhone($value)
    {
        if (!$value) return null;

        // remove spaces + special chars except +
        $value = preg_replace('/[^0-9+]/', '', $value);

        // convert 91XXXXXXXXXX → +91XXXXXXXXXX
        if (preg_match('/^91[6-9]\d{9}$/', $value)) {
            $value = '+' . $value;
        }

        // convert 10 digit → +91XXXXXXXXXX
        if (preg_match('/^[6-9]\d{9}$/', $value)) {
            $value = '+91' . $value;
        }

        return $value;
    }
}