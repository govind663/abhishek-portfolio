<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SEO\StoreSeoSettingRequest;
use App\Http\Requests\Backend\SEO\UpdateSeoSettingRequest;
use App\Models\SeoSetting;
use App\Services\SeoSettingService;
use Illuminate\Support\Facades\Cache;

class SeoSettingController extends Controller
{
    protected $seoService;

    public function __construct(SeoSettingService $seoService)
    {
        $this->seoService = $seoService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $seoSettings = SeoSetting::orderBy("id","desc")
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->get();

        return view('backend.seo.index', [
            'seoSettings' => $seoSettings
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.seo.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreSeoSettingRequest $request)
    {
        try {
            $this->seoService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('seo_settings');

            return redirect()->route('seo-settings.index')
                ->with('message', 'SEO created successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(string $id)
    {
        $seo = SeoSetting::findOrFail($id);

        return view('backend.seo.edit', [
            'seo' => $seo
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateSeoSettingRequest $request, string $id)
    {
        try {
            $seo = SeoSetting::findOrFail($id);

            $this->seoService->update($seo, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('seo_settings');

            return redirect()->route('seo-settings.index')
                ->with('message', 'SEO updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id)
    {
        try {
            $seo = SeoSetting::findOrFail($id);

            $this->seoService->delete($seo);

            // ✅ CLEAR CACHE
            Cache::forget('seo_settings');

            return redirect()->route('seo-settings.index')
                ->with('message', 'SEO deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}