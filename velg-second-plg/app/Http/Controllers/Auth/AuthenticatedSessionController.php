<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Pengguna;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Sync User -> Pengguna after successful login
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $existing = \App\Models\Pengguna::where('email', $user->email)->first();
            if ($existing) {
                $existing->update([
                    'nama'     => $user->name,
                    'password' => $user->getAuthPassword(),
                    // keep existing role
                ]);
            } else {
                \App\Models\Pengguna::create([
                    'nama'     => $user->name,
                    'email'    => $user->email,
                    'password' => $user->getAuthPassword(),
                    'role'     => 'guest', // default
                ]);
            }
        }

        // Migrasi keranjang guest -> user
        $userId = \Illuminate\Support\Facades\Auth::id();
        if ($userId) {
            $userKey = 'cart_user_' . $userId;
            $guest = $request->session()->pull('cart_guest', $request->session()->pull('cart', []));
            if (!empty($guest)) {
                $current = $request->session()->get($userKey, []);
                foreach ($guest as $pid => $item) {
                    if (isset($current[$pid])) {
                        $current[$pid]['qty'] += (int)($item['qty'] ?? 1);
                    } else {
                        $current[$pid] = $item;
                    }
                }
                $request->session()->put($userKey, $current);
            }
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
