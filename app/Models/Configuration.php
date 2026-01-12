<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;
use App\Models\User;

class configuration extends Model
{
    use HasFactory;

        protected $fillable = [
        'version',      #version
        'upload_date',  #Fecha de carga del archivo
        'doc_date',     #Fecha del documento
        'doc_size',     #Tamaño del archivo
        'doc_name',    #Nombre del archivo
        'doc_content',  #Contenido del archivo
        'user_id',        # autor
        'equipment_id',   # FK al equipo
        'status_equipment_id', # FK a status_equipment (activo, desincorporado)
        ];

    #Relacion con Equipos
    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    // Relación: una configuración pertenece a un usuario (autor)
    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
