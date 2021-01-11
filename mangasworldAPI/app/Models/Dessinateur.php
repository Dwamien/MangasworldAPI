<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dessinateur extends Model
{
    protected $table = 'dessinateur';
    public $timestamps = false;
    protected $primaryKey = 'id_dessinateur';
    protected $fillable = ['id_dessinateur', 'nom_dessinateur', 'prenom_dessinateur'];
    public function mangas (){
        return $this->hasMany('App\Models\Manga', 'id_dessinateur', 'id_dessinateur');
    }    
}
