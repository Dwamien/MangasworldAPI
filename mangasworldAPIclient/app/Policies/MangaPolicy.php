<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Manga;

class MangaPolicy
{
    use HandlesAuthorization;
    public function __construct()
    {
        //
    }
    // Seuls les contributeurs qui ont créé le Manga
    // peuvent le modifier
    public function modifier (User $user, Manga $manga){
        $autorized = ($user->role == 'contrib' && $user->id == $manga->id_lecteur);
        return $autorized;
    }    
    // Seul les contributeurs qui ont créé le Manga
    // peuvent le supprimer
    public function supprimer (User $user, Manga $manga){
        $autorized = ($user->role == 'contrib' && $user->id == $manga->id_lecteur);
        return $autorized;        
    }   
}

