<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Puesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'fecha',
        'nombre',
        'descripcion',
        'fecha_publicacion',
        'imagen',
    ];

    public function empresa()
    {
        return $this->belongsTo(User::class, 'empresa_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
