<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sepomex extends Model
{
    // The table was created manually by the user
    protected $table = 'sepomex';

    // Disable timestamps if the table doesn't have them or uses strings
    public $timestamps = false;

    protected $fillable = [
        'd_codigo',
        'd_asenta',
        'd_tipo_asenta',
        'd_mnpio',
        'd_estado',
        'd_ciudad',
        'd_cp',
        'c_estado',
        'c_oficina',
        'c_cp',
        'c_tipo_asenta',
        'c_mnpio',
        'id_asenta_cp_cons',
        'd_zona',
        'c_cve_ciudad',
    ];
}
