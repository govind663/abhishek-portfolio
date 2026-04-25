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
    | PRE-PROCESS INPUT (CLEAN + NORMALIZE)
    |--------------------------------------------------------------------------
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'name'     => $this->clean($this->name),
            'title'    => $this->clean($this->title),
            'email'    => strtolower(trim((string) $this->email)),
            'phone'    => $this->normalizeIndianPhone($this->phone),
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
        // 🔥 FIX: route('id') use karo
        $resumeId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],

            'title' => ['required', 'string', 'max:255'],

            'summary' => ['required', 'string', 'min:10', 'max:2000'],

            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique('resumes', 'email')
                    ->ignore($resumeId)
                    ->whereNull('deleted_at'),
            ],

            'phone' => [
                'required',
                // 🔥 INDIA MOBILE VALIDATION
                'regex:/^(\+91|91)?[6-9]\d{9}$/',
                Rule::unique('resumes', 'phone')
                    ->ignore($resumeId)
                    ->whereNull('deleted_at'),
            ],

            'location' => ['required', 'string', 'max:255'],

            'status' => ['required', Rule::in(['active', 'inactive'])],
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
            'phone.regex' => __('Enter valid Indian mobile number (10 digit starting 6-9)'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN TEXT
    |--------------------------------------------------------------------------
    */
    private function clean($value)
    {
        return $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE INDIAN PHONE
    |--------------------------------------------------------------------------
    */
    private function normalizeIndianPhone($value)
    {
        if (!$value) return null;

        // remove spaces, dash, brackets
        $value = preg_replace('/[^0-9]/', '', $value);

        // remove country code if present
        if (str_starts_with($value, '91') && strlen($value) > 10) {
            $value = substr($value, -10);
        }

        return $value;
    }
}