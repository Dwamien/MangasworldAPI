@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Vérification de votre adresse courriel') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un lien de vérification vous a été envoyé par courriel.') }}
                        </div>
                    @endif

                    {{ __('Avant de commencer, merci de vérifier la réception du courriel de vérification.') }}
                    {{ __('Si vous n'avez pas reçu de courriel) }}, <a href="{{ route('verification.resend') }}">{{ __('cliquer pour en recevoir un autre.') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
