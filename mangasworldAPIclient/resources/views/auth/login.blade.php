@extends('layouts.master')
@section('content')

<div class="col-md-9 well well-md">
    <center><h1>Authentification</h1></center>
    <form method="POST" action="{{ route('login') }}">
         @csrf
        <div class="form-horizontal">    
            <div class="form-group">
                <label class="col-md-3 control-label">Identifiant : </label>
                <div class="col-md-3 ">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Mot de passe : </label>
                <div class="col-md-3 ">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div> 
            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                               <label class="form-check-label" for="remember"> {{ ('Se souvenir de moi') }}</label>
                    </div>
                </div>
            </div>        
            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-log-in"></span> Valider</button>
                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                    @endif                
                </div>
            </div>
        </div>
    </form>        
</div>
@stop

