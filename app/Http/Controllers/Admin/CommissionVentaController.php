<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recibo;
use App\Models\Poliza;
use App\Models\User;
use App\Models\Aseguradora;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommissionVentaController extends Controller
{
    /**
     * Display a listing of commissions and sales.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        // 1. Initial Queries for Filters
        $agents = $isAdmin ? User::where('role', 'agente')->orderBy('name')->get() : collect([$user]);
        $insurers = Aseguradora::orderBy('nombre')->get();

        // 2. Build Base Query for Stats and Table
        $query = Recibo::with(['poliza.asegurado', 'poliza.aseguradora', 'poliza.user'])
            ->where('is_active', true);

        // Security: Agents only see their receipts
        if (!$isAdmin) {
            $query->whereHas('poliza', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($request->filled('agent_id')) {
            $query->whereHas('poliza', function ($q) use ($request) {
                $q->where('user_id', $request->agent_id);
            });
        }

        // Apply Insurer Filter
        if ($request->filled('aseguradora_id')) {
            $query->whereHas('poliza', function ($q) use ($request) {
                $q->where('aseguradora_id', $request->aseguradora_id);
            });
        }

        // Apply Date Range Filter (Default to current year if none provided)
        $start = $request->filled('desde') ? Carbon::parse($request->desde) : now()->startOfYear();
        $end = $request->filled('hasta') ? Carbon::parse($request->hasta)->endOfDay() : now()->endOfDay();
        
        $query->whereBetween('created_at', [$start, $end]);

        // 3. Aggregate Stats
        $statsQuery = clone $query;
        $stats = [
            'total_ventas' => (clone $statsQuery)->sum('monto'),
            'total_comisiones' => (clone $statsQuery)->sum('comision'),
            'comisiones_pagadas' => (clone $statsQuery)->where('status', 'pagado')->sum('comision'),
            'comisiones_pendientes' => (clone $statsQuery)->whereIn('status', ['pendiente', 'vencido'])->sum('comision'),
            'conteo_recibos' => (clone $statsQuery)->count(),
        ];

        // 4. Monthly Trend (Trend Chart)
        $trendData = (clone $statsQuery)
            ->selectRaw('MONTH(created_at) as mes, YEAR(created_at) as anio, SUM(monto) as ventas, SUM(comision) as comisiones')
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        $monthlyStats = [];
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        // Prepare 12 months for the chart (even if empty)
        for ($i = 1; $i <= 12; $i++) {
            $dataForMonth = $trendData->firstWhere('mes', $i);
            $monthlyStats[] = [
                'name' => $mesesNombres[$i - 1],
                'ventas' => $dataForMonth ? $dataForMonth->ventas : 0,
                'comisiones' => $dataForMonth ? $dataForMonth->comisiones : 0,
            ];
        }

        // 5. Paginated Results for the Table
        $recibos = $query->latest()->paginate(25)->withQueryString();

        return view('admin.comisiones.index', compact(
            'agents', 
            'insurers', 
            'stats', 
            'monthlyStats', 
            'recibos',
            'isAdmin'
        ));
    }
}
