<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recibo extends Model
{
    protected $fillable = [
        'poliza_id',
        'indice_recibo',
        'monto',
        'prima_neta',
        'derechos',
        'recargo',
        'iva',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'periodo_gracia',
        'comision',
        'status',
        'contracargo',
        'fecha_vencimiento',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio_vigencia' => 'date',
            'fecha_fin_vigencia' => 'date',
            'fecha_vencimiento' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getStatusDisplayAttribute()
    {
        if ($this->status === 'pendiente' && $this->fecha_vencimiento && now()->startOfDay()->greaterThan($this->fecha_vencimiento)) {
            return 'vencido';
        }
        return $this->status;
    }

    public function poliza(): BelongsTo
    {
        return $this->belongsTo(Poliza::class);
    }
}
