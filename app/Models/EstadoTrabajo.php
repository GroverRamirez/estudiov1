<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoTrabajo extends Model
{
    use HasFactory;

    protected $table = 'estados_trabajo';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre'
    ];

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'idEstado');
    }
}
