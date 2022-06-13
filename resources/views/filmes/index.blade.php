@extends('layout')

@section('title')
<h2>Filmes</h2>
@endsection

@section('content')

<div class="container">
    <div class="row mb-3">
        <div class="col-9">
            <form method="GET" action="{{route('filmes.index')}}" class="form-group">
                <div class="input-group">
                    <select class="custom-select" name="genero" id="inputGenero" aria-label="Genero">
                        <option value="" {{'' == old('genero', $selectedGenero) ? 'selected' : ''}}>Todos
                            Géneros</option>
                        @foreach ($generos as $code=>$genero)
                        <option value={{$code}} {{$genero == old('genero', $selectedGenero) ? 'selected' : ''}}>{{$genero}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
                    </div>
                </div>

            </form>

            <div class="form-group">
                <form method="GET" action="{{route('filmes.index')}}" class="form-inline">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="substring">
                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
                    <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value=""> Repor</button>
                </form>

            </div>
        </div>

    </div>

    <div class="row equal">
        @foreach($filmes as $filme)
        <div class="col-sm-4 d-flex pb-3">
            <div class="card" style="width: 18rem;">
                @if(!is_null($filme->cartaz_url))
                <img class="card-img-top" src="{{$filme->cartaz_url ?
         asset('storage/cartazes/' . $filme->cartaz_url) :
         asset('img/default_img.png') }}" alt="Cartaz do filme">
                @endif
                <div class="card-body">

                    <h5 class="carad-title">{{$filme->titulo}}</h5>
                    <p class="card-text">{{$filme->sumario}}</p>
                </div>


                <div class="d-flex align-items-end">
                    <div class="card-body">
                        @if(!is_null($filme->trailer_url))
                        <p><i class="fas fa-eye"></i><a class="card-link" href="{{$filme->trailer_url}}"> Trailer </a></p>
                        @endif
                        <p><i class="fas fa-fast-forward"></i> <a class="card-link" href="{{route('sessoes.filme', ['id' => $filme->id])}}"> Sessões </a></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection