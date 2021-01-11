@extends('layouts.master')
@section('content')

<div class="col-md-12 well well-sm">
    <center><h1>{{$titreVue}}</h1></center>
    {!! Form::open(['url' => 'validerCommentaire']) !!}      
    <div class="form-horizontal">    
        <div class="form-group">
            <input type="hidden" name="id_manga" value="{{$manga->id_manga}}"/>
            <input type="hidden" name="id_commentaire" value="{{$commentaire->id_commentaire}}"/>
            <label class="col-md-3 control-label">Titre : </label>
            <div class="col-md-3">
                <input type="text" name="titre" 
                       value="{{$manga->titre}}" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Genre : </label>
            <div class="col-md-3">
                <input type="text" name="genre" 
                       value="{{ $manga->genre->lib_genre}}" class="form-control"  readonly >
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Scenariste : </label>
            <div class="col-md-3">
                <input type="text" name="genre" 
                       value="{{ $manga->scenariste->prenom_scenariste}} {{$manga->scenariste->nom_scenariste}}" class="form-control"  readonly >
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Dessinateur : </label>
            <div class="col-md-3">
                <input type="text" name="genre" 
                       value="{{ $manga->dessinateur->prenom_dessinateur}} {{$manga->dessinateur->nom_dessinateur}}" class="form-control"  readonly >
            </div>
        </div>     
        <div class="form-group">
            <label class="col-md-3 control-label">Prix : </label>
            <div class="col-md-3">
                <input type="text" name="prix" value="{{$manga->prix}}" class="form-control"   readonly >
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Couverture : </label>
            <div class="col-md-3">               
                <img src='{{ URL::to('/') }}/images/{{$manga->couverture}}' class='img-responsive imgReduite' 
                     alt='{{$manga->couverture}}' />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Commentaire : </label>
            <div class="col-md-5">
                <textarea id="lib_commentaire" row="2" name="lib_commentaire" class="form-control" required autofocus {!!$readonly!!}>{{$commentaire->lib_commentaire}}</textarea>
            </div>
        </div>        
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-default btn-primary" @if($readonly) disabled @endif>
                    <span class="glyphicon glyphicon-ok"></span> Valider
                </button>
                &nbsp;
                <button type="button" class="btn btn-default btn-primary" 
                        onclick="javascript: window.location = '{{url('/listerCommentaires')}}/{{$manga->id_manga}}';">
                    <span class="glyphicon glyphicon-remove"></span> Annuler
                </button>
            </div>           
        </div>
        <div class="col-md-6 col-md-offset-3">
            @include('error')
        </div>        
    </div>
    {!! Form::close() !!}
</div>

@stop