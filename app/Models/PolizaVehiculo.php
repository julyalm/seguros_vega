<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolizaVehiculo extends Model
{
    protected $table = 'poliza_vehiculos';

    protected $fillable = [
        'poliza_id',
        'inciso',
        'tipo',
        'modelo',
        'marca',
        'submarca',
        'vin',
        'motor',
        'placas',
    ];

    /**
     * Get the policy that owns the vehicle.
     */
    public function poliza(): BelongsTo
    {
        return $this->belongsTo(Poliza::class);
    }
}
