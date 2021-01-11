<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Lecteur;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $client = new Client();
        $uri = 'http://localhost/mangasworldAPI/public/api/register';
        // Il faut envoyer un tableau qui sera sérialisé
        $json = ['email' => $data['email'], 'password' => $data['password'], 'password_confirmation' => $data['password_confirmation']];
        try {
            $response = $client->post($uri, ['query' => $json]);
            // On désérialise la réponse retournée
            $json = json_decode($response->getBody()->getContents());
            $user = new User();
            $user->email = $json->email;
            $user->api_token = $json->api_token;
            $user->id = $json->id;
            // On enregistre localement l'utilisateur authentifié par l'API
            Auth::guard()->login($user);
            return $user; 
        } catch (Exception $ex) {
            // Si l'API Rest retourne un code 50X ou 40X, cela provoque
            // une exception qu'il faut récupérer
            return $this->sendFailedLoginResponse($request);
        }              
    }
}
