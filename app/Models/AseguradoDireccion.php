<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AseguradoDireccion extends Model
{
    protected $table = 'asegurado_direcciones';

    protected $fillable = [
        'asegurado_id',
        'codigo_postal',
        'estado',
        'municipio',
        'colonia',
        'calle',
        'num_exterior',
        'num_interior',
        'alias',
    ];

    public function asegurado(): BelongsTo
    {
        return $this->belongsTo(Asegurado::class);
    }
}
