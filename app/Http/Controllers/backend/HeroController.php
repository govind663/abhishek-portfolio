<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Hero\StoreHeroSectionRequest;
use App\Http\Requests\Backend\Hero\UpdateHeroSectionRequest;
use App\Models\HeroSection;
use App\Services\HeroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HeroController extends Controller
{
    protected $heroService;

    public function __construct(HeroService $heroService)
    {
        $this->heroService = $heroService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $heroes = HeroSection::active()->latestId()->get();

        return view('backend.hero.index', [
            'heroes' => $heroes
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.hero.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreHeroSectionRequest $request)
    {
        try {
            $this->heroService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('hero_section');

            return redirect()->route('hero.index')
                ->with('message', 'Hero created successfully');

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
        $hero = HeroSection::findOrFail($id);

        return view('backend.hero.edit', [
            'hero' => $hero
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateHeroSectionRequest $request, string $id)
    {
        try {
            $hero = HeroSection::findOrFail($id);

            $this->heroService->update($hero, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('hero_section');

            return redirect()->route('hero.index')
                ->with('message', 'Hero updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id, Request $request)
    {
        try {
            $hero = HeroSection::findOrFail($id);

            $this->heroService->delete($hero);

            // ✅ CLEAR CACHE
            Cache::forget('hero_section');

            return redirect()->route('hero.index')
                ->with('message', 'Hero deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}