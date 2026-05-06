<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use App\Models\AseguradoDireccion;
use App\Models\Asegurado;
use App\Models\Poliza;
use App\Models\Recibo;
use App\Models\PolizaVehiculo;
use App\Models\PolizaGmm;
use App\Models\Sepomex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PolizaController extends Controller
{
    /**
     * Display a listing of the policies with full filtering.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Poliza::with(['asegurado', 'aseguradora', 'user', 'vehiculos']);

        // 1. Permissions Check
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // 2. Application of Search Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhereHas('asegurado', function($sq) use ($search) {
                      $sq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('aseguradora', function($sq) use ($search) {
                      $sq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehiculos', function($sq) use ($search) {
                      $sq->where('vin', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('ramo') && $request->ramo !== 'all') {
            $query->where('ramo', $request->ramo);
        }

        if ($request->filled('aseguradora_id') && $request->aseguradora_id !== 'all') {
            $query->where('aseguradora_id', $request->aseguradora_id);
        }

        if ($user->role === 'admin' && $request->filled('agente_id') && $request->agente_id !== 'all') {
            $query->where('user_id', $request->agente_id);
        }

        if ($request->filled('desde')) {
            $query->where('fecha_fin', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->where('fecha_fin', '<=', $request->hasta);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('fecha_fin', '>=', now());
            } else if ($request->status === 'expired') {
                $query->where('fecha_fin', '<', now());
            }
        }

        // 3. Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['numero_poliza', 'fecha_fin', 'prima_total', 'ramo', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        // 4. Data for Filters
        $aseguradoras = Aseguradora::all();
        $agentes = $user->role === 'admin' ? \App\Models\User::where('role', 'agent')->get() : collect();

        if ($request->boolean('partial')) {
            $polizas = $query->paginate(15)->withQueryString();
            return response()->json([
                'rows'       => view('polizas._rows', compact('polizas'))->render(),
                'pagination' => $polizas->links()->toHtml(),
                'total'      => $polizas->total(),
                'from'       => $polizas->firstItem() ?? 0,
                'to'         => $polizas->lastItem() ?? 0,
            ]);
        }

        $polizas = $query->paginate(15)->withQueryString();

        return view('polizas.index', compact('polizas', 'aseguradoras', 'agentes'));
    }

    /**
     * Show the wizard for creating a new policy.
     */
    public function create()
    {
        $aseguradoras = Aseguradora::all();
        return view('polizas.create', compact('aseguradoras'));
    }

    /**
     * Display the specified policy.
     */
    public function show(Poliza $poliza)
    {
        $poliza->load(['asegurado', 'aseguradora', 'recibos', 'vehiculos', 'parent', 'children', 'children.vehiculos']);

        return view('polizas.show', compact('poliza'));
    }

    /**
     * Update fixed policy data (Admin only).
     */
    public function update(Request $request, Poliza $poliza)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar la póliza.');
        }

        $section = $request->input('section');

        if ($section === 'asegurado') {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'rfc' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'telefono' => 'nullable|string|max:20',
            ]);
            $poliza->asegurado->update($validated);
            $msg = 'Información del asegurado actualizada.';
        } elseif ($section === 'vigencia') {
            $validated = $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);
            $poliza->update($validated);
            $msg = 'Vigencia de la póliza actualizada.';
        } else {
            // Default to finanzas/generales
            $validated = $request->validate([
                'numero_poliza' => 'required|string|max:255|unique:polizas,numero_poliza,' . $poliza->id,
                'prima_neta' => 'required|numeric|min:0',
                'derechos' => 'required|numeric|min:0',
                'recargo' => 'required|numeric|min:0',
                'iva' => 'required|numeric|min:0',
                'comision' => 'required|numeric|min:0',
                'prima_total' => 'required|numeric|min:0',
            ]);
            $poliza->update($validated);
            $msg = 'Resumen financiero actualizado correctamente.';
        }

        return back()->with('success', $msg);
    }

    /**
     * API lookup for parent policy data.
     * for fleet inclusion.
     */
    public function lookupParent($numero)
    {
        $poliza = Poliza::with(['aseguradora', 'recibos' => function ($q) {
            $q->where('status', 'pendiente');
        }])->where('numero_poliza', $numero)->first();

        if (!$poliza) {
            return response()->json(['status' => 'error', 'message' => 'Póliza padre no encontrada'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $poliza->id,
                'numero_poliza' => $poliza->numero_poliza,
                'fecha_fin' => \Carbon\Carbon::parse($poliza->fecha_fin)->format('Y-m-d'),
                'recibos_pendientes' => count($poliza->recibos),
                'aseguradora' => $poliza->aseguradora ? [
                    'nombre' => $poliza->aseguradora->nombre,
                    'inicial' => $poliza->aseguradora->inicial,
                    'color' => $poliza->aseguradora->color,
                ] : null,
                'vencimientos_pendientes' => $poliza->recibos()
                    ->where('status', 'pendiente')
                    ->orderBy('indice_recibo')
                    ->get()
                    ->map(fn($r) => \Carbon\Carbon::parse($r->fecha_vencimiento)->format('Y-m-d')),
                'incisos_usados' => PolizaVehiculo::whereIn('poliza_id', function($query) use ($poliza) {
                    $query->select('id')->from('polizas')
                          ->where('id', $poliza->id)
                          ->orWhere('parent_id', $poliza->id);
                })->pluck('inciso')->toArray(),
                'asegurado_id' => $poliza->asegurado_id,
                'aseguradora_id' => $poliza->aseguradora_id,
                'ramo' => $poliza->ramo,
                'frecuencia_pago' => $poliza->frecuencia_pago,
                'prima_neta' => $poliza->prima_neta,
                'derechos' => $poliza->derechos,
                'recargo' => $poliza->recargo,
                'iva' => $poliza->iva,
                'comision' => $poliza->comision,
            ]
        ]);
    }

    /**
     * Store a newly created policy in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ramo'             => 'required',
            'asegurado_rfc'    => 'required|string|min:12|max:13',
            'asegurado_nombre' => 'required',
            'numero_poliza'    => 'required|unique:polizas,numero_poliza',
            'fecha_inicio'     => 'required|date',
            'fecha_fin'        => 'required|date|after:fecha_inicio',
            'prima_neta'       => 'required|numeric|min:0.01',
            'derechos'         => 'nullable|numeric|min:0',
            'recargo'          => 'nullable|numeric|min:0',
            'iva'              => 'required|numeric|min:0',
            'prima_total'      => 'required|numeric|min:0',
            'comision'         => 'nullable|numeric|min:0',
            'aseguradora_id'   => 'required|exists:aseguradoras,id',
            'direccion_id'     => 'nullable|exists:asegurado_direcciones,id',
            'asegurado_cp'       => 'required_without:direccion_id',
            'asegurado_estado'   => 'required_without:direccion_id',
            'asegurado_municipio'=> 'required_without:direccion_id',
            'asegurado_colonia'  => 'required_without:direccion_id',
            'asegurado_calle'    => 'required_without:direccion_id',
            'asegurado_num_ext'  => 'required_without:direccion_id',
            'archivo_poliza'   => 'nullable|file|mimes:pdf|max:10240',
            'archivo_recibo'   => 'nullable|file|mimes:pdf|max:10240',

            // Vehicles for Fleet
            'vehiculos'           => 'nullable|array',
            'vehiculos.*.marca'   => 'required_if:ramo,Autos',
            'vehiculos.*.modelo'  => 'required_if:ramo,Autos',
            'vehiculos.*.vin'     => 'nullable|unique:poliza_vehiculos,vin',
        ]);

        try {
            DB::beginTransaction();

            $asegurado = Asegurado::firstOrCreate(
                ['rfc' => $request->asegurado_rfc],
                [
                    'nombre' => $request->asegurado_nombre,
                    'fecha_nacimiento' => $request->asegurado_nacimiento,
                    'genero' => $request->asegurado_genero,
                    'email' => $request->asegurado_email,
                    'telefono' => $request->asegurado_telefono,
                ]
            );

            if ($request->filled('direccion_id')) {
                $direccionId = $request->direccion_id;
            } else {
                $direccion = AseguradoDireccion::create([
                    'asegurado_id' => $asegurado->id,
                    'codigo_postal' => $request->asegurado_cp,
                    'estado' => $request->asegurado_estado,
                    'municipio' => $request->asegurado_municipio,
                    'colonia' => $request->asegurado_colonia,
                    'calle' => $request->asegurado_calle,
                    'num_exterior' => $request->asegurado_num_ext,
                    'num_interior' => $request->asegurado_num_int,
                    'alias' => $request->direccion_alias ?? 'Principal',
                ]);
                $direccionId = $direccion->id;
            }

            $filePath = null;
            $reciboPath = null;

            if ($request->hasFile('archivo_poliza')) {
                $filePath = $request->file('archivo_poliza')->store('polizas', 'public');
            }

            if ($request->hasFile('archivo_recibo')) {
                $reciboPath = $request->file('archivo_recibo')->store('recibos', 'public');
            }

            if ($request->filled('parent_id')) {
            $parent = Poliza::findOrFail($request->parent_id);
            $incisosUsados = PolizaVehiculo::whereIn('poliza_id', function($query) use ($parent) {
                $query->select('id')->from('polizas')
                      ->where('id', $parent->id)
                      ->orWhere('parent_id', $parent->id);
            })->pluck('inciso')->toArray();

            foreach ($request->vehiculos as $v) {
                if (in_array($v['inciso'], $incisosUsados)) {
                    return back()->withErrors(['vehiculo' => 'El inciso ' . $v['inciso'] . ' ya existe en esta flotilla.'])->withInput();
                }
            }
        }

            $poliza = Poliza::create([
                'numero_poliza'       => $request->numero_poliza,
                'ramo'                => $request->ramo,
                'user_id'             => auth()->id(),
                'asegurado_id'        => $asegurado->id,
                'asegurado_direccion_id' => $direccionId,
                'aseguradora_id'      => $request->aseguradora_id,
                'fecha_inicio'        => $request->fecha_inicio,
                'fecha_fin'           => $request->fecha_fin,
                'prima_neta'          => $request->prima_neta,
                'derechos'            => $request->derechos ?? 0,
                'recargo'             => $request->recargo ?? 0,
                'iva'                 => $request->iva,
                'prima_total'         => $request->prima_total,
                'comision'            => $request->comision ?? 0,
                'frecuencia_pago'     => $request->frecuencia_pago ?? 'Anual',
                'es_flotilla'         => $request->boolean('es_flotilla'),
                'flotilla_existente'  => $request->boolean('flotilla_existente'),
                'parent_id'           => $request->parent_id,
                'tipo_movimiento'     => $request->tipo_movimiento ?? ($request->parent_id ? 'inclusion' : 'emision'),
                'file_path'           => $filePath,
                'recibo_path'         => $reciboPath,
            ]);

            if ($request->ramo === 'Autos') {
                $vehiculos = $request->input('vehiculos', []);

                // Fallback for individual vehicle if array is empty
                if (empty($vehiculos) && $request->filled('vehiculo_marca')) {
                    $vehiculos = [[
                        'inciso' => $request->input('inciso', 1),
                        'tipo' => $request->input('vehiculo_tipo'),
                        'modelo' => $request->input('vehiculo_modelo'),
                        'marca' => $request->input('vehiculo_marca'),
                        'submarca' => $request->input('vehiculo_submarca'),
                        'vin' => $request->input('vehiculo_vin'),
                        'motor' => $request->input('vehiculo_motor'),
                        'placas' => $request->input('vehiculo_placas'),
                    ]];
                }

                foreach ($vehiculos as $v) {
                    PolizaVehiculo::create([
                        'poliza_id' => $poliza->id,
                        'inciso' => $v['inciso'] ?? null,
                        'tipo' => $v['tipo'] ?? 'Sedán',
                        'modelo' => $v['modelo'],
                        'marca' => $v['marca'],
                        'submarca' => $v['submarca'],
                        'vin' => $v['vin'] ?? null,
                        'motor' => $v['motor'] ?? null,
                        'placas' => $v['placas'] ?? null,
                    ]);
                }
            } elseif ($request->ramo === 'GMM') {
                PolizaGmm::create([
                    'poliza_id' => $poliza->id,
                    'endoso' => $request->gmm_endoso,
                    'suma_asegurada' => $request->gmm_suma,
                    'deducible' => $request->gmm_deducible,
                    'coaseguro' => $request->gmm_coaseguro,
                    'plan' => $request->gmm_plan,
                ]);
            }

            if ($request->has('recibos')) {
                foreach ($request->input('recibos') as $r) {
                    Recibo::create([
                        'poliza_id' => $poliza->id,
                        'indice_recibo' => $r['indice_recibo'],
                        'monto' => $r['monto'],
                        'prima_neta' => $r['prima_neta'] ?? 0,
                        'derechos' => $r['derechos'] ?? 0,
                        'recargo' => $r['recargo'] ?? 0,
                        'iva' => $r['iva'] ?? 0,
                        'fecha_inicio_vigencia' => $r['fecha_inicio_vigencia'],
                        'fecha_fin_vigencia' => $r['fecha_fin_vigencia'],
                        'fecha_vencimiento' => $r['fecha_vencimiento'],
                        'periodo_gracia' => $r['periodo_gracia'] ?? 30,
                        'status' => 'pendiente',
                    ]);
                }
            } else {
                $this->generarRecibos($poliza, $request->input('recibos_restantes_padre'));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Póliza registrada correctamente.',
                'redirect' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Limpiar archivos huérfanos subidos antes del error de BD
            if (!empty($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            if (!empty($reciboPath)) {
                Storage::disk('public')->delete($reciboPath);
            }
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al guardar la póliza: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logic to generate receipts based on payment frequency or parent remaining count.
     */
    private function generarRecibos(Poliza $poliza, $cantForzada = null)
    {
        $frecuencias = ['Anual' => 1, 'Semestral' => 2, 'Trimestral' => 4, 'Mensual' => 12];
        $cant = $cantForzada ?: ($frecuencias[$poliza->frecuencia_pago] ?? 1);

        $netaFracc = round($poliza->prima_neta / $cant, 2);
        $ivaFracc = round($poliza->iva / $cant, 2);
        $recFracc = round($poliza->recargo / $cant, 2);

        $totalGenerado = 0;
        $accumNeta = 0;
        $accumIva = 0;
        $accumRec = 0;

        $mesesIntervalo = 12 / ($frecuencias[$poliza->frecuencia_pago] ?? 1);

        for ($i = 0; $i < $cant; $i++) {
            $curNeta = $netaFracc;
            $curIva = $ivaFracc;
            $curRec = $recFracc;

            if ($i === ($cant - 1)) {
                $curNeta = round($poliza->prima_neta - $accumNeta, 2);
                $curIva = round($poliza->iva - $accumIva, 2);
                $curRec = round($poliza->recargo - $accumRec, 2);
            } else {
                $accumNeta += $curNeta;
                $accumIva += $curIva;
                $accumRec += $curRec;
            }

            $montoRecibo = $curNeta + $curIva + $curRec;
            if ($i === 0) $montoRecibo += $poliza->derechos;

            // Simple safety check on the very last receipt for the final total sum
            if ($i === ($cant - 1)) {
                $montoRecibo = round($poliza->prima_total - $totalGenerado, 2);
            }

            $inicio = Carbon::parse($poliza->fecha_inicio)->addMonths($i * $mesesIntervalo);
            $fin = Carbon::parse($poliza->fecha_inicio)->addMonths(($i + 1) * $mesesIntervalo);

            Recibo::create([
                'poliza_id' => $poliza->id,
                'indice_recibo' => $i + 1,
                'monto' => $montoRecibo,
                'prima_neta' => $curNeta,
                'derechos' => ($i === 0) ? $poliza->derechos : 0,
                'recargo' => $curRec,
                'iva' => $curIva,
                'fecha_inicio_vigencia' => $inicio,
                'fecha_fin_vigencia' => $fin,
                'periodo_gracia' => 30,
                'status' => 'pendiente',
                'fecha_vencimiento' => $inicio->copy()->addDays(30),
            ]);

            $totalGenerado += $montoRecibo;
        }
    }

    /**
     * Logic to activate / deactivate a policy and its receipts.
     */
    public function toggleActive(Poliza $poliza)
    {
        $newState = !$poliza->is_active;

        DB::beginTransaction();
        try {
            $poliza->update(['is_active' => $newState]);

            // Disable/enable related receipts based on policy
            $poliza->recibos()->update(['is_active' => $newState]);

            DB::commit();
            return back()->with('success', 'Estado de la póliza y sus recibos actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar estado: ' . $e->getMessage());
        }
    }

    /**
     * Update insured information for a policy (Admin only).
     */
    public function updateInsured(Request $request, Poliza $poliza)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar la información del asegurado.');
        }

        // Validation
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        try {
            // Update asegurado
            $poliza->asegurado->update($validated);

            return back()->with('success', 'Información del asegurado actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Update vehicle information for a policy (Admin only).
     */
    public function updateVehicles(Request $request, Poliza $poliza)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar los vehículos.');
        }

        // Validation
        $validated = $request->validate([
            'vehiculos' => 'required|array',
            'vehiculos.*.id' => 'required|exists:poliza_vehiculos,id',
            'vehiculos.*.inciso' => 'required|integer|min:1',
            'vehiculos.*.marca' => 'required|string|max:100',
            'vehiculos.*.submarca' => 'required|string|max:100',
            'vehiculos.*.modelo' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vehiculos.*.serie' => 'nullable|string|max:50',
            'vehiculos.*.motor' => 'nullable|string|max:50',
            'vehiculos.*.placas' => 'nullable|string|max:50',
            'vehiculos.*.vin' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            // Check for duplicate incisos within this policy
            $incisos = collect($validated['vehiculos'])->pluck('inciso')->toArray();
            if (count($incisos) !== count(array_unique($incisos))) {
                return back()->withErrors(['vehiculos' => 'Los incisos deben ser únicos.'])->withInput();
            }

            // Update each vehicle
            foreach ($validated['vehiculos'] as $vehiculoData) {
                $vehiculo = PolizaVehiculo::findOrFail($vehiculoData['id']);

                // Verify vehicle belongs to this policy
                if ($vehiculo->poliza_id !== $poliza->id) {
                    throw new \Exception('Vehículo no pertenece a esta póliza.');
                }

                $vehiculo->update([
                    'inciso' => $vehiculoData['inciso'],
                    'marca' => $vehiculoData['marca'],
                    'submarca' => $vehiculoData['submarca'],
                    'modelo' => $vehiculoData['modelo'], // This is the Year
                    'vin' => $vehiculoData['vin'] ?? $vehiculoData['serie'] ?? null,
                    'motor' => $vehiculoData['motor'] ?? null,
                    'placas' => $vehiculoData['placas'] ?? null,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Información de vehículos actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar vehículos: ' . $e->getMessage());
        }
    }

    /**
     * Update payment schedule for a policy (Admin only).
     */
    public function updatePayments(Request $request, Poliza $poliza)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar el calendario de pagos.');
        }

        // Validation
        $validated = $request->validate([
            'recibos' => 'required|array',
            'recibos.*.id' => 'required|exists:recibos,id',
            'recibos.*.fecha_vencimiento' => 'required|date',
            'recibos.*.fecha_inicio_vigencia' => 'required|date',
            'recibos.*.fecha_fin_vigencia' => 'required|date|after_or_equal:fecha_inicio_vigencia',
            'recibos.*.periodo_gracia' => 'required|integer|min:0',
            'recibos.*.monto' => 'required|numeric|min:0',
            'recibos.*.prima_neta' => 'required|numeric|min:0',
            'recibos.*.derechos' => 'required|numeric|min:0',
            'recibos.*.recargo' => 'required|numeric|min:0',
            'recibos.*.iva' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['recibos'] as $reciboData) {
                $recibo = Recibo::findOrFail($reciboData['id']);

                // Verify receipt belongs to this policy
                if ($recibo->poliza_id !== $poliza->id) {
                    throw new \Exception('Recibo no pertenece a esta póliza.');
                }

                $recibo->update([
                    'fecha_vencimiento' => $reciboData['fecha_vencimiento'],
                    'fecha_inicio_vigencia' => $reciboData['fecha_inicio_vigencia'],
                    'fecha_fin_vigencia' => $reciboData['fecha_fin_vigencia'],
                    'periodo_gracia' => $reciboData['periodo_gracia'],
                    'monto' => $reciboData['monto'],
                    'prima_neta' => $reciboData['prima_neta'],
                    'derechos' => $reciboData['derechos'],
                    'recargo' => $reciboData['recargo'],
                    'iva' => $reciboData['iva'],
                ]);
            }

            DB::commit();
            return back()->with('success', 'Calendario de pagos actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar calendario: ' . $e->getMessage());
        }
    }

    /**
     * Update validity period for a policy (Admin only).
     */
    public function updateValidity(Request $request, Poliza $poliza)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para modificar el periodo de vigencia.');
        }

        // Validation
        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $poliza->update($validated);

            return back()->with('success', 'Periodo de vigencia actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar vigencia: ' . $e->getMessage());
        }
    }
    public function checkAvailability($numero)
    {
        $exists = Poliza::where('numero_poliza', $numero)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkVinAvailability($vin)
    {
        $exists = PolizaVehiculo::where('vin', $vin)->exists();
        return response()->json(['exists' => $exists]);
    }
}
