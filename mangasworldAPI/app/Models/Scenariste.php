<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scenariste extends Model
{
    protected $table = 'scenariste';
    public $timestamps = false;
    protected $primaryKey = 'id_scenariste';
    protected $fillable = ['id_scenariste', 'nom_scenariste', 'prenom_scenariste'];
    public function mangas() {
        return $this->hasMany('App\Models\Manga', 'id_scenariste', 'id_scenariste');
    }    
}
