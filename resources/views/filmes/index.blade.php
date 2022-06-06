@extends('layout')

@section('title')
<h2>Filmes</h2>
@endsection

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
            <div class="curso-info">
                <span class="curso-info-desc"><i class="fas fa-eye"></i><a href="{{$filme->trailer_url}}"> Trailer</a></span> 
        </div>
        <div class="curso-info">
                <span class="curso-info-desc"><i class="fas fa-fast-forward"></i><a href="{{$filme->trailer_url}}"> Sessões</a></span> 
        </div>
            </div>
    </div>
    @endforeach
</div>

@endsection