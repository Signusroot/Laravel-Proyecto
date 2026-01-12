<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Configuration;

class equipment extends Model
{
    use HasFactory;
    
        protected $fillable = [
        'name_equipment',
        'acronym',
        'ip',
        'model',
        'provider', # Tecnologia alcatel, zte, huawei
        
        #Lugar donde se ubica
        'region',
        'state',
        'locality',
    ];

    #Relacion con las configuraciones
    // Un equipo puede tener muchas configuraciones
    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'equipment_id');
    }
}
