<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Services\ResumeParserService;
use App\Services\ResumeContentEnhancerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\HasModuleLogger;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    use AuthorizesRequests, HasModuleLogger;

    protected $logChannel = 'resumes';
    protected $parser;
    protected $enhancer;

    public function __construct(ResumeParserService $parser, ResumeContentEnhancerService $enhancer)
    {
        $this->parser = $parser;
        $this->enhancer = $enhancer;
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

    /**
     * Store resume created from builder.
     */
    public function storeFromBuilder(Request $request)
    {
        $request->validate([
            'data' => 'required|json',
            'action' => 'required|in:save_draft,download_pdf'
        ]);

        $data = json_decode($request->data, true);

        // Validate essential fields
        if (!isset($data['template']) || !isset($data['personal'])) {
            return response()->json(['error' => 'Missing required data'], 422);
        }

        $personal = $data['personal'];

        try {
            // Generate PDF
            $pdfPath = null;
            $shouldGeneratePdf = $request->action === 'download_pdf';

            if ($shouldGeneratePdf) {
                $template = $data['template'] ?? 'professional';
                
                // Ensure template exists, fallback if needed
                if (!view()->exists("resumes.templates.{$template}")) {
                    $template = 'minimal';
                }

                $pdf = Pdf::loadView("resumes.templates.{$template}", ['data' => $data]);
                
                // Save PDF to storage (Public disk to match other parts of the system)
                $fileName = 'resume_' . auth()->id() . '_' . time() . '.pdf';
                $pdfPath = 'resumes/' . $fileName;
                Storage::disk('public')->put($pdfPath, $pdf->output());
            }

            // Parse skills from text
            $skills = [];
            if (!empty($data['skills_text'])) {
                $skills = array_map('trim', preg_split('/[,\n]+/', $data['skills_text']));
                $skills = array_filter($skills);
            }

            // Calculate years of experience from experience array
            $yearsOfExperience = 0;
            if (!empty($data['experience'])) {
                foreach ($data['experience'] as $exp) {
                    if (!empty($exp['start_year'])) {
                        $endYear = $exp['currently_working'] ? date('Y') : ($exp['end_year'] ?? date('Y'));
                        $yearsOfExperience += max(0, $endYear - $exp['start_year']);
                    }
                }
            }

            // Create resume record
            $resume = auth()->user()->resumes()->create([
                'title' => $personal['full_name'] . ' - ' . ($personal['professional_title'] ?? 'Resume'),
                'file_path' => $pdfPath,
                'parsed_content' => json_encode($data),
                'summary' => $personal['professional_summary'] ?? null,
                'skills' => $skills,
                'experience' => $data['experience'] ?? [],
                'years_of_experience' => $yearsOfExperience,
                'education' => $data['education'] ?? [],
                'projects' => [], // Store text-based projects
                'certifications' => [], // Store text-based certifications
                'parse_status' => $request->action === 'save_draft' ? 'pending' : 'success',
                'parse_error' => null,
                'source' => 'builder',
            ]);

            $this->logInfo('Resume created from builder', [
                'resume_id' => $resume->id,
                'action' => $request->action,
                'template' => $data['template']
            ]);

            return response()->json([
                'success' => true,
                'resume_id' => $resume->id,
                'message' => $request->action === 'save_draft' 
                    ? 'Resume draft saved successfully.' 
                    : 'Resume created successfully.'
            ]);

        } catch (\Exception $e) {
            $this->logError('Resume builder submission failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return response()->json([
                'error' => 'Failed to create resume: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate total years of experience from experience array.
     */
    private function calculateYearsOfExperience(array $experiences): ?int
    {
        if (empty($experiences)) {
            return null;
        }

        $totalMonths = 0;
        foreach ($experiences as $exp) {
            if (isset($exp['start_date']) && isset($exp['end_date'])) {
                try {
                    $start = new \DateTime($exp['start_date']);
                    $end = $exp['currently_working'] ?? false 
                        ? new \DateTime() 
                        : new \DateTime($exp['end_date']);
                    $diff = $start->diff($end);
                    $totalMonths += ($diff->y * 12) + $diff->m;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return (int) round($totalMonths / 12);
    }

    /**
     * Download resume PDF.
     */
    public function download(Resume $resume)
    {
        $this->authorize('view', $resume);

        if (!$resume->file_path) {
            return back()->with('error', 'Resume file path missing.');
        }

        // Check Public disk first (standard storage)
        if (Storage::disk('public')->exists($resume->file_path)) {
             return Storage::disk('public')->download(
                $resume->file_path,
                ($resume->title ?? 'resume') . '.pdf'
            );
        }

        // Check Local disk (legacy/fallback)
        if (Storage::disk('local')->exists($resume->file_path)) {
            return Storage::disk('local')->download(
                $resume->file_path,
                ($resume->title ?? 'resume') . '.pdf'
            );
        }

        return back()->with('error', 'Resume file not found.');
    }

    /**
     * Regenerate PDF from builder data.
     */
    public function regenerate(Resume $resume)
    {
        $this->authorize('update', $resume);

        if ($resume->source !== 'builder') {
            return back()->with('error', 'Only builder resumes can be regenerated.');
        }

        try {
            $data = json_decode($resume->parsed_content, true);
            $template = $data['template'] ?? 'professional';
            
             // Ensure template exists, fallback if needed
            if (!view()->exists("resumes.templates.{$template}")) {
                $template = 'minimal';
            }

            $pdf = Pdf::loadView("resumes.templates.{$template}", ['data' => $data]);
            
            // Delete old PDF if exists (check both disks)
            if ($resume->file_path) {
                if (Storage::disk('public')->exists($resume->file_path)) {
                    Storage::disk('public')->delete($resume->file_path);
                } elseif (Storage::disk('local')->exists($resume->file_path)) {
                    Storage::disk('local')->delete($resume->file_path);
                }
            }
            
            // Save new PDF to public disk
            $fileName = 'resume_' . auth()->id() . '_' . time() . '.pdf';
            $pdfPath = 'resumes/' . $fileName;
            Storage::disk('public')->put($pdfPath, $pdf->output());
            
            $resume->update(['file_path' => $pdfPath]);

            $this->logInfo('Resume regenerated', ['resume_id' => $resume->id]);

            return back()->with('success', 'Resume regenerated successfully!');
        } catch (\Exception $e) {
            $this->logError('Failed to regenerate resume', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to regenerate resume.');
        }
    }

    /**
     * Enhance resume content using AI.
     */
    public function enhanceContent(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'section' => 'required|string|in:professional_summary,skills,experience,education,certifications,projects'
        ]);

        $result = $this->enhancer->enhanceContent(
            $request->content,
            $request->section
        );

        return response()->json($result);
    }

    /**
     * Generate HTML preview for resume builder.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'data' => 'required|json',
        ]);

        $data = json_decode($request->data, true);
        $template = $data['template'] ?? 'professional';

        try {
            return view("resumes.templates.{$template}", ['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Template not found'], 404);
        }
    }
}
