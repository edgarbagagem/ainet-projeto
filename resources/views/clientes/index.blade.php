@extends('layout')
@section('title','Clientes' )
@section('content')

<<<<<<< HEAD
<div class="form-group">
    <form method="GET" action="form-inline">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="substring">
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
        <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value=""> Repor</button>
    </form>
=======
>>>>>>> 412a927da481f6c8092caac1a048b5cd102fcd14

<form method="GET" action="{{route('clientes.index')}}" class="form-inline">
    <div class="form-row">
        <div class="form-group col-md-4">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="substring">
        </div>
    </div>
    <button class="btn btn-outline-secondary btn-mb" type="submit">Search</button>
    <button class="btn btn-outline-primary btn-mb" style="margin-left: 0.5rem" type="submit" value=""> Repor</button>

</form>

<br>
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>id</th>
            <th>Nome</th>
            <th>Email</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
        <tr>
            <td>
                <img src="{{$cliente->foto_url ? asset('storage/fotos/' . $cliente->foto_url) : asset('img/default_img.png') }}" alt="Foto do Cliente" class="img-profile rounded-circle" style="width:40px;height:40px">
            </td>
            <td>{{$cliente->id}}</td>
            <td>{{$cliente->nome}}</td>
            <td>{{$cliente->email}}</td>
            <td>
                @can('update', $cliente)
                <form action="{{route('clientes.blockunblock', ['cliente' => $cliente])}}" method="POST">
                    @csrf
                    @method('put')
                    @if($cliente->bloqueado == 0)
                    <input type="submit" class="btn btn-danger btn-sm" value="Bloquear">
                    @endif
                    @if($cliente->bloqueado == 1)
                    <input type="submit" class="btn btn-primary btn-sm" value="Desbloquear">
                    @endif

                </form>
                @endcan
            </td>
            <td>
                @can('delete', $cliente)
                <form action="{{route('clientes.delete', ['cliente' => $cliente])}}" method="POST">
                    @csrf
                    @method('delete')
                    <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $clientes->withQueryString()->links() }}
@endsection