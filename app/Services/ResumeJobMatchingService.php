<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\JobApplication;
use App\Models\ResumeJobMatch;
use App\Models\AiConfiguration;
use App\Neuron\ResumeJobMatchingAgent;
use App\Neuron\OpenAiResumeJobMatchingAgent;
use NeuronAI\Chat\Messages\UserMessage;
use App\Traits\HasModuleLogger;
use Exception;

class ResumeJobMatchingService
{
    use HasModuleLogger;

    protected string $logModule = 'ResumeJobMatching';

    /**
     * Analyze how well a resume matches a job application
     */
    public function analyzeMatch(Resume $resume, JobApplication $jobApplication, bool $forceReanalyze = false): ResumeJobMatch
    {
        // Check if analysis already exists
        if (!$forceReanalyze) {
            $existing = ResumeJobMatch::where('resume_id', $resume->id)
                ->where('job_application_id', $jobApplication->id)
                ->first();
            
            if ($existing) {
                $this->logInfo('Using existing match analysis', [
                    'resume_id' => $resume->id,
                    'job_application_id' => $jobApplication->id,
                    'match_score' => $existing->match_score
                ]);
                return $existing;
            }
        }

        // Validate inputs
        if (!$resume->isParsed()) {
            throw new Exception('Resume must be successfully parsed before matching.');
        }

        if (empty($jobApplication->job_description)) {
            throw new Exception('Job description is required for matching analysis.');
        }

        $this->logInfo('Starting match analysis', [
            'resume_id' => $resume->id,
            'resume_title' => $resume->title,
            'job_application_id' => $jobApplication->id,
            'job_title' => $jobApplication->job_title
        ]);

        try {
            // Get active AI configuration
            $activeConfig = AiConfiguration::where('is_active', true)->first();
            
            if (!$activeConfig) {
                throw new Exception('No active AI configuration found. Please configure an AI provider.');
            }

            // Prepare resume data summary
            $resumeData = $this->prepareResumeData($resume);
            
            // Prepare prompt
            $prompt = $this->buildPrompt($resumeData, $jobApplication->job_description, $jobApplication->job_title);

            // Call AI
            $result = $this->callAI($activeConfig, $prompt);

            // Store results
            $match = ResumeJobMatch::updateOrCreate(
                [
                    'resume_id' => $resume->id,
                    'job_application_id' => $jobApplication->id,
                ],
                [
                    'match_score' => $result['match_score'] ?? 0,
                    'strengths' => $result['strengths'] ?? [],
                    'gaps' => $result['gaps'] ?? [],
                    'ai_feedback' => $result['ai_feedback'] ?? 'No feedback provided.',
                    'ai_provider' => $activeConfig->provider,
                ]
            );

            $this->logInfo('Match analysis completed', [
                'match_id' => $match->id,
                'match_score' => $match->match_score,
                'category' => $match->getScoreCategory()
            ]);

            return $match;

        } catch (Exception $e) {
            $this->logError('Match analysis failed', [
                'error' => $e->getMessage(),
                'resume_id' => $resume->id,
                'job_application_id' => $jobApplication->id
            ]);
            throw $e;
        }
    }

    /**
     * Prepare resume data for AI analysis
     */
    protected function prepareResumeData(Resume $resume): string
    {
        $data = [];

        if ($resume->summary) {
            $data[] = "PROFESSIONAL SUMMARY:\n" . $resume->summary;
        }

        if ($resume->years_of_experience) {
            $data[] = "TOTAL EXPERIENCE: {$resume->years_of_experience} years";
        }

        if (!empty($resume->skills)) {
            $skills = is_array($resume->skills) ? implode(', ', $resume->skills) : $resume->skills;
            $data[] = "SKILLS:\n" . $skills;
        }

        if (!empty($resume->experience)) {
            $expText = "WORK EXPERIENCE:\n";
            foreach ($resume->experience as $exp) {
                $expText .= "- {$exp['role']} at {$exp['company']} ({$exp['duration']})\n";
                if (!empty($exp['description'])) {
                    $expText .= "  " . $exp['description'] . "\n";
                }
            }
            $data[] = $expText;
        }

        if (!empty($resume->education)) {
            $eduText = "EDUCATION:\n";
            foreach ($resume->education as $edu) {
                $eduText .= "- {$edu['degree']} from {$edu['institution']} ({$edu['year']})\n";
            }
            $data[] = $eduText;
        }

        if (!empty($resume->projects)) {
            $projText = "PROJECTS:\n";
            foreach ($resume->projects as $proj) {
                $projText .= "- {$proj['name']}: {$proj['description']}\n";
                if (!empty($proj['technologies'])) {
                    $techs = is_array($proj['technologies']) ? implode(', ', $proj['technologies']) : $proj['technologies'];
                    $projText .= "  Technologies: {$techs}\n";
                }
            }
            $data[] = $projText;
        }

        if (!empty($resume->certifications)) {
            $certText = "CERTIFICATIONS:\n";
            foreach ($resume->certifications as $cert) {
                $certText .= "- {$cert['name']} from {$cert['issuer']} ({$cert['year']})\n";
            }
            $data[] = $certText;
        }

        return implode("\n\n", $data);
    }

    /**
     * Build the AI prompt
     */
    protected function buildPrompt(string $resumeData, string $jobDescription, string $jobTitle): string
    {
        return <<<PROMPT
Analyze how well this resume matches the job posting and provide a detailed assessment.

JOB TITLE: {$jobTitle}

JOB DESCRIPTION:
{$jobDescription}

---

CANDIDATE'S RESUME:
{$resumeData}

---

Provide your analysis in JSON format with:
1. match_score (0-100)
2. strengths (array of specific matching points)
3. gaps (array of missing requirements or weaknesses)
4. ai_feedback (detailed paragraph with recommendations)

Return ONLY the JSON, no markdown or extra text.
PROMPT;
    }

    /**
     * Call AI provider
     */
    protected function callAI(AiConfiguration $config, string $prompt): array
    {
        $this->logInfo('Calling AI provider', [
            'provider' => $config->provider,
            'model' => $config->model
        ]);

        if ($config->provider === 'gemini') {
            $agent = ResumeJobMatchingAgent::create($config->api_key, $config->model);
            $response = $agent->chat(new UserMessage($prompt));
            $content = $response->getContent();
            
            // Clean up markdown code blocks if present
            $content = trim($content);
            $content = preg_replace('/^```json\s*/i', '', $content);
            $content = preg_replace('/```\s*$/i', '', $content);
            $content = trim($content);
            
            $data = json_decode($content, true);
            
            if (!is_array($data)) {
                throw new Exception('Invalid JSON response from AI');
            }
            
            return $data;
        }

        if ($config->provider === 'openai') {
            $agent = OpenAiResumeJobMatchingAgent::create($config->api_key, $config->model);
            $response = $agent->chat(new UserMessage($prompt));
            $content = $response->getContent();
            
            // Clean up markdown code blocks if present
            $content = trim($content);
            $content = preg_replace('/^```json\s*/i', '', $content);
            $content = preg_replace('/```\s*$/i', '', $content);
            $content = trim($content);
            
            $data = json_decode($content, true);
            
            if (!is_array($data)) {
                $this->logError('Invalid JSON response from OpenAI', [
                    'raw_content' => $content
                ]);
                throw new Exception('Invalid JSON response from AI');
            }
            
            return $data;
        }

        throw new Exception("Provider {$config->provider} not yet supported for matching analysis.");
    }
}
