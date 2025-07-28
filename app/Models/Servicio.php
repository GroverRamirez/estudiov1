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
        'nombreServicio',
        'precio',
        'idUsuario'
    ];

    protected $casts = [
        'precio' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'idServicio');
    }
}
