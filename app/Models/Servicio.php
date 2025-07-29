<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'idServicio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'estado',
        'duracion_estimada',
        'imagen',
        'idUsuario'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_estimada' => 'integer'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'idServicio');
    }

    // Override the getRouteKeyName method to use idServicio for route model binding
    public function getRouteKeyName()
    {
        return 'idServicio';
    }
}
