<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BrandDescription\StoreBrandDescriptionRequest;
use App\Http\Requests\Backend\BrandDescription\UpdateBrandDescriptionRequest;
use App\Models\BrandDescription;
use App\Services\BrandDescriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandDescriptionController extends Controller
{
    protected $service;

    public function __construct(BrandDescriptionService $service)
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
        $brandDescription = BrandDescription::active()->latestId()->get();

        return view('backend.brand-description.index', [
            'brandDescriptions' => $brandDescription,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.brand-description.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreBrandDescriptionRequest $request)
    {
        try {
            $this->service->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('brand_description');

            return redirect()->route('brand-description.index')
                ->with('message', 'Brand Description created successfully');

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
        $brandDescription = BrandDescription::findOrFail($id);

        return view('backend.brand-description.edit', [
            'brandDescription' => $brandDescription,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateBrandDescriptionRequest $request, string $id)
    {
        try {
            $model = BrandDescription::findOrFail($id);

            $this->service->update($model, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('brand_description');

            return redirect()->route('brand-description.index')
                ->with('message', 'Brand Description updated successfully');

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
            $model = BrandDescription::findOrFail($id);

            $this->service->delete($model);

            // ✅ CLEAR CACHE
            Cache::forget('brand_description');

            return redirect()->route('brand-description.index')
                ->with('message', 'Deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}