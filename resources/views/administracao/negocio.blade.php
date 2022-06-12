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

@endsection