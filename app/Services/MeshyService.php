<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MeshyService
{
    protected string $baseUrl = 'https://api.meshy.ai/openapi/v2';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.meshy.api_key', '');
    }

    /**
     * Create a 3D model from an image URL (Image to 3D).
     * Returns the task ID for polling.
     */
    public function imageToModel(string $imageUrl): string
    {
        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post("{$this->baseUrl}/image-to-3d", [
                'image_url' => $imageUrl,
                'ai_model' => 'meshy-4',
                'topology' => 'quad',
                'target_polycount' => 30000,
                'should_remesh' => true,
            ]);

        if ($response->failed()) {
            Log::error('Meshy imageToModel failed', ['status' => $response->status(), 'body' => $response->json()]);
            throw new \Exception('Failed to create 3D model: ' . $response->body());
        }

        $data = $response->json();
        return $data['result'] ?? $data['id'] ?? '';
    }

    /**
     * Check the status of a 3D generation task.
     * Returns: { status: 'PENDING|IN_PROGRESS|SUCCEEDED|FAILED', model_urls: { glb, usdz } }
     */
    public function getTaskStatus(string $taskId): array
    {
        $response = Http::withToken($this->apiKey)
            ->timeout(15)
            ->get("{$this->baseUrl}/image-to-3d/{$taskId}");

        if ($response->failed()) {
            Log::error('Meshy getTaskStatus failed', ['task_id' => $taskId, 'status' => $response->status()]);
            throw new \Exception('Failed to check model status');
        }

        $data = $response->json();

        return [
            'status' => $data['status'] ?? 'PENDING',
            'model_urls' => $data['model_urls'] ?? [],
            'progress' => $data['progress'] ?? 0,
        ];
    }
}
