<?php
namespace App\Http\Controllers;

use App\Models\Scenariste;


class ScenaristeController extends Controller
{
    /**
     * Retourne la liste de tous les Scenariste
     * @return Collection de Scenariste
     */
    public function index() {
        $scenaristes = Scenariste::all();
        return response()->json($scenaristes, 200);
    }  
    
}

