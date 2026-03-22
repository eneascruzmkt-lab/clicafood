<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPlanController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('user:id,name,email')
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Plans', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function store(Request $request)
    {
        // Admin can manually create subscriptions
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|in:free,pro',
            'status' => 'required|in:active,cancelled,expired',
            'ends_at' => 'nullable|date',
        ]);

        $validated['starts_at'] = now();

        Subscription::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated,
        );

        return redirect()->route('admin.plans.index')->with('success', 'Plano atualizado!');
    }

    public function update(Request $request, Subscription $plan)
    {
        $validated = $request->validate([
            'plan' => 'required|in:free,pro',
            'status' => 'required|in:active,cancelled,expired',
            'ends_at' => 'nullable|date',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plano atualizado!');
    }

    public function destroy(Subscription $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plano removido.');
    }
}
