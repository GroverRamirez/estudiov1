<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'idRol'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'idUsuario');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'idUsuario');
    }

    public function trabajos()
    {
        return $this->hasMany(Trabajo::class, 'idUsuario');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionTrabajo::class, 'idUsuarioEncargado');
    }
}
