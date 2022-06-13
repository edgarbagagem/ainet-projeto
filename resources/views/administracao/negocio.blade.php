@extends('layout')
@section('title','Administração de negócio' )
@section('content')

<form method="POST" action="{{route('config.save')}}">
    @csrf
    @method('PUT')
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="preco"> Preço Sem Iva </label>
            <input type="number" min="0" step=".01" name="preco" id="preco" class="form-control" value="{{$configuracao->preco_bilhete_sem_iva}}" required>
        </div>


        <div class="form-group col-md-2">
            <label for="iva"> IVA </label>
            <input type="number" min="0" name="iva" id="iva" class="form-control" value="{{$configuracao->percentagem_iva}}" required>
        </div>


    </div>
    <button type="submit" class="btn btn-primary mb-2" name="ok">Guardar</button>
</form>

<!-- salas -->
<br>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestão de Salas</h1>
</div>
<div class="row">
    <div class="col">
        <div class="row mb-3">
            <div class="col-3">
                <a href="{{route('config.create.sala')}}" class="btn btn-success" role="button" aria-pressed="true">Nova Sala</a>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nome</th>
                    <th>Lugares</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salas as $sala)
                <tr>
                    <td>{{$sala->id}}</td>
                    <td>{{$sala->nome}}</td>
                    <td>{{$sala->lugares}}</td>
                    <td>
                        <form action="{{route('config.edit.sala', ['sala' => $sala])}}">
                            @csrf
                            <input type="submit" class="btn btn-info btn-sm" value="Alterar">
                        </form>
                    </td>
                    <td>

                        <form action="{{route('config.delete.sala', ['sala' => $sala])}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $salas->withQueryString()->links() }}
    </div>
</div>

<!-- filmes -->

<br>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestão de Filmes</h1>
</div>

<div class="form-group">
    <form method="GET" action="{{route('config.index')}}" class="form-inline">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="substring">
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
        <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value=""> Repor</button>
    </form>

</div>
<div class="row">
    <div class="col">
        <div class="row mb-3">
            <div class="col-3">
                <a href="{{route('config.create.filme')}}" class="btn btn-success" role="button" aria-pressed="true">Novo Filme</a>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Cartaz</th>
                    <th>id</th>
                    <th>Título</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filmes as $filme)
                <tr>
                    <td>
                        <img src="{{$filme->cartaz_url ? asset('storage/cartazes/' . $filme->cartaz_url) : asset('img/default_img.png') }}" alt="Cartaz" class="img-profile rounded-circle" style="width:40px;height:40px">
                    </td>
                    <td>{{$filme->id}}</td>
                    <td>{{$filme->titulo}}</td>
                    <td>
                        <form action="{{route('config.edit.filme', ['filme' => $filme])}}">
                            @csrf
                            <input type="submit" class="btn btn-info btn-sm" value="Alterar">
                        </form>
                    </td>
                    <td>
                        @if($filme->sessaoCount == 0)
                        <form action="{{route('config.delete.filme', ['filme' => $filme])}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $filmes->withQueryString()->links() }}
    </div>
</div>

@endsection