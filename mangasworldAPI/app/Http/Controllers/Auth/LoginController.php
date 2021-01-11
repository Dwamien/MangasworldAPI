<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**Redéfinition de la méthode login pour 
     * gérer l'authentification dans le cadre d'une API Rest.
     * Si le user est correctement authenfié, on appelle generateToken,
     * et on retourne le user avec son jeton.
     * @param Request $request
     * @return type
     */
    public function login(Request $request){
        $this->validateLogin($request);
        if($this->attemptLogin($request)){
            $user = $this->guard()->user();
            $user->generateToken();
            return response()->json($user, 200);
        }
        return $this->sendFailedLoginResponse($request);
    }
    
    /**Redéfinition de la méthode sendFailedLoginResponse 
     * pour envoyer un message d'erreur
     * @param Request $request
     * @return type
     */
    public function sendFailedLoginResponse(Request $request){
        return response()->json('Login ou mot de passe inconnu ¯\_(ツ)_/¯ ', 401);
    }
    
    /**Redéfinition de la méthode logout. 
     * Le jeton asscocié à l'utilisateur est supprimé de la BdD 
     * @param Request $request
     * @return type
     */
    public function logout(Request $request){
        $user = Auth::guard('api')->user();
        if($user){
            $user->api_token = null;
            $user->save();
            return response()->json('Utilisateur déconnecté', 200);
        }
        return response()->json('Utilisateur inconnu', 401);
    }
}
