<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
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
        return $this->nombre . ' ' . $this->apellido;
    }
}
