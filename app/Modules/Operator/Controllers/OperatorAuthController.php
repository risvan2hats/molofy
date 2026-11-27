<?php

namespace App\Modules\Operator\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operator\Models\Operator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OperatorAuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('Operator/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'pin' => ['required', 'string', 'digits:6'],
        ]);

        $key = 'operator-login:' . strtolower($request->email)
            . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'pin' => 'Too many attempts. Please try again later.',
            ]);
        }

        $operator = Operator::query()
            ->where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (!$operator || !Hash::check($request->pin, $operator->pin_hash)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'pin' => 'The email address or PIN is incorrect.',
            ]);
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();
        $request->session()->put('operator_id', $operator->id);

        return redirect()->route('operator.departures');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('operator_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('access');
    }
}
