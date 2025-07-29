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
        'monto',
        'fecha_pago',
        'metodo_pago',
        'referencia',
        'estado',
        'tipo_pago',
        'observaciones',
        'idTrabajo',
        'idUsuario'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'idTrabajo');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    // Override the getRouteKeyName method to use idPago for route model binding
    public function getRouteKeyName()
    {
        return 'idPago';
    }

    // Scopes para filtros
    public function scopeCompletado($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeCancelado($query)
    {
        return $query->where('estado', 'cancelado');
    }

    public function scopeReembolsado($query)
    {
        return $query->where('estado', 'reembolsado');
    }

    public function scopePorMetodo($query, $metodo)
    {
        return $query->where('metodo_pago', $metodo);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_pago', $tipo);
    }

    public function scopePorFecha($query, $fechaDesde = null, $fechaHasta = null)
    {
        if ($fechaDesde) {
            $query->where('fecha_pago', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fecha_pago', '<=', $fechaHasta);
        }
        return $query;
    }

    // Accessors
    public function getMontoFormateadoAttribute()
    {
        return '$' . number_format($this->monto, 2, ',', '.');
    }

    public function getEstadoColorAttribute()
    {
        $colores = [
            'completado' => 'green',
            'pendiente' => 'yellow',
            'cancelado' => 'red',
            'reembolsado' => 'blue'
        ];
        return $colores[$this->estado] ?? 'gray';
    }

    public function getMetodoPagoColorAttribute()
    {
        $colores = [
            'efectivo' => 'green',
            'tarjeta' => 'blue',
            'transferencia' => 'purple',
            'deposito' => 'yellow',
            'otro' => 'gray'
        ];
        return $colores[$this->metodo_pago] ?? 'gray';
    }

    public function getTipoPagoColorAttribute()
    {
        $colores = [
            'adelanto' => 'blue',
            'pago_parcial' => 'yellow',
            'pago_completo' => 'green',
            'reembolso' => 'red'
        ];
        return $colores[$this->tipo_pago] ?? 'gray';
    }
}
