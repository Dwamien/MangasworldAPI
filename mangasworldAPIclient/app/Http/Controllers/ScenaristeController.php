<?php

namespace App\Http\Controllers;

use App\Models\Scenariste;
use Illuminate\Support\Facades\Auth;
use Session;
use GuzzleHttp\Client;

class ScenaristeController extends Controller {

    /**
     * Récupère la liste des scenaristes
     * @return Collection de Scenariste
     */
    public function getScenaristes() {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/scenariste';
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $scenaristes = json_decode($response->getBody()->getContents());
        return $scenaristes;
    }

}
