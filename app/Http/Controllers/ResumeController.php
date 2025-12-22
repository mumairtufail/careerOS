<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Services\ResumeParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\HasModuleLogger;

class ResumeController extends Controller
{
    use AuthorizesRequests, HasModuleLogger;

    protected $logChannel = 'resumes';
    protected $parser;

    public function __construct(ResumeParserService $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resumes = auth()->user()->resumes()->latest()->get();
        return view('resumes.index', compact('resumes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resumes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:2048',
        ]);

        $file = $request->file('resume');
        $path = $file->store('resumes', 'public');

        $this->logInfo('Resume file uploaded', ['path' => $path]);

        $parsedData = $this->parser->parse($file);

        $resume = auth()->user()->resumes()->create([
            'title' => $request->title,
            'file_path' => $path,
            'parsed_content' => $parsedData['parsed_content'],
            'summary' => $parsedData['summary'] ?? null,
            'skills' => $parsedData['skills'] ?? [],
            'experience' => $parsedData['experience'] ?? [],
            'years_of_experience' => $parsedData['years_of_experience'] ?? null,
            'education' => $parsedData['education'] ?? [],
            'projects' => $parsedData['projects'] ?? [],
            'certifications' => $parsedData['certifications'] ?? [],
            'parse_status' => $parsedData['ai_extraction_failed'] ? 'failed' : 'success',
            'parse_error' => $parsedData['ai_extraction_failed'] ? $parsedData['ai_extraction_error'] : null,
        ]);

        $message = 'Resume uploaded successfully.';
        if ($parsedData['ai_extraction_failed']) {
            $this->logError('AI extraction failed', ['error' => $parsedData['ai_extraction_error'], 'resume_id' => $resume->id]);
            $message .= ' However, AI extraction failed: ' . $parsedData['ai_extraction_error'];
            return redirect()->route('resumes.show', $resume)->with('warning', $message);
        }

        $this->logInfo('Resume parsed successfully', ['resume_id' => $resume->id]);

        return redirect()->route('resumes.show', $resume)
            ->with('success', 'Resume uploaded and parsed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resume $resume)
    {
        $this->authorize('view', $resume);
        return view('resumes.show', compact('resume'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resume $resume)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resume $resume)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resume $resume)
    {
        $this->authorize('delete', $resume);
        
        if (Storage::disk('public')->exists($resume->file_path)) {
            Storage::disk('public')->delete($resume->file_path);
        }
        
        $resume->delete();

        $this->logInfo('Resume deleted', ['resume_id' => $resume->id, 'title' => $resume->title]);

        return redirect()->route('resumes.index')
            ->with('success', 'Resume deleted successfully.');
    }

    /**
     * Bulk delete resumes
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:resumes,id',
        ]);

        $resumes = Resume::whereIn('id', $request->ids)
            ->where('user_id', auth()->id())
            ->get();

        if ($resumes->isEmpty()) {
            return redirect()->route('resumes.index')
                ->with('error', 'No resumes found to delete.');
        }

        $count = 0;
        foreach ($resumes as $resume) {
            if (Storage::disk('public')->exists($resume->file_path)) {
                Storage::disk('public')->delete($resume->file_path);
            }
            $resume->delete();
            $count++;
        }

        $this->logInfo('Bulk resumes deleted', ['count' => $count, 'ids' => $request->ids]);

        return redirect()->route('resumes.index')
            ->with('success', "{$count} resume(s) deleted successfully.");
    }

    /**
     * Re-parse an existing resume.
     */
    public function reParse(Resume $resume)
    {
        // Ensure the user owns this resume
        if ($resume->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // Get the file from storage
            $filePath = storage_path('app/public/' . $resume->file_path);
            
            if (!file_exists($filePath)) {
                $this->logError('Resume file not found for re-parsing', ['resume_id' => $resume->id, 'path' => $filePath]);
                return back()->with('error', 'Resume file not found. Cannot re-parse.');
            }

            // Create UploadedFile instance
            $file = new \Illuminate\Http\UploadedFile(
                $filePath,
                basename($filePath),
                mime_content_type($filePath),
                null,
                true
            );

            $this->logInfo('Re-parsing resume', ['resume_id' => $resume->id]);

            // Parse the resume
            $parsedData = $this->parser->parse($file);

            // Update the resume with new parsed data
            $resume->update([
                'parsed_content' => $parsedData['parsed_content'],
                'summary' => $parsedData['summary'] ?? null,
                'skills' => $parsedData['skills'] ?? [],
                'experience' => $parsedData['experience'] ?? [],
                'years_of_experience' => $parsedData['years_of_experience'] ?? null,
                'education' => $parsedData['education'] ?? [],
                'projects' => $parsedData['projects'] ?? [],
                'certifications' => $parsedData['certifications'] ?? [],
                'parse_status' => $parsedData['ai_extraction_failed'] ? 'failed' : 'success',
                'parse_error' => $parsedData['ai_extraction_failed'] ? $parsedData['ai_extraction_error'] : null,
            ]);

            $message = 'Resume re-parsed successfully.';
            if ($parsedData['ai_extraction_failed']) {
                $this->logError('AI re-extraction failed', ['error' => $parsedData['ai_extraction_error'], 'resume_id' => $resume->id]);
                $message .= ' However, AI extraction failed: ' . $parsedData['ai_extraction_error'];
                return back()->with('warning', $message);
            }

            $this->logInfo('Resume re-parsed successfully', ['resume_id' => $resume->id]);

            return back()->with('success', $message);

        } catch (\Exception $e) {
            $this->logError('Re-parsing failed', ['resume_id' => $resume->id, 'error' => $e->getMessage()]);
            
            $resume->update([
                'parse_status' => 'failed',
                'parse_error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Re-parsing failed: ' . $e->getMessage());
        }
    }
}
