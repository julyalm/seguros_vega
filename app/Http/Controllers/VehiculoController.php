<?php

namespace App\Http\Controllers;

use App\Models\PolizaVehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{

    public function update(Request $request, $vin)
    {
        $vehiculo = PolizaVehiculo::where('vin', $vin)->firstOrFail();
        $vehiculo->update($request->all());
        return redirect()->back();
    }

}
