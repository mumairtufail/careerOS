<?php

declare(strict_types=1);

namespace App\Neuron;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Gemini\Gemini;
use GuzzleHttp\Client;

class ResumeJobMatchingAgent extends Agent
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
        $provider = new Gemini(
            key: $this->apiKey,
            model: $this->model ?? 'gemini-1.5-flash-latest',
        );
        
        // SSL bypass for local development
        $customClient = new Client([
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
                "You are an expert career coach and resume analyst.",
                "Your task is to evaluate how well a resume matches a specific job description.",
                "You must provide actionable, specific, and honest feedback.",
                "Focus on practical insights that help the candidate improve their application."
            ],
            steps: [
                "Carefully analyze the resume's skills, experience, education, and achievements.",
                "Compare them against the job description's requirements and preferences.",
                "Identify specific strengths where the candidate excels.",
                "Identify specific gaps or missing elements.",
                "Calculate an honest match score from 0-100.",
                "Provide constructive feedback on how to improve the match."
            ],
            output: [
                "Return ONLY a valid JSON object, no markdown code blocks or extra text.",
                "Use this exact structure:",
                "{",
                '  "match_score": 75,',
                '  "strengths": ["Strength 1", "Strength 2", "Strength 3"],',
                '  "gaps": ["Gap 1", "Gap 2", "Gap 3"],',
                '  "ai_feedback": "Detailed paragraph explaining the analysis and recommendations"',
                "}",
                "",
                "For 'match_score': Integer from 0-100 based on overall fit.",
                "For 'strengths': Array of 3-5 specific positive matches (skills, experience, achievements).",
                "For 'gaps': Array of 2-4 missing requirements or weak areas.",
                "For 'ai_feedback': A comprehensive paragraph (100-200 words) with:",
                "  - Overall assessment of the match",
                "  - Why the score is what it is",
                "  - Top 2-3 recommendations for improvement",
                "  - Encouragement if applicable",
                "",
                "Be honest but constructive. Focus on actionable insights."
            ]
        );
    }
}
