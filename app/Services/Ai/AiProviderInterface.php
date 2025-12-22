<?php

namespace App\Services\Ai;

interface AiProviderInterface
{
    public function extractResumeData(string $text): array;
}
