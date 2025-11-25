<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CekLogin
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (! in_array($user->idrole, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
