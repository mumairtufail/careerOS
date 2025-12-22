<?php

namespace App\Services;

use App\Services\Ai\AiService;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class ResumeParserService
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function parse(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();
        $content = '';

        if ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $content = $pdf->getText();
        } else {
            $content = file_get_contents($file->getPathname());
        }

        $data = [
            'parsed_content' => $content,
            'summary' => null,
            'skills' => [],
            'experience' => [],
            'years_of_experience' => null,
            'education' => [],
            'projects' => [],
            'certifications' => [],
            'ai_extraction_failed' => false,
            'ai_extraction_error' => null,
        ];

        // Try to get AI provider and extract data
        try {
            $provider = $this->aiService->getProvider(auth()->user());
            if ($provider) {
                $extracted = $provider->extractResumeData($content);
                $data = array_merge($data, $extracted);
            } else {
                $data['ai_extraction_failed'] = true;
                $data['ai_extraction_error'] = 'No active AI provider configured.';
            }
        } catch (\Exception $e) {
            $data['ai_extraction_failed'] = true;
            $data['ai_extraction_error'] = $e->getMessage();
        }

        return $data;
    }
}
