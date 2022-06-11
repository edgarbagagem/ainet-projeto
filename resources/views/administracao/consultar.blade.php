@extends('layout')
@section('title','Perfil do Utilizador')
@section('content')
<div class="container">
    <br>
    <div class="row justify-content">
        <div class="col-md-9">
            <label class="control-label" for="nome"> Nome </label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{$user->name}}" disabled><br>

            <label class="control-label" for="email"> Email </label>
            <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}" disabled><br>

            <label class="control-label" for="tipo_user"> Tipo de User </label>
            <input type="text" name="tipo_user" id="tipo_user" class="form-control" value="{{$user->tipo}}" disabled><br>

            <a href="{{route('users.admin.edit', ['user' =>$user])}}" class="btn btn-primary mr-auto" role="button" aria-pressed="true">Editar Perfil</a>
        </div>
        <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin: auto">

            <img src="{{$user->foto_url ? asset('storage/fotos/' . $user->foto_url) : asset('img/default_img.png')}}" height="200" width="200" />
            <div class="w3-container">
                <p>Foto de Perfil</p>
            </div>
        </div>
    </div>
</div>
@endsection