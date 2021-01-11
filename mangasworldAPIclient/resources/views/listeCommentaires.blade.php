@extends('layouts.master')
@section('content')
<div class="container">
    <div class="blanc">
        <h1>Liste des commentaires</h1>
    </div>
    <div class="row col-md-9">
        <div class="row col-md-9">
            <div class="row" style='margin-bottom: 10px;'>
                <label class="col-md-2">Titre : </label>
                <div class="col-md-7">
                    <input type="text" name="titre" 
                           value="{{$manga->titre}}" class="form-control" readonly>
                </div>
            </div>
            <div class="row" style='margin-bottom: 10px;'>
                <label class="col-md-2 control-label">Genre : </label>
                <div class="col-md-5">
                    <input type="text" name="genre" 
                           value="{{ $manga->genre->lib_genre}}" class="form-control"  readonly >
                </div>
            </div> 
            @auth
            <div class="row" style='margin-bottom: 10px;'>
                <div class="col-md-6">
                    <button type="button" class="btn btn-default btn-primary" 
                            onclick="javascript: window.location = '{{ url('/ajouterCommentaire') }}/{{ $manga->id_manga }}';">
                        <span class="glyphicon glyphicon-plus"></span> Ajouter un commentaire
                    </button>
                </div>           
            </div> 
            @endauth
        </div>    
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 250px;">Libellé</th>
                    <th style="width: 150px;">Lecteur</th>
                    @can('comment')
                    <th style="width: 50px;">Modifier</th>
                    <th style="width: 50px;">Supprimer</th>
                    @endcan
                    @guest
                    <th style="width: 50px;">consulter</th>
                    @endguest
                </tr>
            </thead>
           
            @foreach($commentaires as $commentaire)
                @foreach($lecteurs as $lecteur)
                    @if($commentaire->id_lecteur == $lecteur->id_lecteur)
            <tr>   
                <td> {{Str::limit($commentaire->lib_commentaire, $limit = 41, $end = ' ...')}}</td>
                <td> {{ $lecteur->prenom }} {{  $lecteur->nom  }}</td>
                @can('comment')
                <td style="text-align:center;"><a href="{{ url('/modifierCommentaire') }}/{{ $commentaire->id_commentaire }}">
                        <span class="glyphicon glyphicon-pencil" data-toggle="tooltip" data-placement="top" title="Modifier"></span></a>
                </td>
                <td style="text-align:center;">
                    <a class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="top" title="Supprimer" href="#"
                       onclick="javascript:if (confirm('Suppression confirmée ?'))
                           { window.location ='{{ url('/supprimerCommentaire') }}/{{ $commentaire->id_commentaire }}'; }">
                    </a>
                </td>  
                @endcan
                @guest
                <td style="text-align:center;"><a href="{{ url('/consulterCommentaire') }}/{{ $commentaire->id_commentaire }}">
                        <span class="glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="Consulter"></span></a>
                </td>                
                @endguest
            </tr>
                    @endif
                @endforeach
            @endforeach

            <BR> <BR>
        </table>
    </div>        
    <div class="col-md-6 col-md-offset-3">
        @include('error')
    </div> 
</div>
@stop