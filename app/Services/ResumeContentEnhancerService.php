<?php

namespace App\Services;

use App\Neuron\ResumeContentEnhancerAgent;
use App\Models\AiConfiguration;
use App\Traits\HasModuleLogger;
use NeuronAI\Chat\Messages\UserMessage;

class ResumeContentEnhancerService
{
    use HasModuleLogger;

    protected $logChannel = 'resumes';

    /**
     * Enhance resume section content using AI.
     */
    public function enhanceContent(string $content, string $section, ?int $userId = null): array
    {
        try {
            $userId = $userId ?? auth()->id();
            
            // Get active AI configuration
            $aiConfig = AiConfiguration::where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$aiConfig) {
                $this->logError('No active AI configuration found', ['user_id' => $userId]);
                return [
                    'success' => false,
                    'error' => 'No active AI configuration found. Please configure AI settings first.',
                ];
            }

            $this->logInfo('Creating AI agent for content enhancement', [
                'provider' => $aiConfig->provider,
                'model' => $aiConfig->model,
                'section' => $section
            ]);

            // Create agent
            $agent = ResumeContentEnhancerAgent::create(
                $aiConfig->provider,
                $aiConfig->api_key,
                $aiConfig->model
            );

            // Create section-specific prompt
            $prompt = $this->buildPrompt($content, $section);

            $this->logInfo('Enhancing resume content', [
                'section' => $section,
                'provider' => $aiConfig->provider,
                'content_length' => strlen($content)
            ]);

            // Get AI response using chat method
            $response = $agent->chat(new UserMessage($prompt));
            $enhanced = trim($response->content);

            $this->logInfo('Content enhanced successfully', [
                'section' => $section,
                'original_length' => strlen($content),
                'enhanced_length' => strlen($enhanced)
            ]);

            return [
                'success' => true,
                'enhanced' => $enhanced,
                'original' => $content,
            ];

        } catch (\Exception $e) {
            $this->logError('Failed to enhance content', [
                'section' => $section,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to enhance content: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build section-specific enhancement prompt.
     */
    private function buildPrompt(string $content, string $section): string
    {
        $sectionInstructions = match($section) {
            'professional_summary' => 'Enhance this professional summary to be compelling and highlight key strengths. Keep it to 3-4 sentences.',
            'skills' => 'Organize and enhance this skills list. Group by category (Technical Skills, Soft Skills, Tools & Technologies, etc.) and format as a clean, scannable list.',
            'experience' => 'Enhance this work experience section. Format each position with: Company, Title, Dates, and 3-5 achievement-focused bullet points with quantifiable results where possible.',
            'education' => 'Enhance this education section. Format clearly with: Institution, Degree, Dates, GPA (if strong), relevant coursework or honors.',
            'certifications' => 'Format this certifications list professionally. Include: Certification Name, Issuing Organization, Date (if available).',
            'projects' => 'Enhance this projects section. For each project: Title, brief description, technologies used, and key achievements or results.',
            default => 'Enhance this resume section to be more professional, ATS-friendly, and impactful.',
        };

        return <<<PROMPT
Section: {$section}

Original Content:
{$content}

Instructions: {$sectionInstructions}

Enhanced Version:
PROMPT;
    }
}
