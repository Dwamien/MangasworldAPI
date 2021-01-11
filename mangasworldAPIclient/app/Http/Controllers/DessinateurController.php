<?php

namespace App\Http\Controllers;

use App\Models\Dessinateur;
use Illuminate\Support\Facades\Auth;
use Session;
use GuzzleHttp\Client;

class DessinateurController extends Controller {

    /**
     * Récupère la liste de tous les Dessinateur
     * @return Collection de Dessinateur
     */
    public function getDessinateurs() {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/dessinateur';
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $dessinateurs = json_decode($response->getBody()->getContents());
        return $dessinateurs;
    }

}
