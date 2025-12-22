<?php

declare(strict_types=1);

namespace App\Neuron;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Gemini\Gemini;
use GuzzleHttp\Client;

class ResumeParserAgent extends Agent
{
    protected string $apiKey;
    protected ?string $model;

    public static function create(string $apiKey, ?string $model = null): self
    {
        $agent = new self();
        $agent->apiKey = $apiKey;
        $agent->model = $model;
        return $agent;
    }

    protected function provider(): AIProviderInterface
    {
        // Use the correct model name format for Gemini API
        // Note: Some versions use 'gemini-1.5-flash' while others need 'models/gemini-1.5-flash'
        $modelName = $this->model ?? 'gemini-1.5-flash-latest';
        
        // Create Gemini provider
        $provider = new Gemini(
            key: $this->apiKey,
            model: $modelName,
        );

        // Create custom Guzzle client with SSL verification disabled for local development
        $customClient = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $this->apiKey,
            ],
            'timeout' => 120,
            'verify' => false, // Disable SSL verification for Laragon/local development
        ]);

        // Set the custom client on the provider
        $provider->setClient($customClient);

        return $provider;
    }

    public function instructions(): string
    {
        return (string) new SystemPrompt(
            background: [
                "You are an expert resume parser and analyzer.",
                "Your task is to extract structured information from resume text.",
                "You must always return valid JSON format."
            ],
            steps: [
                "Carefully read and analyze the provided resume text.",
                "Extract key information including personal details, skills, work experience, education, projects, and certifications.",
                "Calculate the total years of professional experience.",
                "Structure all information in the required JSON format."
            ],
            output: [
                "Return ONLY a valid JSON object, no markdown code blocks or extra text.",
                "Use the exact structure with these keys: summary, skills, experience, years_of_experience, education, projects, certifications.",
                "For 'summary': Write a brief professional summary (2-3 sentences).",
                "For 'skills': Array of skill strings.",
                "For 'experience': Array of objects with 'company', 'role', 'duration', 'description'.",
                "For 'years_of_experience': Integer representing total years.",
                "For 'education': Array of objects with 'institution', 'degree', 'year'.",
                "For 'projects': Array of objects with 'name', 'description', 'technologies'.",
                "For 'certifications': Array of objects with 'name', 'issuer', 'year'.",
            ]
        );
    }
}
