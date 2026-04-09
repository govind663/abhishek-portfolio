<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Feature\StoreFeatureRequest;
use App\Http\Requests\Backend\Feature\UpdateFeatureRequest;
use App\Models\Feature;
use App\Services\FeatureService;
use Illuminate\Support\Facades\Cache;

class FeatureController extends Controller
{
    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $features = Feature::active()->latest()->get();

        return view('backend.features.index', [
            'features' => $features
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.features.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreFeatureRequest $request)
    {
        try {
            $this->featureService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('features');

            return redirect()->route('features.index')
                ->with('message', 'Feature created successfully');

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
        $feature = Feature::findOrFail($id);

        return view('backend.features.edit', [
            'feature' => $feature
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateFeatureRequest $request, string $id)
    {
        try {
            $feature = Feature::findOrFail($id);

            $this->featureService->update($feature, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('features');

            return redirect()->route('features.index')
                ->with('message', 'Feature updated successfully');

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
            $feature = Feature::findOrFail($id);

            $this->featureService->delete($feature);

            // ✅ CLEAR CACHE
            Cache::forget('features');

            return redirect()->route('features.index')
                ->with('message', 'Feature deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}