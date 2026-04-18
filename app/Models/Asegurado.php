<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asegurado extends Model
{
    protected $fillable = [
        'nombre',
        'rfc',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'email',
        'telefono',
    ];

    public function polizas(): HasMany
    {
        return $this->hasMany(Poliza::class);
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(AseguradoDireccion::class, 'asegurado_id');
    }
}
