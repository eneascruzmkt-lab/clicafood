<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $items = $restaurant->menuItems()
            ->with('category:id,name')
            ->orderBy('order')
            ->get();

        return Inertia::render('Menu/Index', [
            'items' => $items,
        ]);
    }

    public function create(Request $request)
    {
        $categories = $request->user()->restaurant->categories()
            ->where('active', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        return Inertia::render('Menu/Form', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4,webm,mov|max:102400',
            'video_url_external' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:4096',
            'featured' => 'boolean',
            'available' => 'boolean',
        ]);

        $restaurant = $request->user()->restaurant;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 's3');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['video_thumbnail'] = $request->file('thumbnail')->store('thumbnails', 's3');
        }

        if ($request->hasFile('video')) {
            $validated['video_url'] = $this->processAndStoreVideo($request->file('video'));
        } elseif ($request->filled('video_url_external')) {
            $validated['video_url'] = $request->video_url_external;
        }

        unset($validated['video'], $validated['video_url_external'], $validated['thumbnail']);

        $validated['restaurant_id'] = $restaurant->id;
        $validated['order'] = $restaurant->menuItems()->max('order') + 1;

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Item adicionado ao cardápio!');
    }

    public function edit(Request $request, MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        $categories = $request->user()->restaurant->categories()
            ->where('active', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        return Inertia::render('Menu/Form', [
            'item' => $menuItem,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4,webm,mov|max:102400',
            'video_url_external' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:4096',
            'featured' => 'boolean',
            'available' => 'boolean',
            'remove_video' => 'boolean',
            'remove_thumbnail' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image && !str_starts_with($menuItem->image, 'http')) {
                Storage::disk('s3')->delete($menuItem->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 's3');
        }

        // Thumbnail handling
        if ($request->boolean('remove_thumbnail') && $menuItem->video_thumbnail) {
            if (!str_starts_with($menuItem->video_thumbnail, 'http')) {
                Storage::disk('s3')->delete($menuItem->video_thumbnail);
            }
            $validated['video_thumbnail'] = null;
        } elseif ($request->hasFile('thumbnail')) {
            if ($menuItem->video_thumbnail && !str_starts_with($menuItem->video_thumbnail, 'http')) {
                Storage::disk('s3')->delete($menuItem->video_thumbnail);
            }
            $validated['video_thumbnail'] = $request->file('thumbnail')->store('thumbnails', 's3');
        }

        // Video handling
        if ($request->boolean('remove_video') && $menuItem->video_url) {
            if (!str_starts_with($menuItem->video_url, 'http')) {
                Storage::disk('s3')->delete($menuItem->video_url);
            }
            $validated['video_url'] = null;
        } elseif ($request->hasFile('video')) {
            if ($menuItem->video_url && !str_starts_with($menuItem->video_url, 'http')) {
                Storage::disk('s3')->delete($menuItem->video_url);
            }
            $validated['video_url'] = $this->processAndStoreVideo($request->file('video'));
        } elseif ($request->filled('video_url_external')) {
            if ($menuItem->video_url && !str_starts_with($menuItem->video_url, 'http')) {
                Storage::disk('s3')->delete($menuItem->video_url);
            }
            $validated['video_url'] = $request->video_url_external;
        }

        unset($validated['video'], $validated['video_url_external'], $validated['remove_video'], $validated['thumbnail'], $validated['remove_thumbnail']);

        $menuItem->update($validated);

        return redirect()->route('menu-items.index')->with('success', 'Item atualizado!');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize($menuItem);

        if ($menuItem->image && !str_starts_with($menuItem->image, 'http')) {
            Storage::disk('s3')->delete($menuItem->image);
        }
        if ($menuItem->video_url && !str_starts_with($menuItem->video_url, 'http')) {
            Storage::disk('s3')->delete($menuItem->video_url);
        }
        if ($menuItem->video_thumbnail && !str_starts_with($menuItem->video_thumbnail, 'http')) {
            Storage::disk('s3')->delete($menuItem->video_thumbnail);
        }

        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Item removido!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:menu_items,id',
        ]);

        $restaurant = $request->user()->restaurant;

        foreach ($request->ids as $index => $id) {
            $restaurant->menuItems()->where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Compress video with FFmpeg and upload to S3/R2.
     * - H.264 codec, max 720p vertical, ~1.5Mbps bitrate
     * - Converts any format to MP4
     * - Strips audio track to reduce size further (optional: keeps audio)
     */
    private function processAndStoreVideo($file): string
    {
        $inputPath = $file->getRealPath();
        $outputFilename = 'videos/' . Str::uuid() . '.mp4';
        $outputPath = sys_get_temp_dir() . '/' . Str::uuid() . '.mp4';

        try {
            // Get video dimensions to determine orientation
            $probeCmd = sprintf(
                '%s -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 %s 2>/dev/null',
                config('laravel-ffmpeg.ffprobe.binaries', '/usr/bin/ffprobe'),
                escapeshellarg($inputPath)
            );
            $dimensions = trim(shell_exec($probeCmd) ?? '');

            // Determine scale filter: max 720p, keep aspect ratio
            // For vertical video (portrait): height=720, width=auto
            // For horizontal video (landscape): width=720, height=auto
            $scaleFilter = "scale='min(720,iw)':'min(1280,ih)':force_original_aspect_ratio=decrease";
            if ($dimensions) {
                [$w, $h] = array_map('intval', explode('x', $dimensions));
                if ($w > 0 && $h > 0) {
                    if ($h > $w) {
                        // Portrait/vertical
                        $scaleFilter = "scale=-2:'min(1280,ih)'";
                    } else {
                        // Landscape
                        $scaleFilter = "scale='min(720,iw)':-2";
                    }
                }
            }

            // FFmpeg compression command
            $ffmpegBin = config('laravel-ffmpeg.ffmpeg.binaries', '/usr/bin/ffmpeg');
            $cmd = sprintf(
                '%s -y -i %s -c:v libx264 -preset fast -crf 28 -b:v 1500k -maxrate 2000k -bufsize 3000k ' .
                '-vf "%s" -c:a aac -b:a 128k -movflags +faststart -f mp4 %s 2>&1',
                $ffmpegBin,
                escapeshellarg($inputPath),
                $scaleFilter,
                escapeshellarg($outputPath)
            );

            $output = shell_exec($cmd);

            // If compression succeeded and output exists, upload compressed version
            if (file_exists($outputPath) && filesize($outputPath) > 0) {
                Storage::disk('s3')->put($outputFilename, file_get_contents($outputPath), 'public');
                @unlink($outputPath);
                return $outputFilename;
            }

            // Fallback: upload original if compression failed
            @unlink($outputPath);
            return $file->store('videos', 's3');

        } catch (\Throwable $e) {
            @unlink($outputPath);
            // Fallback: upload original
            return $file->store('videos', 's3');
        }
    }

    private function authorize(MenuItem $menuItem): void
    {
        if ($menuItem->restaurant_id !== request()->user()->restaurant->id) {
            abort(403);
        }
    }
}
