<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Lecteur;
use Exception;


class ProfilController extends Controller {

    // Collection des rôles disponibles avec leur libellé
    private $roles = array("admin" => "Administrateur", "comment" => "Commentateur", "contrib" => "Contributeur");

    /**
     * Initialise le formulaire de saisie d'un profil
     * @return Vue profil
     */
    public function show($id) {
        $lecteur = Lecteur::find($id);
        return response()->json($lecteur, 200);
    }

    /**
     * Enregistre le profil
     * @return Vue home
     */
    public function update(Request $request) {
        try {
            // messages d'erreurs personnalisés
            $messages = array(
                'nom.required' => 'Il faut saisir un nom.',
                'prenom.required' => 'Il faut saisir un prénom.', 
                'cp.required' => 'Il faut saisir un code postal.',
                'cp.numeric' => 'Le code postal doit être une valeur numérique.'            
            );
            //règles à vérifier
            $regles = array(
                'nom' => 'required', 
                'prenom' => 'required',
                'cp' => 'numeric'
            );

            $validator = Validator::make($request->all(), $regles, $messages);

            // on retourne un Json avec les messages d'erreur si la validation échoue
            if($validator->fails()){
                return response()->json($validator->errors()->messages(), 500);
            }

            // on récupère les données et on les enregistre
            // et on retourne un Json du lecteur
            $id_lecteur = $request->input('id_lecteur');
            $lecteur = Lecteur::find($id_lecteur);
            $lecteur->nom = $request->input('nom');
            $lecteur->prenom = $request->input('prenom');
            $lecteur->rue = $request->input('rue');
            $lecteur->cp = $request->input('cp');
            $lecteur->ville = $request->input('ville');
            $lecteur->save();
            return response()->json($lecteur, 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }
    
    public function indexLecteur() {
        $lecteurs = Lecteur::all();
        return response()->json($lecteurs, 200);
    }    
    
}
