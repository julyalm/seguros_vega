<?php

namespace App\Http\Controllers;

use App\Models\Asegurado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AseguradoController extends Controller
{
    /**
     * Show the specified insured by RFC.
     */
    public function showByRfc(string $rfc)
    {
        $asegurado = Asegurado::with('direcciones')->where('rfc', $rfc)->first();

        if (!$asegurado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asegurado no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $asegurado
        ]);
    }

    public function update(Request $request, string $id)
    {

        Log::info($request->all());
        Log::info('id -> ' . $id);

        $asegurado = Asegurado::find($id);

        if (!$asegurado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asegurado no encontrado'
            ], 404);
        }

        $asegurado->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $asegurado
        ]);
    }
}
