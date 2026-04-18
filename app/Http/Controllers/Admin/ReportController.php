<?php

namespace App\Http\Controllers\Admin;

use App\Models\Poliza;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the analytics and operations report.
     */
    public function index()
    {
        // 1. Distribución por Ramo (para Dona)
        $ramoStats = Poliza::selectRaw('ramo, count(*) as count, sum(prima_total) as prima')
            ->groupBy('ramo')
            ->get();

        // 2. Ranking de Agentes (para Barras)
        $agentStats = User::where('role', 'agente')
            ->withSum('polizas', 'prima_total')
            ->orderByDesc('polizas_sum_prima_total')
            ->limit(10)
            ->get();

        // 3. Tendencia Mensual (año actual)
        $monthlyData = Poliza::selectRaw('MONTH(created_at) as mes, SUM(prima_total) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Rellenar meses vacíos
        $monthlyStats = [];
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStats[] = [
                'name' => $mesesNombres[$i - 1],
                'total' => $monthlyData[$i] ?? 0
            ];
        }

        // 4. Detalle de Operaciones (para Tabla)
        // Incluimos una "Comisión Estimada" ficticia del 10%
        $operaciones = Poliza::with(['asegurado', 'aseguradora', 'user'])
            ->latest()
            ->limit(100)
            ->get();

        $totales = [
            'prima' => $operaciones->sum('prima_total'),
            'comision' => $operaciones->sum('prima_total') * 0.10, // 10% global estimate
            'conteo' => $operaciones->count()
        ];

        return view('admin.reportes.index', compact('ramoStats', 'agentStats', 'monthlyStats', 'operaciones', 'totales'));
    }
}
