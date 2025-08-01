<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'idRol');
    }
}
