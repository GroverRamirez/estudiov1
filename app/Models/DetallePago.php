<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    use HasFactory;

    protected $table = 'detalle_pagos';
    protected $primaryKey = 'idDetallePago';

    protected $fillable = [
        'idPago',
        'monto',
        'fecha',
        'comentario'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date'
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'idPago');
    }
}
