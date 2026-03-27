<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('restaurant:id,user_id,name,slug')
            ->orderByDesc('created_at')
            ->get([
                'id', 'name', 'email', 'phone', 'cpf_cnpj', 'role',
                'subscription_status', 'subscription_plan',
                'subscription_expires_at', 'subscription_id',
                'abacatepay_customer_id', 'created_at',
            ]);

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:18',
            'role' => 'required|string|in:owner,admin',
            'subscription_status' => 'nullable|string|in:active,pending,trial,canceled',
            'subscription_plan' => 'nullable|string|max:50',
            'subscription_expires_at' => 'nullable|date',
        ]);

        if (empty($validated['subscription_expires_at'])) {
            $validated['subscription_expires_at'] = null;
        }

        $user->update($validated);

        return back()->with('success', "Usuário {$user->name} atualizado.");
    }
}
