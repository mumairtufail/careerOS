<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::query()->latest();

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $logs = $query->paginate(20)->withQueryString();
        $channels = SystemLog::distinct('channel')->pluck('channel');
        $levels = SystemLog::distinct('level')->pluck('level');

        return view('logs.index', compact('logs', 'channels', 'levels'));
    }
}
