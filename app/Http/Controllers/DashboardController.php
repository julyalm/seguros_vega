<?php

namespace App\Http\Controllers;

use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real KPIs and last recent policies.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $kpiQuery = Poliza::query();
        if ($user->role !== 'admin') {
            $kpiQuery->where('user_id', $user->id);
        }

        $kpis = [
            'total' => (clone $kpiQuery)->count(),
            'activas' => (clone $kpiQuery)->where('fecha_fin', '>=', now())->count(),
            'vencidas' => (clone $kpiQuery)->where('fecha_fin', '<', now())->count(),
            'nuevas_mes' => (clone $kpiQuery)->whereMonth('created_at', now()->month)->count(),
        ];

        // Recent policies (Dashboard overview)
        $recentPolizas = Poliza::with(['asegurado', 'aseguradora', 'user']);
        if ($user->role !== 'admin') {
            $recentPolizas->where('user_id', $user->id);
        }
        $recentPolizas = $recentPolizas->latest()->limit(5)->get();

        return view('dashboard.index', compact('recentPolizas', 'kpis'));
    }
}
