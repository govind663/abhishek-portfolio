<?php

namespace App\Http\Requests\Backend\Resume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeRequest extends FormRequest
{
    /**
     * Authorize Request
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Prepare Request Data
     */
    protected function prepareForValidation(): void
    {
        $experiences = $this->input('experiences', []);

        foreach ($experiences as &$experience) {

            if (!empty($experience['details'])) {

                foreach ($experience['details'] as &$detail) {

                    /*
                    |--------------------------------------------------------------------------
                    | Remove Empty ID
                    |--------------------------------------------------------------------------
                    */

                    if (empty($detail['id'])) {
                        unset($detail['id']);
                    }

                }
            }
        }


        $this->merge([
            'experiences' => $experiences,
        ]);
    }


    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $resume = $this->route('resume');

        $resumeId = is_object($resume)
            ? $resume->id
            : $resume;


        return [

            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('resumes','email')
                    ->ignore($resumeId),
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive'
                ]),
            ],

            'summary' => [
                'required',
                'string',
            ],



            /*
            |--------------------------------------------------------------------------
            | EDUCATIONS
            |--------------------------------------------------------------------------
            */

            'educations' => [
                'required',
                'array',
                'min:1',
            ],

            'educations.*.id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:educations,id',
            ],

            'educations.*.degree' => [
                'required',
                'string',
                'max:255',
            ],

            'educations.*.field' => [
                'nullable',
                'string',
                'max:255',
            ],

            'educations.*.institution' => [
                'required',
                'string',
                'max:255',
            ],

            'educations.*.university' => [
                'nullable',
                'string',
                'max:255',
            ],

            'educations.*.location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'educations.*.start_date' => [
                'required',
                'date',
            ],

            'educations.*.end_date' => [
                'nullable',
                'date',
                'after_or_equal:educations.*.start_date',
            ],



            /*
            |--------------------------------------------------------------------------
            | TECHNICAL SKILLS
            |--------------------------------------------------------------------------
            */

            'skills' => [
                'required',
                'array',
                'min:1',
            ],

            'skills.*.id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:technical_skills,id',
            ],

            'skills.*.skill_name' => [
                'required',
                'string',
                'max:255',
            ],

            'skills.*.category' => [
                'required',
                'string',
                'max:255',
            ],

            'skills.*.icon_path' => [
                'nullable',
                'string',
            ],

            'skills.*.icon_viewbox' => [
                'nullable',
                'string',
                'max:100',
            ],

            'skills.*.icon_fill' => [
                'nullable',
                'string',
                'max:50',
            ],



            /*
            |--------------------------------------------------------------------------
            | EXPERIENCES
            |--------------------------------------------------------------------------
            */

            'experiences' => [
                'required',
                'array',
                'min:1',
            ],


            'experiences.*.id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:experiences,id',
            ],


            'experiences.*.designation' => [
                'required',
                'string',
                'max:255',
            ],


            'experiences.*.company' => [
                'required',
                'string',
                'max:255',
            ],


            'experiences.*.location' => [
                'nullable',
                'string',
                'max:255',
            ],


            'experiences.*.start_date' => [
                'required',
                'date',
            ],


            'experiences.*.end_date' => [
                'nullable',
                'date',
                'after_or_equal:experiences.*.start_date',
            ],



            /*
            |--------------------------------------------------------------------------
            | EXPERIENCE DETAILS
            |--------------------------------------------------------------------------
            */

            'experiences.*.details' => [
                'nullable',
                'array',
            ],


            'experiences.*.details.*.id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:experience_details,id',
            ],


            'experiences.*.details.*.description' => [
                'required',
                'string',
                'max:1000',
            ],

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
            'summary' => 'Summary',
            'status' => 'Status',


            'educations.*.degree' => 'Degree',
            'educations.*.institution' => 'Institution',
            'educations.*.start_date' => 'Education Start Date',
            'educations.*.end_date' => 'Education End Date',


            'skills.*.skill_name' => 'Skill Name',
            'skills.*.category' => 'Skill Category',


            'experiences.*.designation' => 'Designation',
            'experiences.*.company' => 'Company',
            'experiences.*.location' => 'Experience Location',
            'experiences.*.start_date' => 'Experience Start Date',
            'experiences.*.end_date' => 'Experience End Date',

            'experiences.*.details.*.description'
                => 'Experience Description',

        ];
    }



    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'name.required'
                => 'Name is required.',

            'title.required'
                => 'Title is required.',

            'email.required'
                => 'Email is required.',

            'email.email'
                => 'Please enter valid email.',

            'phone.required'
                => 'Phone number is required.',

            'location.required'
                => 'Location is required.',

            'summary.required'
                => 'Summary is required.',



            'educations.required'
                => 'At least one education is required.',

            'educations.*.degree.required'
                => 'Degree is required.',

            'educations.*.institution.required'
                => 'Institution is required.',



            'skills.required'
                => 'At least one skill is required.',

            'skills.*.skill_name.required'
                => 'Skill name is required.',

            'skills.*.category.required'
                => 'Skill category is required.',



            'experiences.required'
                => 'At least one experience is required.',

            'experiences.*.designation.required'
                => 'Designation is required.',

            'experiences.*.company.required'
                => 'Company is required.',

            'experiences.*.start_date.required'
                => 'Experience start date is required.',


            'experiences.*.details.*.description.required'
                => 'Experience description is required.',
        ];
    }
}