<?php

namespace App\Services\Ai;

use App\Neuron\OpenAiResumeParserAgent;
use NeuronAI\Chat\Messages\UserMessage;

class OpenAiProvider implements AiProviderInterface
{
    protected OpenAiResumeParserAgent $agent;

    public function __construct(string $apiKey, string $model = 'gpt-4-turbo')
    {
        $this->agent = OpenAiResumeParserAgent::create($apiKey, $model);
    }

    public function extractResumeData(string $text): array
    {
        try {
            $prompt = "Extract information from this resume and return it as JSON with the following structure:
{
    \"summary\": \"Brief professional summary\",
    \"skills\": [\"skill1\", \"skill2\"],
    \"experience\": [
        {
            \"company\": \"Company Name\",
            \"role\": \"Job Title\",
            \"duration\": \"Date Range\",
            \"description\": \"Job description\"
        }
    ],
    \"years_of_experience\": 0,
    \"education\": [
        {
            \"institution\": \"University Name\",
            \"degree\": \"Degree Name\",
            \"year\": \"Year\"
        }
    ],
    \"projects\": [
        {
            \"name\": \"Project Name\",
            \"description\": \"Project description\",
            \"technologies\": [\"tech1\", \"tech2\"]
        }
    ],
    \"certifications\": [
        {
            \"name\": \"Certification Name\",
            \"issuer\": \"Issuing Organization\",
            \"year\": \"Year\"
        }
    ]
}

Resume text:
{$text}

Return ONLY the JSON, no markdown code blocks or additional text.";

            $response = $this->agent->chat(new UserMessage($prompt));
            $content = $response->getContent();
            
            // Clean up markdown code blocks if present
            $content = trim($content);
            $content = preg_replace('/^```json\s*/i', '', $content);
            $content = preg_replace('/```\s*$/i', '', $content);
            $content = trim($content);
            
            $data = json_decode($content, true);
            
            if (!is_array($data)) {
                throw new \Exception('Invalid JSON response from AI');
            }
            
            return $data;
        } catch (\Exception $e) {
            throw new \Exception("AI extraction failed: " . $e->getMessage());
        }
    }
}
