<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionTrabajo extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_trabajos';
    protected $primaryKey = 'idAsignacion';

    protected $fillable = [
        'idTrabajo',
        'idUsuarioEncargado',
        'turno',
        'fechaAsignacion'
    ];

    protected $casts = [
        'fechaAsignacion' => 'date'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'idTrabajo');
    }

    public function usuarioEncargado()
    {
        return $this->belongsTo(User::class, 'idUsuarioEncargado');
    }
}
