<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    protected $table = 'manga';
    public $timestamps = false;
    protected $primaryKey = 'id_manga';
    protected $fillable = ['id_manga','id_dessinateur', 'id_scenariste', 'prix','titre',  'couverture',  'id_genre',' id_lecteur'];
    
    public function dessinateur()
    {
        return $this->belongsTo('App\Models\Dessinateur', 'id_dessinateur', 'id_dessinateur');
    }
    
    public function scenariste()
    {
        return $this->belongsTo('App\Models\Scenariste', 'id_scenariste', 'id_scenariste');
    }    
    
    public function genre()
    {
        return $this->belongsTo('App\Models\Genre', 'id_genre', 'id_genre');
    } 
    
    public function commentaires()
    {
        return $this->hasMany('App\Models\Commentaire', 'id_manga', 'id_manga');
    }     
    
    public function lecteur()
    {
        return $this->belongsTo('App\Models\Lecteur', 'id_lecteur', 'id_lecteur');
    }    
    
    /**Redéfinit toArray() de Model pour former
     * un tableau qui sera transformé en JSON
     * @return type tableau de propriétés
     */
    public function toArray(){
        $data = parent::toArray();
        $data['lecteur']=$this->lecteur;
        $data['genre']=$this->genre;
        $data['dessinateur']=$this->dessinateur;
        $data['scenariste']=$this->scenariste;
        $data['commentaires']=$this->commentaires;
        return $data;         
    }
    
}
