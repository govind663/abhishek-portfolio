<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\About\StoreAboutRequest;
use App\Http\Requests\Backend\About\UpdateAboutRequest;
use App\Models\AboutSection;
use App\Services\AboutService;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    protected $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $aboutData = AboutSection::active()->latest()->get();

        return view('backend.about.index', [
            'aboutData' => $aboutData
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.about.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreAboutRequest $request)
    {
        try {
            $this->aboutService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('about_section');

            return redirect()->route('about.index')
                ->with('message', 'About section created successfully');

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
        $about = AboutSection::findOrFail($id);

        return view('backend.about.edit', [
            'about' => $about
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateAboutRequest $request, string $id)
    {
        try {
            $about = AboutSection::findOrFail($id);

            $this->aboutService->update($about, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('about_section');

            return redirect()->route('about.index')
                ->with('message', 'About section updated successfully');

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
            $about = AboutSection::findOrFail($id);

            $this->aboutService->delete($about);

            // ✅ CLEAR CACHE
            Cache::forget('about_section');

            return redirect()->route('about.index')
                ->with('message', 'About section deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}