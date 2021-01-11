@extends('layouts.master')
@section('content')
<div class="col-md-9 well well-md">
    <center><h1>Réinitialiser le mot de passe</h1></center>
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf        
        <div class="form-horizontal">    
            <div class="form-group">
                <label class="col-md-3 control-label">Adresse courriel : </label>
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
                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-default btn-primary"><span class="glyphicon glyphicon-log-in"></span> Envoyer lien de réinitialisation</button>              
                </div>
            </div>

        </div>
    </form>        
</div>

@stop

