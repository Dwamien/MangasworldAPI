<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Session;
use GuzzleHttp\Client;

class GenreController extends Controller {

    /**
     * Récupère la liste des genres 
     * @return Collection de Genre
     */
    public function getGenres() {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/genre';
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $genres = json_decode($response->getBody()->getContents());
        return $genres;
    }

}
