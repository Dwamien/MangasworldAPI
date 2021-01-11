<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dessinateur;
use Exception;


class DessinateurController extends Controller {

    /**
     * Retourne la liste de tous les Dessinateur
     * @return Collection de Dessinateur
     */
    public function index() {
        $dessinateurs = Dessinateur::all();
        return response()->json($dessinateurs, 200);
    }

    /**
     * Création d'un nouveau Dessinateur
     * @param Request $request embarquant le Dessinateur au format JSON
     * @return type Dessinateur créé
     */
    public function store(Request $request) {
        try {
            $dessinateur = new Dessinateur();
            $dessinateur->prenom_dessinateur = $request->input('prenom_dessinateur');
            $dessinateur->nom_dessinateur = $request->input('nom_dessinateur');
            $dessinateur->save();
            return response()->json($dessinateur, 201);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }      
    }

    /**
     * Modification d'un Dessinateur
     * @param Request $request embarquant le Dessinateur au format JSON
     * @return type Dessinateur créé
     */
    public function update(Request $request) {
        try {
            $id_dessinateur = $request->input('id_dessinateur');
            $dessinateur = Dessinateur::find($id_dessinateur);
            $dessinateur->prenom_dessinateur = $request->input('prenom_dessinateur');
            $dessinateur->nom_dessinateur = $request->input('nom_dessinateur');
            $dessinateur->save();
            return response()->json($dessinateur, 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

    /**
     * Supression d'un Dessinateur sur son Id
     * @param int $id : Id du Dessinateur à supprimer
     * @return Message
     */
    public function delete($id) {
        try {
            $dessinateur = Dessinateur::find($id);
            $dessinateur->delete();
            return response()->json("Suppression du dessinateur confirmée... On entendra plus parler de lui! ");
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

}
