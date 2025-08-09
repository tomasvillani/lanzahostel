<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo', // 'c' o 'e'
        'telefono',
        'cv',
        'foto_perfil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación si es empresa (tipo = 'e')
     */
    public function puestos()
    {
        return $this->hasMany(Puesto::class, 'empresa_id');
    }

    /**
     * Relación si es cliente (tipo = 'c')
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'cliente_id');
    }
}
