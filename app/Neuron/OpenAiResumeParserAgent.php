<?php

declare(strict_types=1);

namespace App\Neuron;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\OpenAI;
use GuzzleHttp\Client;

class OpenAiResumeParserAgent extends Agent
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
        $provider = new OpenAI(
            key: $this->apiKey,
            model: $this->model ?? 'gpt-4-turbo',
        );
        
        // Create custom Guzzle client with proper base URI and SSL verification disabled
        $customClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'verify' => false,
            'timeout' => 120,
        ]);
        
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
