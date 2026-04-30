<?php

namespace App\Http\Controllers\Api;

use App\Models\Sepomex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SepomexController extends Controller
{
    /**
     * Look up CP information from Sepomex table.
     */
    public function lookup(string $cp)
    {
        $results = Sepomex::where('d_codigo', $cp)
            ->orWhere('d_cp', $cp)
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Código Postal no encontrado'
            ], 404);
        }

        // SEPOMEX tables usually have duplicates for different colonies (d_asenta)
        // We take the first result for State and Municipality as they should be the same for one CP
        $first = $results->first();
        
        $colonias = $results->pluck('d_asenta')->unique()->values()->toArray();

        return response()->json([
            'status' => 'success',
            'data' => [
                'cp' => $cp,
                'estado' => $first->d_estado,
                'municipio' => $first->d_mnpio,
                'colonias' => $colonias
            ]
        ]);
    }
}
