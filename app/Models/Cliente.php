<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';
    
    // Override the getRouteKeyName method to use idCliente for route model binding
    public function getRouteKeyName()
    {
        return 'idCliente';
    }

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'estado',
        'observaciones',
        'fecha_registro',
        'idUsuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'idCliente');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idCliente');
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre;
    }
}
