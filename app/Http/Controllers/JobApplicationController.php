<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobStage;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JobApplicationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobApplication::where('user_id', auth()->id())
            ->with('stage');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(10);

        return view('job-applications.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stages = JobStage::orderBy('sort_order')->get();
        return view('job-applications.form', [
            'jobApplication' => new JobApplication(),
            'stages' => $stages,
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobApplicationRequest $request)
    {
        auth()->user()->jobApplications()->create($request->validated());
        return redirect()->route('job-applications.index')->with('success', 'Job application created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);
        return view('job-applications.show', compact('jobApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);
        $stages = JobStage::orderBy('sort_order')->get();
        return view('job-applications.form', [
            'jobApplication' => $jobApplication,
            'stages' => $stages,
            'isEdit' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);
        $jobApplication->update($request->validated());
        return redirect()->route('job-applications.index')->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication)
    {
        $this->authorize('delete', $jobApplication);
        $jobApplication->delete();
        return redirect()->route('job-applications.index')->with('success', 'Job application deleted successfully.');
    }
}
