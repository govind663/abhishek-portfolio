<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Stat\StoreStatRequest;
use App\Http\Requests\Backend\Stat\UpdateStatRequest;
use App\Models\Stat;
use App\Services\StatService;
use Illuminate\Support\Facades\Cache;

class StatController extends Controller
{
    protected $statService;

    public function __construct(StatService $statService)
    {
        $this->statService = $statService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $stats = Stat::active()->latest()->get();

        return view('backend.stats.index', [
            'stats' => $stats
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.stats.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreStatRequest $request)
    {
        try {
            $this->statService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('stats');

            return redirect()->route('stats.index')
                ->with('message', 'Stat created successfully');

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
        $stat = Stat::findOrFail($id);

        return view('backend.stats.edit', [
            'stat' => $stat
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateStatRequest $request, string $id)
    {
        try {
            $stat = Stat::findOrFail($id);

            $this->statService->update($stat, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('stats');

            return redirect()->route('stats.index')
                ->with('message', 'Stat updated successfully');

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
            $stat = Stat::findOrFail($id);

            $this->statService->delete($stat);

            // ✅ CLEAR CACHE
            Cache::forget('stats');

            return redirect()->route('stats.index')
                ->with('message', 'Stat deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}