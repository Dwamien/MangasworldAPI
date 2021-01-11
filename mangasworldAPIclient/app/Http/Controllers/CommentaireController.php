<?php

namespace App\Http\Controllers;

use Request;
use Exception;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Commentaire;
use App\Models\Manga;
use GuzzleHttp\Client;
use App\Models\Lecteur;


class CommentaireController extends Controller {

    /**
     * Affiche la liste de tous les Commentaires
     * Si la Session contient un message d'erreur, 
     * on le récupère et on le supprime de la Session
     * @return Vue listerCommentaires
     */
    public function getCommentaires($idManga) {
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        // On récupère la liste des commentaires sur le manga choisi
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/'.$idManga;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $manga = json_decode($response->getBody()->getContents());
        $commentaires = $manga->commentaires;
        // On récupère la liste des lecteurs
        $lecteursController = new ProfilController();
        $lecteurs = $lecteursController->getLecteurs();
        // On affiche la liste des commentaires de ce manga        
        return view('listeCommentaires', compact('commentaires', 'manga', 'erreur', 'lecteurs'));
    }

    /**
     * Lit le Commentaire à modifier et récupère le Manga
     * auquel il est rattaché. Vérifie que l'utilisateur
     * connecté a bien le droit de le modifier et 
     * initialise le formulaire en mode Modification si
     * c'est le cas sinon l'initialise en mode Consultation
     * @param int $id Id du Commentaire à modifier
     * @param string $erreur message d'erreur (paramètre optionnel)
     * @return Vue formCommentaire
     */
    public function updateCommentaire($idCommentaire) {
        $readonly = null;
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        //On récupère le commentaire choisi
        $user = Auth::guard()->user();
        $client = new Client();
        $uri1 = 'http://localhost/mangasworldAPI/public/api/commentaire/'.$idCommentaire;
        $response1 = $client->request('GET', $uri1, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $commentaire = json_decode($response1->getBody()->getContents());
        //On récupère le manga choisi
        $id_manga = $commentaire->id_manga;
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/'.$id_manga;
        $response2 = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $manga = json_decode($response2->getBody()->getContents());
        $titreVue = "Modification d'un Commentaire";
        //On récupère les listes de genres, dessinateurs et scénaristes
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        //On vérifie qu'il s'agit bien du comment qui a créé le commentaire
        if ($user != null) {
            if (!($user->role == 'comment' && $user->id == $commentaire->id_lecteur)) {
                $erreur = 'Vous ne pourrez que consulter ce commentaire, mais pas le modifier';
                $readonly = 'readonly';
            }
        }
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formCommentaire', compact('manga', 'commentaire', 'titreVue', 'readonly', 'erreur', 'genres', 'dessinateurs', 'scenaristes'));
    }

    /**
     * Lit le Commentaire à consulter et récupère le Manga
     * auquel il est rattaché. Vérifie que l'utilisateur
     * connecté a bien le droit de le consulter et 
     * initialise le formulaire en mode Modification si
     * c'est le cas sinon l'initialise en mode Consultation
     * @param int $id Id du Commentaire à consulter
     * @param string $erreur message d'erreur (paramètre optionnel)
     * @return Vue formCommentaire
     */
    public function showCommentaire($idCommentaire) { //mais plus de guest, donc méthode obsolète... 
        $readonly = null;
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $commentaire = new Commentaire();
        $client = new Client();
        //On récupère le manga correspondant
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/'.$idManga;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $manga = json_decode($response->getBody()->getContents());
        //On récupère les listes de genres, dessinateurs et scénaristes
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        $titreVue = "Consultation d'un Commentaire";
        $user = Auth::user();
        if ($user != null) {
            if (!($user->role == 'comment' && $user->id == $commentaire->id_lecteur)) {
                $erreur = 'Vous ne pourrez que consulter ce commentaire, mais pas le modifier';
                $readonly = 'readonly';
            }
        } else{
            $readonly = 'readonly';
        }
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formCommentaire', compact('manga', 'commentaire', 'titreVue', 'readonly', 'erreur', 'dessinateurs', 'genres', 'scenaristes'));
    }

    /**
     * Enregistre une mise à jour d'un Commentaire 
     * après avoir vérifié que l'utilisateur est bien
     * habilité à le faire
     * Si la modification d'un Commentaire
     * provoque une erreur fatale, on la place
     * dans la Session et on réaffiche le formulaire
     * Sinon réaffiche la liste des mangas
     * @return Redirection listerCommentaires
     */
    public function validateCommentaire() {
        // Récupération des valeurs saisies
        $idManga = Request::input('id_manga'); // id dans le champs caché
        $idCommentaire = Request::input('id_commentaire'); // id dans le champs caché
        $libCommentaire = Request::input('lib_commentaire');
        $erreur = "";
        $user = Auth::guard()->user();
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/commentaire';
        $commentaire = new Commentaire();
        $commentaire->lib_commentaire = $libCommentaire;
        $commentaire->id_lecteur = Auth::user()->id;
        $commentaire->id_manga = $idManga;
        try {
            if ($idCommentaire > 0) {
                $commentaire->id_commentaire = $idCommentaire;
                $data = $commentaire->toArray();
                $response = $client->request('PUT', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token], 'query' => $data]);
                $commentaire = json_decode($response->getBody()->getContents());
            } else {
                $data = $commentaire->toArray();
                $response = $client->request('POST', $uri, ['headers' => ['Authorization' => 'Bearer ' . $user->api_token], 'query' => $data]);
                $commentaire = json_decode($response->getBody()->getContents()); 
            }
        } catch (Exception $ex) {
            $erreur = $ex->getMessage();
            Session::put('erreur', $erreur);
        }
        // On réaffiche la liste des mangas
        return redirect('/listerCommentaires/' . $idManga);
    }

    /**
     * Initialise le formulaire d'ajout d'un commentaire
     * sous réserve que l'utilisateur en ait bien le droit
     * @return Vue formCommentaire
     */
    public function addCommentaire($idManga) {
        $readonly = null;
        $erreur = Session::get('erreur');
        Session::forget('erreur');
        $user = Auth::guard()->user();
        if (!$user->role == 'comment') {
            $erreur = 'Vous ne disposez pas des droits pour ajouter des commentaires !';
            Session::put('erreur', $erreur);
            return $this->getCommentaires($idManga);
        }
        $commentaire = new Commentaire();
        $client = new Client();
        //On récupère le manga correspondant
        $uri = 'http://localhost/mangasworldAPI/public/api/manga/'.$idManga;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $manga = json_decode($response->getBody()->getContents());
        //On récupère les listes de genres, dessinateurs et scénaristes
        $genreController = new GenreController();
        $genres = $genreController->getGenres();
        $dessinateurController = new DessinateurController();
        $dessinateurs = $dessinateurController->getDessinateurs();
        $scenaristeController = new ScenaristeController();
        $scenaristes = $scenaristeController->getScenaristes();
        $titreVue = "Ajout d'un Commentaire";
        // Affiche le formulaire en lui fournissant les données à afficher
        return view('formCommentaire', compact('manga', 'commentaire', 'titreVue', 'readonly', 'erreur', 'genres', 'dessinateurs', 'scenaristes'));
    }

    /**
     * Supression d'un Commentaire
     * Si la suppression provoque une erreur fatale
     * on la place dans la Session
     * Dans tous les cas on réaffiche la liste des mangas
     * @param int $idCommentaire : Id du Commentaire à supprimer
     * @return Redirection listerCommentaires
     */
    public function deleteCommentaire($idCommentaire) {
        $erreur = "";
        $user = Auth::guard()->user();            
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/commentaire/'.$idCommentaire;
        $response = $client->request('GET', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
        $commentaire = json_decode($response->getBody()->getContents());
        $idManga = $commentaire->id_manga;
        try {
            if(!($user->role == 'comment' && $user->id == $commentaire->id_lecteur)) {
                $erreur = 'Vous ne pouvez supprimer que vos propres commentaires !';
                Session::put('erreur', $erreur);
                return redirect('/listerCommentaires/'.$idManga);
            }            
            $client->request('DELETE', $uri, ['headers' => ['Authorization' => 'Bearer '.$user->api_token]]);
            // On réaffiche la liste des commentaires
            return redirect('/listerCommentaires/'.$idManga);
        } catch (Exception $ex) {
            Session::put('erreur', $ex->getMessage());
            return redirect('/listerCommentaires/'.$idManga);
        }
    }

}
