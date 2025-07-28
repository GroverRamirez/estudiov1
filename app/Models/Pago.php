<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'idPago';

    protected $fillable = [
        'idTrabajo',
        'idCliente',
        'montoTotal',
        'aCuenta',
        'saldoCalculado',
        'fechaPago',
        'idEstadoPago'
    ];

    protected $casts = [
        'montoTotal' => 'decimal:2',
        'aCuenta' => 'decimal:2',
        'saldoCalculado' => 'decimal:2',
        'fechaPago' => 'date'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'idTrabajo');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPago::class, 'idEstadoPago');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePago::class, 'idPago');
    }
}
