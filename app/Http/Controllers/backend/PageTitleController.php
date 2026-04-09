<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PageTitle\StorePageTitleRequest;
use App\Http\Requests\Backend\PageTitle\UpdatePageTitleRequest;
use App\Models\PageTitle;
use App\Services\PageTitleService;
use Illuminate\Support\Facades\Cache;

class PageTitleController extends Controller
{
    protected $pageTitleService;

    public function __construct(PageTitleService $pageTitleService)
    {
        $this->pageTitleService = $pageTitleService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $pageTitles = PageTitle::latestId()->get();

        return view('backend.page-title.index', [
            'pageTitles' => $pageTitles
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.page-title.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StorePageTitleRequest $request)
    {
        try {
            $this->pageTitleService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('page_title');

            return redirect()->route('page-titles.index')
                ->with('message', 'Page title created successfully');

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
        $pageTitle = PageTitle::findOrFail($id);

        return view('backend.page-title.edit', [
            'pageTitle' => $pageTitle
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdatePageTitleRequest $request, string $id)
    {
        try {
            $pageTitle = PageTitle::findOrFail($id);

            $this->pageTitleService->update($pageTitle, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('page_title');

            return redirect()->route('page-titles.index')
                ->with('message', 'Page title updated successfully');

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
            $pageTitle = PageTitle::findOrFail($id);

            $this->pageTitleService->delete($pageTitle);

            // ✅ CLEAR CACHE
            Cache::forget('page_title');

            return redirect()->route('page-titles.index')
                ->with('message', 'Page title deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}