<?php

namespace App\Services\Ai;

use App\Models\User;
use Exception;

class AiService
{
    public function getProvider(User $user): ?AiProviderInterface
    {
        $config = $user->aiConfigurations()->where('is_active', true)->first();

        if (!$config) {
            return null;
        }

        switch ($config->provider) {
            case 'openai':
                return new OpenAiProvider($config->api_key, $config->model);
            case 'gemini':
                return new GeminiProvider($config->api_key, $config->model);
            default:
                throw new Exception("Provider {$config->provider} not supported.");
        }
    }
}
