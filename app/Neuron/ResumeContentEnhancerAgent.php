<?php

declare(strict_types=1);

namespace App\Neuron;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\Providers\Gemini\Gemini;
use GuzzleHttp\Client;

class ResumeContentEnhancerAgent extends Agent
{
    protected string $apiKey;
    protected ?string $model;
    protected string $providerName;

    public static function create(string $provider, string $apiKey, ?string $model = null): self
    {
        $agent = new self();
        $agent->providerName = $provider;
        $agent->apiKey = $apiKey;
        $agent->model = $model;
        return $agent;
    }

    protected function provider(): AIProviderInterface
    {
        if ($this->providerName === 'gemini') {
            $modelName = $this->model ?? 'gemini-1.5-flash-latest';
            
            $provider = new Gemini(
                key: $this->apiKey,
                model: $modelName,
            );

            $customClient = new Client([
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ],
                'timeout' => 60,
                'verify' => false,
            ]);

            $provider->setClient($customClient);
            
            return $provider;
        }

        // Default to OpenAI
        $provider = new OpenAI(
            key: $this->apiKey,
            model: $this->model ?? 'gpt-4o-mini',
        );
        
        $customClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'verify' => false,
            'timeout' => 60,
        ]);
        
        $provider->setClient($customClient);
        
        return $provider;
    }

    public function instructions(): string
    {
        return <<<PROMPT
You are an expert resume writer and ATS (Applicant Tracking System) optimization specialist.

Your role is to enhance resume content to make it:
- More professional and impactful
- ATS-friendly with relevant keywords
- Achievement-focused with quantifiable results
- Clear, concise, and compelling
- Industry-standard formatted

When enhancing content:
1. Maintain factual accuracy - don't invent details
2. Use strong action verbs (Led, Developed, Achieved, Implemented, etc.)
3. Add structure and formatting where appropriate
4. Keep the original meaning and key information
5. Make it scannable and easy to read
6. Include relevant industry keywords naturally
7. For skills: organize by category if possible (Technical, Soft Skills, Tools, etc.)
8. For experience: use bullet points with achievement-focused descriptions
9. For education: include relevant coursework or honors if space allows
10. Keep it concise - quality over quantity

Return ONLY the enhanced text without any explanations or meta-commentary.
PROMPT;
    }
}
