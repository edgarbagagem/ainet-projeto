@extends('layout')
@section('title','Administradores e Funcionários' )
@section('content')
<div class="row mb-3">
    <div class="col-3">
        @can('create', App\Models\User::class)
        <a href="{{route('users.admin.create')}}" class="btn btn-success" role="button" aria-pressed="true">Novo User</a>
        @endcan
    </div>

    <div class="form-group">
        <form method="GET" action="{{route('users.admin')}}" class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="substring">
            <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
            <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value=""> Repor</button>
        </form>

    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>id</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>
                <img src="{{$user->foto_url ? asset('storage/fotos/' . $user->foto_url) : asset('img/default_img.png') }}" alt="Foto do user" class="img-profile rounded-circle" style="width:40px;height:40px">
            </td>
            <td>{{$user->id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->tipo}}</td>
            <td>
                @can('view', $user)
                <form action="{{route('users.admin.consultar', ['user'=> $user])}}">
                    @csrf
                    <input type="submit" class="btn btn-info btn-sm" value="Consultar">
                </form>
                @endcan
            </td>
            <td>
                @can('update', $user)
                <form action="{{route('users.admin.blockunblock', ['user' => $user])}}" method="POST">
                    @csrf
                    @method('put')
                    @if($user->bloqueado == 0)
                    <input type="submit" class="btn btn-danger btn-sm" value="Bloquear">
                    @endif
                    @if($user->bloqueado == 1)
                    <input type="submit" class="btn btn-primary btn-sm" value="Desbloquear">
                    @endif

                </form>
                @endcan
            </td>
            <td>
                @can('delete', $user)
                <form action="{{route('users.admin.delete', ['user' => $user])}}" method="POST">
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
{{ $users->withQueryString()->links() }}
@endsection