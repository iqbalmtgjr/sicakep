<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role;
        $userRoles = $user->roles ?? [];
        $levelJabatan = $user->level_jabatan;

        // Cek role biasa (single role)
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // TAMBAHAN: Cek multiple roles
        foreach ($userRoles as $role) {
            if (in_array($role, $roles)) {
                return $next($request);
            }
        }

        // TAMBAHAN: Cek berdasarkan level jabatan
        if (in_array($levelJabatan, $roles)) {
            return $next($request);
        }

        flash('Anda tidak memiliki hak akses.', 'error', [], 'Gagal!');
        return redirect('dashboard');
    }
}
