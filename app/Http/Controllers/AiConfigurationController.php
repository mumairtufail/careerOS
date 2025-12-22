<?php

namespace App\Http\Controllers;

use App\Models\AiConfiguration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiConfigurationController extends Controller
{
    public function index()
    {
        $configurations = auth()->user()->aiConfigurations()->latest()->get();
        return view('ai-configurations.index', compact('configurations'));
    }

    public function create()
    {
        return view('ai-configurations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider' => ['required', 'string', 'max:255'],
            'api_key' => 'nullable|string',
            'model' => 'nullable|string',
        ]);

        $config = auth()->user()->aiConfigurations()->create([
            'provider' => $request->provider,
            'api_key' => $request->api_key,
            'model' => $request->model,
            'is_active' => false,
        ]);

        // If this is the first config, make it active
        if (auth()->user()->aiConfigurations()->count() === 1) {
            $config->update(['is_active' => true]);
        }

        return redirect()->route('ai-configurations.index')->with('success', 'Configuration created successfully.');
    }

    public function edit(AiConfiguration $aiConfiguration)
    {
        if ($aiConfiguration->user_id !== auth()->id()) {
            abort(403);
        }
        return view('ai-configurations.edit', compact('aiConfiguration'));
    }

    public function update(Request $request, AiConfiguration $aiConfiguration)
    {
        if ($aiConfiguration->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'provider' => ['required', 'string', 'max:255'],
            'api_key' => 'nullable|string',
            'model' => 'nullable|string',
        ]);

        $aiConfiguration->update([
            'provider' => $request->provider,
            'api_key' => $request->api_key,
            'model' => $request->model,
        ]);

        return redirect()->route('ai-configurations.index')->with('success', 'Configuration updated successfully.');
    }

    public function destroy(AiConfiguration $aiConfiguration)
    {
        if ($aiConfiguration->user_id !== auth()->id()) {
            abort(403);
        }

        $aiConfiguration->delete();

        return redirect()->route('ai-configurations.index')->with('success', 'Configuration deleted successfully.');
    }

    public function activate(Request $request, AiConfiguration $aiConfiguration)
    {
        if ($aiConfiguration->user_id !== auth()->id()) {
            abort(403);
        }

        // Deactivate all others
        auth()->user()->aiConfigurations()->update(['is_active' => false]);
        
        // Activate this one
        $aiConfiguration->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Provider activated.');
    }

    /**
     * Fetch available models from the AI provider
     */
    public function fetchModels(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'api_key' => 'required|string',
        ]);

        try {
            if ($request->provider === 'gemini') {
                return $this->fetchGeminiModels($request->api_key);
            }

            return response()->json([
                'success' => false,
                'message' => 'Model fetching not supported for this provider yet.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch models: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Fetch available Gemini models from Google API
     */
    private function fetchGeminiModels(string $apiKey)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification for local development
            'timeout' => 30,
        ]);

        try {
            $response = $client->get('https://generativelanguage.googleapis.com/v1beta/models', [
                'headers' => [
                    'x-goog-api-key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (!isset($data['models'])) {
                throw new \Exception('Invalid response from Gemini API');
            }

            // Filter models that support generateContent
            $models = collect($data['models'])
                ->filter(function ($model) {
                    return isset($model['supportedGenerationMethods']) 
                        && in_array('generateContent', $model['supportedGenerationMethods']);
                })
                ->map(function ($model) {
                    return [
                        'name' => str_replace('models/', '', $model['name']),
                        'displayName' => $model['displayName'] ?? $model['name'],
                        'description' => $model['description'] ?? '',
                    ];
                })
                ->values()
                ->toArray();

            return response()->json([
                'success' => true,
                'models' => $models,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody()->getContents(), true);
            
            return response()->json([
                'success' => false,
                'message' => $body['error']['message'] ?? 'Invalid API key or request failed',
            ], 400);
        }
    }
}
