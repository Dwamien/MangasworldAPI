<!doctype html>
<html lang="fr">
    <head>
        <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/mangas.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/bootstrap-theme.css') }}" rel="stylesheet">
        <!-- Scripts -->

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    </head>
    <body class="body">
        <div class="container">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-target">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar+ bvn"></span>
                        </button>
                        <a class="navbar-brand" href="{{ url('/') }}">Mangas World</a>
                    </div>
             
                    <div class="collapse navbar-collapse" id="navbar-collapse-target">
                        @guest
                        <ul class="nav navbar-nav navbar-right">                             
                            <li><a href="{{ url('/login') }}" data-toggle="collapse" data-target=".navbar-collapse.in">Se connecter</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __("S'inscrire") }}</a></li>                            
                        </ul> 
                        @endguest                        
                        <ul class="nav navbar-nav">                           
                            <li><a href="{{ url('/listerMangas') }}" data-toggle="collapse" data-target=".navbar-collapse.in">Lister</a></li>
                            <li><a href="{{ url('/listerGenres') }}" data-toggle="collapse" data-target=".navbar-collapse.in">Mangas par genre</a></li>
                            @can('contrib')
                            <li><a href="{{ url('/ajouterManga') }}"data-toggle="collapse" data-target=".navbar-collapse.in">Ajouter</a></li>   
                            @endcan
                        </ul>  
                        @auth
                        <ul class="nav navbar-nav navbar-right"> 
                            <li><a href="{{ url('/profil') }}"data-toggle="collapse" data-target=".navbar-collapse.in">Profil</a></li> 
                            
                            <li>
                                <a  data-toggle="collapse" data-target=".navbar-collapse.in" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Se déconnecter
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>                            
                        </ul>    
                        @endauth
                    </div>
                </div><!--/.container-fluid -->
            </nav>
        </div> 
        <div class="container">
            @yield('content')
        </div>
        <script src="{{ asset('assets/js/jquery-2.1.3.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/bootstrap.js') }}" defer></script>      
    </body>
</html>
