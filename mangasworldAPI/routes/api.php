<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::group(['middleware' => 'auth:api'], function(){
        //routes get ->index() 
    Route::get('/manga', 'MangaController@index');
    Route::get('/dessinateur', 'DessinateurController@index');
    Route::get('/scenariste', 'ScenaristeController@index');
    Route::get('/genre', 'GenreController@index');
    Route::get('/commentaire', 'CommentaireController@index');
    Route::get('/lecteur', 'ProfilController@indexLecteur');

    // routes get {id} ->show($id) et affichage d'un model/model/{id}
    Route::get('/manga/{id}', 'MangaController@show');
    Route::get('/lecteur/{id}', 'ProfilController@show');
    Route::get('/commentaire/{id}', 'CommentaireController@show');
    Route::get('/manga/genre/{id}', 'MangaController@getMangasGenre');
    Route::get('/commentaire/manga/{id}', 'CommentaireController@getCommentairesManga');

    // routes post ->store($request)
    Route::post('/dessinateur', 'DessinateurController@store');
    Route::post('/manga', 'MangaController@store');
    Route::post('/commentaire', 'CommentaireController@store');

    // routes put ->update($request)
    Route::put('/dessinateur', 'DessinateurController@update');
    Route::put('/manga', 'MangaController@update');
    Route::put('/commentaire', 'CommentaireController@update');
    Route::put('/lecteur', 'ProfilController@update');

    // routes delete ->delete($id)
    Route::delete('/dessinateur/{id}', 'DessinateurController@delete');
    Route::delete('/manga/{id}', 'MangaController@delete');
    Route::delete('/commentaire/{id}', 'CommentaireController@delete');

    //désauthentification
    Route::get('/logout', 'Auth\LoginController@logout');
});


//authentification
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::middleware('auth:api')->get('user', function(Request $request){
    return $request->user();
});
