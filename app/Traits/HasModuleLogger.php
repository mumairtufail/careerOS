<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasModuleLogger
{
    /**
     * Get the log channel for the module.
     * Defaults to 'system' if not defined in the class.
     */
    protected function getLogChannel(): string
    {
        return property_exists($this, 'logChannel') ? $this->logChannel : 'system';
    }

    /**
     * Log an info message.
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::channel($this->getLogChannel())->info($message, $context);
    }

    /**
     * Log an error message.
     */
    protected function logError(string $message, array $context = []): void
    {
        Log::channel($this->getLogChannel())->error($message, $context);
    }

    /**
     * Log a warning message.
     */
    protected function logWarning(string $message, array $context = []): void
    {
        Log::channel($this->getLogChannel())->warning($message, $context);
    }
}
