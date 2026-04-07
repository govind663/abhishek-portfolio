<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Social\StoreSocialLinkRequest;
use App\Http\Requests\Backend\Social\UpdateSocialLinkRequest;
use App\Models\SocialLink;
use App\Services\SocialLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SocialLinkController extends Controller
{
    protected $service;

    public function __construct(SocialLinkService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $socialLinks = SocialLink::active()->latestId()->get();

        return view('backend.social.index', [
            'socialLinks' => $socialLinks
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.social.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreSocialLinkRequest $request)
    {
        try {
            $this->service->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('social_links');

            return redirect()->route('social-links.index')
                ->with('message', 'Social Link created successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $social = SocialLink::findOrFail($id);

        return view('backend.social.edit', [
            'social' => $social
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateSocialLinkRequest $request, $id)
    {
        try {
            $social = SocialLink::findOrFail($id);

            $this->service->update($social, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('social_links');

            return redirect()->route('social-links.index')
                ->with('message', 'Updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id, Request $request)
    {
        try {
            $social = SocialLink::findOrFail($id);

            $this->service->delete($social);

            // ✅ CLEAR CACHE
            Cache::forget('social_links');

            return redirect()->route('social-links.index')
                ->with('message', 'Deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}