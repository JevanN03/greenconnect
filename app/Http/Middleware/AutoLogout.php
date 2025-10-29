<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = (int) config('session.idle_timeout', env('SESSION_IDLE_TIMEOUT', 20)); // menit
        $now = now()->timestamp;

        // jika sudah login, cek idle
        if (Auth::check()) {
            $last = (int) $request->session()->get('last_activity', $now);
            if (($now - $last) > ($timeout * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('success', 'Sesi berakhir karena tidak ada aktivitas.');
            }
            // refresh timestamp aktivitas
            $request->session()->put('last_activity', $now);
        }

        return $next($request);
    }
}
