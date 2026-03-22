<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $qrCodes = $restaurant->qrCodes()->latest()->get();

        return Inertia::render('QrCode/Index', [
            'qrCodes' => $qrCodes,
            'menuUrl' => $restaurant->public_url,
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('QrCode/Form', [
            'menuUrl' => $request->user()->restaurant->public_url,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'style_config' => 'nullable|array',
            'style_config.color' => 'nullable|string|max:7',
            'style_config.background' => 'nullable|string|max:7',
        ]);

        $restaurant = $request->user()->restaurant;

        $validated['restaurant_id'] = $restaurant->id;
        $validated['url'] = $restaurant->public_url;

        QrCode::create($validated);

        return redirect()->route('qr-codes.index')->with('success', 'QR Code criado!');
    }

    public function edit(QrCode $qrCode)
    {
        $this->authorize($qrCode);

        return Inertia::render('QrCode/Form', [
            'qrCode' => $qrCode,
            'menuUrl' => $qrCode->url,
        ]);
    }

    public function update(Request $request, QrCode $qrCode)
    {
        $this->authorize($qrCode);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'style_config' => 'nullable|array',
        ]);

        $qrCode->update($validated);

        return redirect()->route('qr-codes.index')->with('success', 'QR Code atualizado!');
    }

    public function destroy(QrCode $qrCode)
    {
        $this->authorize($qrCode);
        $qrCode->delete();

        return redirect()->route('qr-codes.index')->with('success', 'QR Code removido!');
    }

    public function download(QrCode $qrCode, string $format)
    {
        $this->authorize($qrCode);

        $config = $qrCode->style_config ?? [];
        $color = $config['color'] ?? '#E63B2E';
        $bg = $config['background'] ?? '#ffffff';

        $colorRgb = sscanf($color, "#%02x%02x%02x");
        $bgRgb = sscanf($bg, "#%02x%02x%02x");

        if ($format === 'svg') {
            $qr = QrCodeGenerator::format('svg')
                ->size(400)
                ->color($colorRgb[0], $colorRgb[1], $colorRgb[2])
                ->backgroundColor($bgRgb[0], $bgRgb[1], $bgRgb[2])
                ->generate($qrCode->url);

            return response($qr, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => 'attachment; filename="qrcode-' . $qrCode->label . '.svg"',
            ]);
        }

        $qr = QrCodeGenerator::format('png')
            ->size(400)
            ->color($colorRgb[0], $colorRgb[1], $colorRgb[2])
            ->backgroundColor($bgRgb[0], $bgRgb[1], $bgRgb[2])
            ->generate($qrCode->url);

        return response($qr, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qrcode-' . $qrCode->label . '.png"',
        ]);
    }

    private function authorize(QrCode $qrCode): void
    {
        if ($qrCode->restaurant_id !== request()->user()->restaurant->id) {
            abort(403);
        }
    }
}
