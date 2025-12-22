<?php

namespace App\Http\Controllers;

use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = JobStage::where('user_id', auth()->id())
            ->orWhere('is_system_default', true)
            ->orderBy('sort_order')
            ->get();

        return view('job-stages.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job-stages.form', ['isEdit' => false]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        JobStage::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'sort_order' => $validated['sort_order'],
            'is_system_default' => false,
        ]);

        return redirect()->route('job-stages.index')
            ->with('success', 'Job stage created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobStage $jobStage)
    {
        // Prevent editing system defaults
        if ($jobStage->is_system_default) {
            return redirect()->route('job-stages.index')
                ->with('error', 'System default stages cannot be edited.');
        }

        // Authorization check
        if ($jobStage->user_id !== auth()->id()) {
            abort(403);
        }

        return view('job-stages.form', [
            'isEdit' => true,
            'jobStage' => $jobStage
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobStage $jobStage)
    {
        if ($jobStage->is_system_default) {
            return redirect()->route('job-stages.index')
                ->with('error', 'System default stages cannot be edited.');
        }

        if ($jobStage->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        $jobStage->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()->route('job-stages.index')
            ->with('success', 'Job stage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobStage $jobStage)
    {
        if ($jobStage->is_system_default) {
            return redirect()->route('job-stages.index')
                ->with('error', 'System default stages cannot be deleted.');
        }

        if ($jobStage->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if stage has applications
        if ($jobStage->applications()->count() > 0) {
            return redirect()->route('job-stages.index')
                ->with('error', 'Cannot delete stage with active applications.');
        }

        $jobStage->delete();

        return redirect()->route('job-stages.index')
            ->with('success', 'Job stage deleted successfully.');
    }
}
