<?php

namespace App\Services\Ai;

use App\Neuron\ResumeParserAgent;
use NeuronAI\Chat\Messages\UserMessage;

class GeminiProvider implements AiProviderInterface
{
    protected $agent;

    public function __construct(string $apiKey, ?string $model = null)
    {
        $this->agent = ResumeParserAgent::create(
            apiKey: $apiKey,
            model: $model ?? 'gemini-1.5-flash'
        );
    }

    public function extractResumeData(string $text): array
    {
        try {
            $prompt = "Extract the information from the following resume text and return it as JSON:\n\n{$text}";
            
            $response = $this->agent->chat(
                new UserMessage($prompt)
            );

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
