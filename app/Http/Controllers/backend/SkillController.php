<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Skill\StoreSkillRequest;
use App\Http\Requests\Backend\Skill\UpdateSkillRequest;
use App\Models\Skill;
use App\Services\SkillService;
use Illuminate\Support\Facades\Cache;

class SkillController extends Controller
{
    protected $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $skills = Skill::active()->latest()->get();

        return view('backend.skills.index', [
            'skills' => $skills
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.skills.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreSkillRequest $request)
    {
        try {
            $this->skillService->store($request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('skills');

            return redirect()->route('skills.index')
                ->with('message', 'Skill created successfully');

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
        $skill = Skill::findOrFail($id);

        return view('backend.skills.edit', [
            'skill' => $skill
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(UpdateSkillRequest $request, string $id)
    {
        try {
            $skill = Skill::findOrFail($id);

            $this->skillService->update($skill, $request->validated());

            // ✅ CLEAR CACHE
            Cache::forget('skills');

            return redirect()->route('skills.index')
                ->with('message', 'Skill updated successfully');

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
            $skill = Skill::findOrFail($id);

            $this->skillService->delete($skill);

            // ✅ CLEAR CACHE
            Cache::forget('skills');

            return redirect()->route('skills.index')
                ->with('message', 'Skill deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }
}