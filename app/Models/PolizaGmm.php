<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolizaGmm extends Model
{
    protected $table = 'poliza_gmm';

    protected $fillable = [
        'poliza_id',
        'endoso',
        'suma_asegurada',
        'deducible',
        'coaseguro',
        'plan',
    ];

    /**
     * Get the policy that owns the GMM record.
     */
    public function poliza(): BelongsTo
    {
        return $this->belongsTo(Poliza::class);
    }
}
