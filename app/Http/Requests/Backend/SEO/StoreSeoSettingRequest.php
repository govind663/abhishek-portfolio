<?php

namespace App\Http\Requests\Backend\SEO;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeoSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_name' => 'required|unique:seo_settings,page_name',

            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'keywords' => 'required|string',
            'canonical' => 'required|url',

            'og_title' => 'required|string|max:255',
            'og_description' => 'required|string',
            'og_url' => 'required|url',
            'og_type' => 'required|string',
            'og_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'twitter_card' => 'required|string',
            'twitter_title' => 'required|string|max:255',
            'twitter_description' => 'required|string',
            'twitter_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'page_name.required' => __('Page name is required'),
            'page_name.unique' => __('Page name already exists'),
            'title.required' => __('Title is required'),
            'description.required' => __('Description is required'),
            'keywords.required' => __('Keywords are required'),
            'canonical.required' => __('Canonical URL is required'),
            'canonical.url' => __('Canonical URL must be a valid URL'),
            'og_title.required' => __('Open Graph Title is required'),
            'og_description.required' => __('Open Graph Description is required'),
            'og_url.required' => __('Open Graph URL is required'),
            'og_url.url' => __('Open Graph URL must be a valid URL'),
            'og_type.required' => __('Open Graph Type is required'),
            'og_image.required' => __('Open Graph Image is required'),
            'og_image.image' => __('Open Graph Image must be an image'),
            'og_image.mimes' => __('Open Graph Image must be a file of type: jpg, jpeg, png, webp'),
            'og_image.max' => __('Open Graph Image may not be greater than 2048 kilobytes'),
            'twitter_card.required' => __('Twitter Card is required'),
            'twitter_title.required' => __('Twitter Title is required'),
            'twitter_description.required' => __('Twitter Description is required'),
            'twitter_image.required' => __('Twitter Image is required'),
            'twitter_image.image' => __('Twitter Image must be an image'),
            'twitter_image.mimes' => __('Twitter Image must be a file of type: jpg, jpeg, png, webp'),
            'twitter_image.max' => __('Twitter Image may not be greater than 2048 kilobytes'),
            'status.required' => __('Status is required'),
        ];
    }
}