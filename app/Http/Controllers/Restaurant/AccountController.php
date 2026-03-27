<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Settings/Account', [
            'user' => $user->only('id', 'name', 'email', 'cpf_cnpj', 'phone'),
            'subscription' => [
                'status' => $user->subscription_status ?? 'pending',
                'plan' => $user->subscription_plan ?? 'free',
                'expires_at' => $user->subscription_expires_at?->format('d/m/Y'),
                'subscription_id' => $user->subscription_id,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
            'cpf_cnpj' => 'nullable|string|max:18',
            'phone' => 'nullable|string|max:20',
        ]);

        $request->user()->update($validated);

        return redirect()->route('account.edit')->with('success', 'Dados atualizados!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.edit')->with('success', 'Senha alterada!');
    }
}
