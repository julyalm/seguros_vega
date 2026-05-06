<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Poliza extends Model
{
    protected $fillable = [
        'numero_poliza',
        'ramo',
        'user_id',
        'asegurado_id',
        'aseguradora_id',
        'fecha_inicio',
        'fecha_fin',
        'prima_total',
        'prima_neta',
        'derechos',
        'recargo',
        'iva',
        'comision',
        'frecuencia_pago',
        'es_flotilla',
        'flotilla_existente',
        'inciso',
        'file_path',
        'recibo_path',
        'parent_id',
        'tipo_movimiento',
        'is_active',
    ];

    /**
     * Get the parent policy (if this is an inclusion).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Poliza::class, 'parent_id');
    }

    /**
     * Get the inclusions for this fleet policy.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Poliza::class, 'parent_id');
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'es_flotilla' => 'boolean',
            'flotilla_existente' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the agent who owns the policy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the policyholder (asegurado).
     */
    public function asegurado(): BelongsTo
    {
        return $this->belongsTo(Asegurado::class);
    }

    /**
     * Get the insurance company (aseguradora).
     */
    public function aseguradora(): BelongsTo
    {
        return $this->belongsTo(Aseguradora::class);
    }

    /**
     * Get the payment receipts for the policy.
     */
    public function recibos(): HasMany
    {
        return $this->hasMany(Recibo::class);
    }

    /**
     * Get the vehicles (items) for the policy (Autos).
     */
    public function vehiculos(): HasMany
    {
        return $this->hasMany(PolizaVehiculo::class);
    }

    /**
     * Get the GMM details for the policy.
     */
    public function gmm(): HasOne
    {
        return $this->hasOne(PolizaGmm::class);
    }
}
