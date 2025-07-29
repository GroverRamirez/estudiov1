<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;

    protected $table = 'trabajos';
    protected $primaryKey = 'idTrabajo';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_entrega',
        'estado',
        'prioridad',
        'precio_total',
        'adelanto',
        'observaciones',
        'idCliente',
        'idServicio',
        'idUsuario'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_entrega' => 'date',
        'precio_total' => 'decimal:2',
        'adelanto' => 'decimal:2'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }



    // Override the getRouteKeyName method to use idTrabajo for route model binding
    public function getRouteKeyName()
    {
        return 'idTrabajo';
    }

    public function detalle()
    {
        return $this->hasOne(DetalleTrabajo::class, 'idTrabajo');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionTrabajo::class, 'idTrabajo');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idTrabajo');
    }
}
