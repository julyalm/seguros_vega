<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aseguradora extends Model
{
    protected $fillable = [
        'nombre',
        'color',
        'inicial',
    ];

    public function polizas(): HasMany
    {
        return $this->hasMany(Poliza::class);
    }
}
