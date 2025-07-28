<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTrabajo extends Model
{
    use HasFactory;

    protected $table = 'detalle_trabajo';
    protected $primaryKey = 'idDetalle';

    protected $fillable = [
        'idTrabajo',
        'descripcion',
        'tamano',
        'color',
        'modelo',
        'cantidad',
        'tipoDocumento',
        'tipoEvento',
        'otros'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'idTrabajo');
    }
}
