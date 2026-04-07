<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Copyright\StoreCopyrightRequest;
use App\Models\Copyright;
use App\Services\CopyrightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CopyrightController extends Controller
{
    protected $service;

    public function __construct(CopyrightService $service)
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
        $copyrights = Copyright::active()->latestId()->get();

        return view('backend.copyright.index', [
            'copyrights' => $copyrights
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.copyright.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreCopyrightRequest $request)
    {
        try {
            $this->service->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('copyright');

            return redirect()->route('copyrights.index')
                ->with('message', 'Created successfully');

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
        $copyright = Copyright::findOrFail($id);

        return view('backend.copyright.edit', [
            'copyright' => $copyright
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, string $id)
    {
        try {
            $model = Copyright::findOrFail($id);

            $this->service->update($model, $request->all());

            // ✅ CLEAR CACHE
            Cache::forget('copyright');

            return redirect()->route('copyrights.index')
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
    public function destroy(string $id)
    {
        try {
            $model = Copyright::findOrFail($id);

            $this->service->delete($model);

            // ✅ CLEAR CACHE
            Cache::forget('copyright');

            return redirect()->route('copyrights.index')
                ->with('message', 'Deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}