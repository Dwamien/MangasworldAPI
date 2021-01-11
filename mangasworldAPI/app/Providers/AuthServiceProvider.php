<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Manga;
use App\Policies\MangaPolicy;

use App\Models\Commentaire;
use App\Policies\CommentairePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',

        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Vérifie que l'utilisateur dispose du rôle Contributeur
        Gate::define('contrib', function($user) {
            return $user->role == 'contrib';
        }); 
        // Vérifie que l'utilisateur dispose du rôle Commentateur
        Gate::define('comment', function($user) {
            return $user->role == 'comment';
        });         
    }
}
