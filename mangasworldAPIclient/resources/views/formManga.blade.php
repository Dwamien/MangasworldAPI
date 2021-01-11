@extends('layouts.master')
@section('content')
{!! Form::open(['url' => 'validerManga', 'files' => true]) !!}  
<div class="col-md-12 well well-sm">
    <center><h1>{{$titreVue}}</h1></center>
    <div class="form-horizontal">   
        <input type="hidden" name="id_manga" value="{{$manga->id_manga}}"/>
        <div class="form-group">
            <label class="col-md-3 control-label">Titre : </label>
            <div class="col-md-3">
                {{Form::text("titre", old("titre") ? old("titre") : (!empty($manga) ? $manga->titre : null),
                    [ "class" => "form-control", "placeholder" => "Titre", "required", "autofocus", $readonly])
                }}                     
            </div>
            @error('titre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror              
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Genre : </label>
            <div class="col-md-3">
                {{ Form::select('cbGenre', $genres->pluck('lib_genre', 'id_genre'), $manga->id_genre, 
                    ['class' => 'form-control', 'placeholder' => 'Sélectionner un genre', $readonly]) }}                
            </div>
            @error('cbGenre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror             
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Scenariste : </label>
            <div class="col-md-3">
                {{ Form::select('cbScenariste', $scenaristes->pluck('nom_scenariste', 'id_scenariste'), $manga->id_scenariste, 
                    ['class' => 'form-control', 'placeholder' => 'Sélectionner un scénariste', $readonly]) }}                 
            </div>
            @error('cbScenariste')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror               
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Dessinateur : </label>
            <div class="col-md-3">
                {{ Form::select('cbDessinateur', $dessinateurs->pluck('nom_dessinateur', 'id_dessinateur'), $manga->id_dessinateur, 
                    ['class' => 'form-control', 'placeholder' => 'Sélectionner un dessinateur', $readonly]) }}                                 
            </div>
            @error('cbDessinateur')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror            
        </div>     
        <div class="form-group">
            <label class="col-md-3 control-label">Prix : </label>
            <div class="col-md-3">
                {{Form::text("prix", old("prix") ? old("prix") : (!empty($manga) ? $manga->prix : null),
                    [ "class" => "form-control", "placeholder" => "Prix", "required", $readonly] )
                }}                        
            </div>
            @error('prix')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror             
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Couverture : </label>
            <div class="col-md-6">
                <input type="hidden" name="MAX_FILE_SIZE" value="204800"/>
                <input name="couverture" type="file" class="btn btn-default pull-left" @if($readonly) disabled @endif/>
                       <input type="hidden" name="couvertureHidden" value="{{$manga->couverture}}"/>                
                <img src='{{ URL::to('/') }}/images/{{$manga->couverture}}' class='img-responsive pull-right imgReduite' 
                     alt='{{$manga->couverture}}' />
            </div>
        </div>
        @can('comment')
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
                <button type="button" class="btn btn-default btn-primary" 
                        onclick="javascript: window.location = '{{ url('/ajouterCommentaire') }}/{{ $manga->id_manga }}';">
                    <span class="glyphicon glyphicon-plus"></span> Ajouter un commentaire
                </button>
            </div>           
        </div>       
        @endcan       
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-default btn-primary" @if($readonly) disabled @endif>
                        <span class="glyphicon glyphicon-ok"></span> Valider
                </button>
                &nbsp;
                <button type="button" class="btn btn-default btn-primary" 
                        onclick="javascript: window.location = '{{ url('/listerMangas') }}';">
                    <span class="glyphicon glyphicon-remove"></span> Annuler
                </button>
            </div>           
        </div>
        <div class="col-md-6 col-md-offset-3">
            @include('error')
        </div>        
    </div>
</div>
{!! Form::close() !!}
@stop