<?php

namespace App\Logging;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        SystemLog::create([
            'channel' => $record->channel,
            'level' => $record->level->name,
            'message' => $record->message,
            'context' => $record->context,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
