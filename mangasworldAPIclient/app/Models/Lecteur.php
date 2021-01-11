<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecteur extends Model
{
    protected $table = 'lecteur';
    public $timestamps = false;
    protected $primaryKey = 'id_lecteur';
    protected $fillable = ['id_lecteur', 'nom', 'prenom', 'rue', 'cp', 'ville'];
    public function mangas() {
        return $this->hasMany('App\Models\Manga', 'id_lecteur', 'id_lecteur');
    }   
    public function commentaires() {
        return $this->hasMany('App\Models\Commentaire', 'id_lecteur', 'id_lecteur');
    }     
}
