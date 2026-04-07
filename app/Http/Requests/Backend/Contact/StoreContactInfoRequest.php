<?php

namespace App\Http\Requests\Backend\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'digits:10',
                Rule::unique('contact_infos', 'phone')->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contact_infos', 'email')->whereNull('deleted_at')
            ],
            'address' => 'required|string|max:255',
            'working_hours' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('Phone number is required'),
            'phone.digits' => __('Phone number must be 10 digits'),
            'phone.unique' => __('Phone number already exists'),

            'email.required' => __('Email Id is required'),
            'email.email' => __('Email Id must be valid'),
            'email.max' => __('Email Id must be less than 255 characters'),
            'email.unique' => __('Email Id already exists'),

            'address.required' => __('Address is required'),
            'address.string' => __('Address must be a string'),
            'address.max' => __('Address must be less than 255 characters'),

            'working_hours.required' => __('Working hours are required'),
            'working_hours.string' => __('Working hours must be a string'),
            'working_hours.max' => __('Working hours must be less than 255 characters'),

            'status.required' => __('Status is required'),
            'status.in' => __('Status must be either active or inactive'),
        ];
    }
}