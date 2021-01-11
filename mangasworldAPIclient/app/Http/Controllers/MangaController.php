<?php

namespace App\Http\Controllers;

use Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Commentaire;
use App\Models\Manga;
use App\Models\Genre;
use App\Models\Dessinateur;
use App\Models\Scenariste;

class MangaController extends Controller {

    /**
     * Affiche la liste de tous les Mangas
     * @return Vue listerMangas
     */
    public function getMangas() {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        // On récupère l'utilisateur en cours pour pouvoir accéder à son jeton d'authentification
        $user = Auth::guard()->user();
        $client = new Client();
        $response = $client->request('GET', 'http://localhost/mangasworldAPI/public/api/manga', 
                ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $mangas = json_decode($response->getBody()->getContents());
        // On affiche la liste de ces mangas        
        return view('listeMangas', compact('mangas', 'erreur'));
    }

    /**
     * Afficher la liste des tous les Mangas d'un Genre
     * Si on a sélectionné un genre, on récupère tous les
     * mangas de ce genre et on les affiche
     * Si on n'a pas sélectionné de genre, on construit
     * un message d'erreur et on relance le formulaire 
     * de sélection d'un genre en lui passant le message
     * @return Vue listerMangas
     */
    public function getMangasGenre() {
        $erreur = "";
        // On récupère l'id du genre sélectionné
        $id_genre = Request::input('cbGenre');
        // Si on a un id de genre
        if ($id_genre) {
            // On récupère la liste de tous les mangas du genre choisi
            $user = Auth::guard()->user();
            $client = new Client();
            $uri = 'http://localhost/mangasworldAPI/public/api/manga/genre/' . $id_genre;
            $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
            $mangas = json_decode($response->getBody()->getContents());
            // On affiche la liste de ces mangas
            return view('listeMangas', compact('mangas', 'erreur'));
        } else {
            $erreur = "Il faut sélectionner un genre !";
            Session::put('erreur', $erreur);
            return redirect('/listerGenres');
        }
    }

    /**
     * Formulaire de consultation d'un manga
     * Initialise toutes les listes déroulantes
     * Lit le manga à modifier
     * Initialise le formulaire en mode Consultation
     * @param int $id Id du Manga à consulter
     * @return Vue formManga
     */
    public function showManga($id) {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $readonly = 'readonly';
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/' . $id;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $manga = json_decode($response->getBody()->getContents());
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $genres = collect($genres);
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $dessinateurs = collect($dessinateurs);
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        $scenaristes = collect($scenaristes);
        $titreVue = "Consultation d'un Manga";
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formManga', compact('manga', 'genres', 'dessinateurs',
                        'scenaristes', 'titreVue', 'readonly', 'erreur'));
    }

    /**
     * Formulaire de modification d'un Manga.
     * Initialise toutes les listes déroulantes
     * Lit le manga à modifier.
     * @param int $id Id du Manga à modifier
     * @param string $erreur message d'erreur (paramètre optionnel)
     * @return Vue formManga
     */
    public function updateManga($id) {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $readonly = null;
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/' . $id;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
        $manga = json_decode($response->getBody()->getContents());
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $genres = collect($genres);
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $dessinateurs = collect($dessinateurs);
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        $scenaristes = collect($scenaristes);
        $titreVue = "Modification d'un Manga";
        // On vérifie que l'utilisateur a bien le droit de modifier
        if (!($user->role == 'contrib' && $user->id == $manga->id_lecteur)) {
            $erreur = "Vous ne pouvez modifier que les Mangas que vous avez créés !";
            $readonly = 'readonly';
        }
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formManga', compact('manga', 'genres', 'dessinateurs',
                        'scenaristes', 'titreVue', 'readonly', 'erreur'));
    }

    /**
     * Formulaire d'ajout d'un Manga
     * Initialise toutes les listes déroulantes
     * et place le formulaire formManga en mode ajout
     * @return Vue formManga
     */
    public function addManga() {
        $manga = new Manga();
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $readonly = null;
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $genres = collect($genres);
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $dessinateurs = collect($dessinateurs);
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        $scenaristes = collect($scenaristes);
        $titreVue = "Ajout d'un Manga";
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formManga', compact('manga', 'genres', 'dessinateurs', 'scenaristes', 'titreVue', 'readonly', 'erreur'));
    }

    /**
     * Enregistre une mise à jour d'un Manga 
     * Avant d'enregistrer on vérifie que l'utilisateur
     * demandeur a bien le droit de le faire. Si ce n'est
     * pas le cas on propage une Exception qui sera récupérée
     * dans le gestionnaire d'exceptions
     * Si la modification d'un Manga
     * provoque une erreur fatale, on la place
     * dans la Session et on réaffiche le formulaire
     * Sinon réaffiche la liste des mangas
     * @return Redirection listerMangas
     */
    public function validateManga() {
        // Récupération des valeurs saisies
        $id_manga = Request::input('id_manga'); // id dans le champs caché
        // Liste des champs à vérifier
        $regles = array(
            'titre' => 'required',
            'prix' => 'required | numeric',
            'cbScenariste' => 'required',
            'cbGenre' => 'required',
            'cbDessinateur' => 'required'
        );
        // Messages d'erreur personnalisés
        $messages = array(
            'titre.required' => 'Il faut saisir un titre.',
            'cbGenre.required' => 'Il faut sélectionner un genre.',
            'cbScenariste.required' => 'Il faut sélectionner un scénariste.',
            'cbDessinateur.required' => 'Il faut sélectionner un dessinateur.',
            'prix.required' => 'Il faut saisir un prix.',
            'prix.numeric' => 'Le prix doit être une valeur numérique.'
        );
        $validator = Validator::make(Request::all(), $regles, $messages);
        // On retourne au formulaire s'il y a un problème
        if ($validator->fails()) {
            if ($id_manga > 0) {
                return redirect('modifierManga/' . $id_manga)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                return redirect('ajouterManga/')
                                ->withErrors($validator)
                                ->withInput();
            }
        }
        $id_dessinateur = Request::input('cbDessinateur'); // Liste déroulante
        $prix = Request::input('prix');
        $id_scenariste = Request::input('cbScenariste'); // Liste déroulante
        $titre = Request::input('titre');
        $id_genre = Request::input('cbGenre'); // Liste déroulante
        // Si on a uploadé une image, il faut la sauvegarder
        // Sinon on récupère le nom dans le champ caché
        if (Request::hasFile('couverture')) {
            $image = Request::file('couverture');
            $couverture = $image->getClientOriginalName();
            Request::file('couverture')->move(base_path() . '/public/images/', $couverture);
        } else {
            $couverture = Request::input('couvertureHidden');
        }
        $erreur = "";
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/manga';
        $manga = new Manga();
        $manga->titre = $titre;
        $manga->couverture = $couverture;
        $manga->prix = $prix;
        $manga->id_dessinateur = $id_dessinateur;
        $manga->id_scenariste = $id_scenariste;
        $manga->id_genre = $id_genre;
        $manga->id_lecteur = $user->id;
        try {
            if ($id_manga > 0) {
                $manga->id_manga = $id_manga;
                $data = $manga->toArray();
                $response = $client->request('PUT', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token], 'query' => $data]);
                $manga = json_decode($response->getBody()->getContents());
            } else {
                $data = $manga->toArray();
                $response = $client->request('POST', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token], 'query' => $data]);
                $manga = json_decode($response->getBody()->getContents());
            }
        } catch (Exception $ex) {
            $erreur = $ex->getMessage();
            Session::put('erreur', $erreur);
            if ($id_manga > 0) {
                return redirect('/modifierManga/' . $id_manga);
            } else {
                return redirect('/ajouterManga/');
            }
        }
        // On réaffiche la liste des mangas
        return redirect('/listerMangas');
    }

    /**
     * Supression d'un Manga sur son Id
     * Si la suppression provoque une erreur fatale
     * on la place dans la Session
     * Dans tous les cas on réaffiche la liste des mangas
     * @param int $id : Id du Manga à supprimer
     * @return Redirection listerMangas
     */
    public function deleteManga($id) {
        $erreur = "";
        try {
            $user = Auth::user();
            if (!$user->role == 'contrib' && $user->id == $manga->id_lecteur) {
                $erreur = 'Vous ne disposez pas des droits pour supprimer ce Manga !';
                Session::put('erreur', $erreur);
                return $this->getMangas();
            }
            $client = new Client();
            $uri = 'http://localhost/mangasworldAPI/public/api/manga/' . $id;
            $client->request('DELETE', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
            return redirect('/listerMangas');
        } catch (Exception $ex) {
            Session::put('erreur', $ex->getMessage());
            return redirect('/listerMangas');
        }
    }

}
