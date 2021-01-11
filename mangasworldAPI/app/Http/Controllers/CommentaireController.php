<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Commentaire;
use App\Models\Manga;
use Illuminate\Http\Request;



class CommentaireController extends Controller {

    /**
     * Retourne un Commentaire ayant l'Id
     * passé en paramètre
     * @param type $id Id du Commentaire à retourner
     * @return type
     */
    public function show($id) {
        $commentaire = Commentaire::find($id);
        return response()->json($commentaire, 200);
        
    }

    /**
     * Retourne la liste de tous les Commentaire
     * @return Collection de Manga
     */
    public function index() {
        $commentaires = Commentaire::all();
        return response()->json($commentaires, 200);
    }

    /**
     * Retourne la liste des tous les Commentaires d'un Manga
     * @param int $id Id du Manga
     * @return Collection de Commentaires
     */
    public function getCommentairesManga($id) {
        $commentaires = Commentaire::where('id_manga', $id)->get();
        return response()->json($commentaires, 200);
    }

    /**
     * Ajoute un Manga
     * @return Manga créé
     */
    public function store(Request $request) {
        try {
            $commentaire = new Commentaire();
            $commentaire->lib_commentaire = $request->input('lib_commentaire');
            $commentaire->id_manga = $request->input('id_manga');
            $commentaire->id_lecteur = $request->input('id_lecteur');
            $commentaire->save();
            return response()->json($commentaire, 201);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

    /**
     * Mise à jour d'un Commentaire
     * @return Commentaire modifié
     */
    public function update(Request $request) {
        try {
            $id_commentaire = $request->input('id_commentaire');
            $commentaire = Commentaire::find($id_commentaire);
            $commentaire->lib_commentaire = $request->input('lib_commentaire');
            $commentaire->id_manga = $request->input('id_manga');
            $commentaire->id_lecteur = $request->input('id_lecteur');
            $commentaire->save();
            return response()->json($commentaire, 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

    /**
     * Supression d'un Commentaire sur son Id
     * @param int $id : Id du Commentaire à supprimer
     * @return Message
     */
    public function delete($id) {
        try {
            $commentaire = Commentaire::find($id);
            $commentaire->delete();
            return response()->json("Hop, un peu de typex : le commentaire est effacé ! ", 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

}
