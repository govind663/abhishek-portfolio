<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeRequest extends FormRequest
{
    /**
     * Authorize
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

            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            'name'      => ['required', 'string', 'max:255'],
            'title'     => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['required', 'string', 'max:20'],
            'location'  => ['required', 'string', 'max:255'],
            'status'    => ['required', 'in:active,inactive'],
            'summary'   => ['required', 'string'],

            /*
            |--------------------------------------------------------------------------
            | EDUCATIONS
            |--------------------------------------------------------------------------
            */

            'educations' => ['required', 'array', 'min:1'],

            'educations.*.degree'      => ['required', 'string', 'max:255'],
            'educations.*.field'       => ['nullable', 'string', 'max:255'],
            'educations.*.institution' => ['required', 'string', 'max:255'],
            'educations.*.university'  => ['nullable', 'string', 'max:255'],
            'educations.*.location'    => ['nullable', 'string', 'max:255'],
            'educations.*.start_date'  => ['required', 'date'],
            'educations.*.end_date'    => ['nullable', 'date', 'after_or_equal:educations.*.start_date'],

            /*
            |--------------------------------------------------------------------------
            | SKILLS
            |--------------------------------------------------------------------------
            */

            'skills' => ['required', 'array', 'min:1'],

            'skills.*.skill_name'   => ['required', 'string', 'max:255'],
            'skills.*.category'     => ['required', 'string', 'max:255'],
            'skills.*.icon_path'    => ['nullable', 'string'],
            'skills.*.icon_viewbox' => ['nullable', 'string', 'max:100'],
            'skills.*.icon_fill'    => ['nullable', 'string', 'max:50'],

            /*
            |--------------------------------------------------------------------------
            | EXPERIENCES
            |--------------------------------------------------------------------------
            */

            'experiences' => ['required', 'array', 'min:1'],

            'experiences.*.designation' => ['required', 'string', 'max:255'],
            'experiences.*.company'     => ['required', 'string', 'max:255'],
            'experiences.*.location'    => ['nullable', 'string', 'max:255'],
            'experiences.*.start_date'  => ['required', 'date'],
            'experiences.*.end_date'    => ['nullable', 'date', 'after_or_equal:experiences.*.start_date'],
            'experiences.*.description' => ['nullable', 'string'],
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            // Personal
            'name.required' => 'Name is required.',
            'title.required' => 'Title is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'phone.required' => 'Phone number is required.',
            'location.required' => 'Location is required.',
            'status.required' => 'Status is required.',
            'summary.required' => 'Summary is required.',

            // Education
            'educations.required' => 'Please add at least one education.',
            'educations.*.degree.required' => 'Degree is required.',
            'educations.*.institution.required' => 'Institution is required.',
            'educations.*.start_date.required' => 'Education start date is required.',
            'educations.*.end_date.after_or_equal' => 'Education end date must be after start date.',

            // Skills
            'skills.required' => 'Please add at least one skill.',
            'skills.*.skill_name.required' => 'Skill name is required.',
            'skills.*.category.required' => 'Skill category is required.',

            // Experience
            'experiences.required' => 'Please add at least one experience.',
            'experiences.*.designation.required' => 'Designation is required.',
            'experiences.*.company.required' => 'Company name is required.',
            'experiences.*.start_date.required' => 'Experience start date is required.',
            'experiences.*.end_date.after_or_equal' => 'Experience end date must be after start date.',
        ];
    }

    /**
     * Attribute Names
     */
    public function attributes(): array
    {
        return [

            'name' => 'Name',
            'title' => 'Title',
            'email' => 'Email',
            'phone' => 'Phone',
            'location' => 'Location',
            'status' => 'Status',
            'summary' => 'Summary',

            'educations.*.degree' => 'Degree',
            'educations.*.institution' => 'Institution',
            'educations.*.start_date' => 'Education Start Date',
            'educations.*.end_date' => 'Education End Date',

            'skills.*.skill_name' => 'Skill Name',
            'skills.*.category' => 'Skill Category',

            'experiences.*.designation' => 'Designation',
            'experiences.*.company' => 'Company',
            'experiences.*.start_date' => 'Experience Start Date',
            'experiences.*.end_date' => 'Experience End Date',
        ];
    }
}