<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genre';
    public $timestamps = false;
    protected $primaryKey = 'id_genre';
    protected $fillable = ['id_genre', 'lib_genre'];
    public function mangas() {
        return $this->hasMany('App\Models\Manga', 'id_genre', 'id_genre');
    }    
}
