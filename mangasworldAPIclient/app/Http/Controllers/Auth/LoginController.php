<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use App\User;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redéfinition de la méthode login de la classe AuthenticatesUsers
     * pour prendre en compte l'appel à l'API Rest
     * @param Request $request
     * @return type
     */
    public function login(Request $request) {
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/login';
        // Il faut envoyer un tableau qui sera sérialisé
        $data = ['email' => $request->input('email'), 'password' => $request->input('password')];
        try {
            $response = $client->post($uri, ['query' => $data]);
            // On désérialise la réponse retournée
            $json = json_decode($response->getBody()->getContents());
            $user = new User();
            $user->email = $json->email;
            $user->api_token = $json->api_token;
            $user->id = $json->id;
            // On enregistre localement l'utilisateur authentifié par l'API
            Auth::guard()->login($user);
            return redirect()->intended($this->redirectPath());
        } catch (Exception $ex) {
            // Si l'API Rest retourne un code 50X ou 40X, cela provoque
            // une exception qu'il faut récupérer
            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * Redéfinition d ela méthode logout de la classe AuthenticatesUsers
     * Il faut adjoindre le jeton pour être sûr que c'est bien celui qui 
     * s'est authentifié qui demande à se déconnecter
     * @param Request $request
     * @return type
     */
    public function logout(Request $request) {
        $user = Auth::guard()->user();
        if ($user) {
            $client = new Client(['headers' => ['Authorization' => 'Bearer ' . $user->api_token]]);
            $response = $client->request('GET', 'http://localhost/mangasworldAPI/public/api/logout');
            Auth::guard()->logout($user);
            return redirect()->intended($this->redirectPath());
        }
    }

}
