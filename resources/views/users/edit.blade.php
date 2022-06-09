@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-0 text-gray-800">Editar Perfil</h1>
    <br>
    <form method="POST" action="{{route('index.user.updatePerfil')}}" class="needs-validation" enctype="multipart/form-data" novalidate> 
        @csrf
        <input name="_method" type="hidden" value="PUT">

        <div class="col-md-6">
            <label class="control-label" for="nome"> Nome </label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{$user->name}}" required><br>

            <label class="control-label" for="email"> Email </label>
            <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}" required><br>
            
            <label class="control-label" for="nif"> NIF </label>
            <input type="text" name="nif" id="nif" class="form-control" value="{{$user->nif}}" required><br>

            <label class="control-label" for="tipo_pagamento">Tipo de Pagamento Preferido</label><br>
            <select name="tipoPagamento" id="tipo_pagamento">
                <option value="mbway" selected>MBWAY</option> <!-- mudar estas liambas dos values e no nif, fica $client->.. -->
                <option value="paypal">PAYPAL</option>
                <option value="visa">VISA</option>
            </select><br><br>

            <label class="control-label" for="foto"> Foto </label>
            <input type="file" name="foto" id="foto" class="form-control"><br>

            

            <button type="submit" class="btn btn-primary mr-auto">Guardar</button>
        </div>
    </form>
</div>
@endsection
