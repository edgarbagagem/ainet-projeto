@extends('layout')

@section('title')
<h2>Filmes</h2>
@endsection

@section('content')
<div class="container">
    <div class="row equal">
        @foreach($filmes as $filme)
        <div class="col-sm-4 d-flex pb-3">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="{{$filme->cartaz_url ?
         asset('storage/cartazes/' . $filme->cartaz_url) :
         asset('img/default_img.png') }}" alt="Cartaz do filme">
                <div class="card-body">

                    <h5 class="carad-title">{{$filme->titulo}}</h5>
                    <p class="card-text">{{$filme->sumario}}</p>
                </div>


                <div class="d-flex align-items-end">
                    <div class="card-body">
                        <p><i class="fas fa-eye"></i> <a class="card-link" href="{{$filme->trailer_url}}"> Trailer</a></p>
                        <p><i class="fas fa-fast-forward"></i> <a class="card-link" href="{{route('sessoes.index')}}"> Sessões</a></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection