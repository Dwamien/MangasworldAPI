<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Manga;


class MangaController extends Controller {

    public function show($id) {
        $manga = Manga::find($id);
        return response()->json($manga, 200);
    }

    /**
     * Retourne la liste de tous les Mangas
     * @return Collection de Manga
     */
    public function index() {
        $mangas = Manga::all();
        return response()->json($mangas, 200);
    }

    /**
     * Retourne la liste des tous les Mangas d'un Genre
     * @param int $id Id du genre
     * @return Collection de Manga
     */
    public function getMangasGenre($id) {
        $mangas = Manga::where('id_genre', $id)->get();
        return response()->json($mangas, 200);
    }

    /**
     * Ajoute un Manga
     * @return Manga créé
     */
    public function store(Request $request) {
        try {
            $manga = new Manga();
            $manga->id_dessinateur = $request->input('id_dessinateur');
            $manga->id_scenariste = $request->input('id_scenariste');
            $manga->id_genre = $request->input('id_genre');
            $manga->prix = $request->input('prix');
            $manga->titre = $request->input('titre');
            $manga->id_lecteur = $request->input('id_lecteur');
            $manga->couverture = $request->input('couverture');
            $manga->save();
            return response()->json($manga, 201); 
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
               
    }

    /**
     * Mise à jour d'un Manga
     * @return Manga modifié
     */
    public function update(Request $request) {
        try {
            $id_manga = $request->input('id_manga');
            $manga = Manga::find($id_manga);
            $manga->id_dessinateur = $request->input('id_dessinateur');
            $manga->id_scenariste = $request->input('id_scenariste');
            $manga->id_genre = $request->input('id_genre');
            $manga->prix = $request->input('prix');
            $manga->titre = $request->input('titre');
            $manga->id_lecteur = $request->input('id_lecteur');
            $manga->couverture = $request->input('couverture');
            $manga->save();
            return response()->json($manga, 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }

    }

    /**
     * Supression d'un Manga sur son Id
     * @param int $id : Id du Manga à supprimer
     * @return Message
     */
    public function delete($id) {
        try {
            $manga = Manga::find($id);
            $manga->delete();
            return response()->json("Abracamanga, ce manga a disparu ! ", 200);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }

    }
} 