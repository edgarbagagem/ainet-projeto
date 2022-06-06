@extends('layout')

@section('title')
<h2>Filmes</h2>
@endsection
<!-- 

@section('content')
<div class="docentes-area">
@foreach($filmes as $filme)
<div class = "docente">

    <div class="card docentes-imagem" style="width: 18rem;">
        <img class="card-img-top" src="{{$filme->cartaz_url ?
         asset('storage/cartazes/' . $filme->cartaz_url) :
         asset('img/default_img.png') }}" alt="Cartaz do filme">
    <div class="card-body">
        <h5 class="card-title">{{$filme->titulo}}</h5>
        <p class="card-text">{{$filme->sumario}}</p>
    </div>

    <div class="card-body">
        <a href="{{$filme->trailer_url}}" class="card-link">Trailer</a>
    </div>
    </div>
    </div>

@endforeach
    </div>    
@endsection -->

@section('content')
<div class="cursos-area">
    @foreach($filmes as $filme)
    <div class="curso">
        <div class="curso-imagem">
            <img src="{{$filme->cartaz_url ?
         asset('storage/cartazes/' . $filme->cartaz_url) :
         asset('img/default_img.png') }}" alt="Cartas do filme">
        </div>
        <div class="curso-info-area">
            <div class="curso-info">
                <span class="curso-label">Titulo</span>
                <span class="curso-info-desc">{{$filme->titulo}}</span>
            </div>
            <div class="curso-info">
                <span class="curso-label">Sumario</span>
                <span class="curso-info-desc">{{$filme->sumario}}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
    