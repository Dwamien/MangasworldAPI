<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
// les routes d'authentification (Se connecter, S'inscrire ...)
Auth::routes();
// Les routes publiques
// Page d'accueil
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
// Afficher la liste de tous les Mangas
Route::get('/listerMangas', 'MangaController@getMangas');
// Lister tous les mangas d'un genre sélectionné
Route::post('/listerMangasGenre', 'MangaController@getMangasGenre');
// Afficher la liste déroulante des genres
Route::get('/listerGenres', 'GenreController@getGenres');
// Aficher la liste des commentaires
Route::get('/listerCommentaires/{id}', 'CommentaireController@getCommentaires');
// Afficher un commentaire   
Route::get('/consulterCommentaire/{id}', 'CommentaireController@showCommentaire'); 

// Les routes protégées
Route::group(['middleware' => ['auth']], function() {
    // Afficher le Profil
    Route::get('/profil', 'ProfilController@getProfil');
    // Enregistrer la mise à jour du Profil
    Route::post('/profil', 'ProfilController@setProfil');
    // Demande d'ajout d'un Manga
    Route::get('/ajouterManga', 'MangaController@addManga')->middleware('can:contrib');
    // Demande de consultation d'un Manga
    Route::get('/consulterManga/{id}', 'MangaController@showManga')->middleware('can:comment');
    // Demande de modification d'un Manga   
    Route::get('/modifierManga/{id}', 'MangaController@updateManga')->middleware('can:contrib');
    // Enregistrer la mise à jour d'un Manga
    Route::post('/validerManga', 'MangaController@validateManga');
    // Supprimer un Manga
    Route::get('/supprimerManga/{id}', 'MangaController@deleteManga');
    // Demande d'ajout d'un commentaire
    Route::get('/ajouterCommentaire/{id}', 'CommentaireController@addCommentaire');   
    // Enregistrer un commentaire
    Route::post('/validerCommentaire', 'CommentaireController@validateCommentaire');  
    // Demande de modification d'un commentaire
    Route::get('/modifierCommentaire/{id}', 'CommentaireController@updateCommentaire')->middleware('can:comment'); 
    // Supprimer un commentaire
    Route::get('/supprimerCommentaire/{id}', 'CommentaireController@deleteCommentaire')->middleware('can:comment');    
});
