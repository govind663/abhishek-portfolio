<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            // 'g-recaptcha-response' => 'required|captcha|captcha_api', // reCAPTCHA field    
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
            'name.required'    => 'Please enter your name.',
            'email.required'   => 'Please enter your email id.',
            'email.email'      => 'Please enter a valid email id.',
            'phone.required'   => 'Please enter your phone number.',
            'phone.string'     => 'Please enter a valid phone number.',
            'subject.required' => 'Please enter your subject.',
            'message.required' => 'Please enter your message.',
            // 'g-recaptcha-response.required' => 'Please complete the reCAPTCHA to verify you are not a robot.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
