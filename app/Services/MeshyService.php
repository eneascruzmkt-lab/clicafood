<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MeshyService
{
    protected string $baseUrl = 'https://api.meshy.ai/openapi/v1';
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
            ->timeout(60)
            ->post("{$this->baseUrl}/image-to-3d", [
                'image_url' => $imageUrl,
                'ai_model' => 'meshy-6',
                'should_remesh' => true,
                'should_texture' => true,
                'enable_pbr' => true,
                'image_enhancement' => true,
                'remove_lighting' => true,
                'target_polycount' => 300000,
            ]);

        if ($response->failed()) {
            Log::error('Meshy imageToModel failed', [
                'status' => $response->status(),
                'body' => $response->json(),
                'image_url' => $imageUrl,
            ]);
            throw new \Exception('Meshy API error: ' . ($response->json()['message'] ?? $response->body()));
        }

        $data = $response->json();
        return $data['result'] ?? '';
    }

    /**
     * Check the status of a 3D generation task.
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
