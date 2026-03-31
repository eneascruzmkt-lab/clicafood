<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Services\MeshyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArModelController extends Controller
{
    /**
     * Generate a 3D model from a menu item's image.
     */
    public function generate(Request $request, MenuItem $menuItem)
    {
        $restaurant = $request->user()->restaurant;

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        if (!$menuItem->image) {
            return back()->with('error', 'O item precisa ter uma imagem para gerar o modelo 3D.');
        }

        // Get full image URL
        $imageUrl = $menuItem->image;
        if (!str_starts_with($imageUrl, 'http')) {
            $imageUrl = Storage::disk('s3')->url($imageUrl);
        }

        try {
            $service = new MeshyService();
            $taskId = $service->imageToModel($imageUrl);

            $menuItem->update([
                'model_status' => 'processing:' . $taskId,
            ]);

            return back()->with('success', 'Modelo 3D sendo gerado. Isso pode levar alguns minutos.');

        } catch (\Exception $e) {
            Log::error('AR model generation failed', [
                'menu_item_id' => $menuItem->id,
                'error' => $e->getMessage(),
            ]);

            $menuItem->update(['model_status' => 'failed']);

            return back()->with('error', 'Erro ao gerar modelo 3D: ' . $e->getMessage());
        }
    }

    /**
     * Poll the status of a 3D model generation.
     */
    public function status(Request $request, MenuItem $menuItem)
    {
        $restaurant = $request->user()->restaurant;

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        if (!$menuItem->model_status || !str_starts_with($menuItem->model_status, 'processing:')) {
            return response()->json([
                'status' => $menuItem->model_status ?? 'none',
                'model_glb_url' => $menuItem->model_glb_url,
                'model_usdz_url' => $menuItem->model_usdz_url,
            ]);
        }

        $taskId = str_replace('processing:', '', $menuItem->model_status);

        try {
            $service = new MeshyService();
            $result = $service->getTaskStatus($taskId);

            if ($result['status'] === 'SUCCEEDED') {
                $glbUrl = $result['model_urls']['glb'] ?? null;
                $usdzUrl = $result['model_urls']['usdz'] ?? null;

                $menuItem->update([
                    'model_glb_url' => $glbUrl,
                    'model_usdz_url' => $usdzUrl,
                    'model_status' => 'ready',
                ]);

                return response()->json([
                    'status' => 'ready',
                    'model_glb_url' => $glbUrl,
                    'model_usdz_url' => $usdzUrl,
                ]);
            }

            if ($result['status'] === 'FAILED') {
                $menuItem->update(['model_status' => 'failed']);
                return response()->json(['status' => 'failed']);
            }

            return response()->json([
                'status' => 'processing',
                'progress' => $result['progress'] ?? 0,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'processing',
                'progress' => 0,
            ]);
        }
    }

    /**
     * Remove the 3D model from a menu item.
     */
    public function destroy(Request $request, MenuItem $menuItem)
    {
        $restaurant = $request->user()->restaurant;

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $menuItem->update([
            'model_glb_url' => null,
            'model_usdz_url' => null,
            'model_status' => null,
        ]);

        return back()->with('success', 'Modelo 3D removido.');
    }
}
