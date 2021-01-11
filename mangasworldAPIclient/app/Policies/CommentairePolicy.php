<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Commentaire;

class CommentairePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // Seuls les commentateurs qui ont créé le Commentaire
    // peuvent le modifier
    public function modifier (User $user, Commentaire $commentaire){
        $autorized = ($user->role == 'comment' && $user->id == $commentaire->id_lecteur);
        return $autorized;
    }    
    // Seul les commentateurs qui ont créé le Commentaire
    // peuvent le supprimer
    public function supprimer (User $user, Commentaire $commentaire){
        $autorized = ($user->role == 'comment' && $user->id == $commentaire->id_lecteur);
        return $autorized;        
    }     
}
