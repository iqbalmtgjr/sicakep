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

        $userRole = auth()->user()->role;
        $levelJabatan = auth()->user()->level_jabatan;

        // Cek role biasa
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // TAMBAHAN: Cek berdasarkan level jabatan
        if (in_array($levelJabatan, $roles)) {
            return $next($request);
        }

        flash('Anda tidak memiliki hak akses.', 'error', [], 'Gagal!');
        return redirect('dashboard');
    }
}
