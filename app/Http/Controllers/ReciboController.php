<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Recibo::with(['poliza.asegurado', 'poliza.aseguradora', 'poliza.user', 'poliza.vehiculos']);

        // Permissions Check
        if ($user->role !== 'admin') {
            $query->whereHas('poliza', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Search Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('poliza', function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhereHas('asegurado', function ($sq) use ($search) {
                      $sq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehiculos', function ($sq) use ($search) {
                      $sq->where('vin', 'like', "%{$search}%");
                  });
            });
        }

        // Active State Filter
        $isActive = $request->get('is_active', 'all');
        if ($isActive !== 'all') {
            $query->where('is_active', $isActive === 'yes');
        }

        // Date Range Filters
        if ($request->filled('desde')) {
            $query->where('fecha_vencimiento', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->where('fecha_vencimiento', '<=', $request->hasta);
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            if ($status === 'vencido') {
                $query->where(function ($q) {
                    $q->where('status', 'vencido')
                      ->orWhere(function ($sq) {
                          $sq->where('status', 'pendiente')
                             ->where('fecha_vencimiento', '<', now()->startOfDay());
                      });
                });
            } elseif ($status === 'pendiente') {
                $query->where('status', 'pendiente')
                      ->where('fecha_vencimiento', '>=', now()->startOfDay());
            } else {
                $query->where('status', $status);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'fecha_vencimiento');
        $direction = $request->get('direction', 'asc');
        
        $allowedSorts = ['fecha_vencimiento', 'monto', 'indice_recibo', 'created_at', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('fecha_vencimiento', 'asc');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportCsv($query);
        }

        if ($request->boolean('partial')) {
            $recibos = $query->paginate(15)->withQueryString();
            return response()->json([
                'rows'       => view('recibos._rows', compact('recibos'))->render(),
                'pagination' => $recibos->links()->toHtml(),
                'total'      => $recibos->total(),
                'from'       => $recibos->firstItem() ?? 0,
                'to'         => $recibos->lastItem() ?? 0,
            ]);
        }

        $recibos = $query->paginate(15)->withQueryString();

        return view('recibos.index', compact('recibos'));
    }

    private function exportCsv($query)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=recibos_export.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Póliza', 'Asegurado', 'Recibo No.', 'Inicio Vigencia', 'Fin Vigencia', 'Vencimiento', 'Monto Original', 'Contracargo', 'Estatus Base', 'Activo'
        ];

        $callback = function() use($query, $columns) {
            $file = fopen('php://output', 'w');
            
            // For Excel to read UTF-8 properly
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

            fputcsv($file, $columns);

            $query->chunk(500, function($recibos) use($file) {
                foreach ($recibos as $recibo) {
                    $row = [
                        $recibo->poliza->numero_poliza,
                        $recibo->poliza->asegurado->nombre,
                        $recibo->indice_recibo,
                        $recibo->fecha_inicio_vigencia ? $recibo->fecha_inicio_vigencia->format('d/m/Y') : '',
                        $recibo->fecha_fin_vigencia ? $recibo->fecha_fin_vigencia->format('d/m/Y') : '',
                        $recibo->fecha_vencimiento ? $recibo->fecha_vencimiento->format('d/m/Y') : '',
                        $recibo->monto,
                        $recibo->contracargo,
                        $recibo->status_display,
                        $recibo->is_active ? 'Sí' : 'No'
                    ];
                    fputcsv($file, $row);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request, Recibo $recibo)
    {
        $request->validate([
            'status' => 'required|in:pendiente,pagado,vencido',
            'contracargo' => 'nullable|numeric|min:0'
        ]);

        $recibo->update([
            'status' => $request->status,
            'contracargo' => $request->contracargo ?? 0
        ]);

        return back()->with('success', 'Recibo actualizado correctamente.');
    }

    public function toggleActive(Recibo $recibo)
    {
        $recibo->update([
            'is_active' => !$recibo->is_active
        ]);

        return back()->with('success', 'Estado del recibo actualizado correctamente.');
    }
}
