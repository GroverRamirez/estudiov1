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
        'idCliente',
        'idServicio',
        'idUsuario',
        'fechaRegistro',
        'fechaEntrega',
        'idEstado'
    ];

    protected $casts = [
        'fechaRegistro' => 'date',
        'fechaEntrega' => 'date'
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

    public function estado()
    {
        return $this->belongsTo(EstadoTrabajo::class, 'idEstado');
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
