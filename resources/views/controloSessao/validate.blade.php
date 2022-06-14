@extends('layout')
@section('title','Controlo de Sessão' )
@section('content')

<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Sala</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        
        <tr>
            <td>{{$sessao->titulo}}</td>
            <td>{{$sessao->data}}</td>
            <td>{{$sessao->horario_inicio}}</td>
            <td>{{$sessao->sala}}</td>
            <td></td>
</tr>

        
    </tbody>
</table>
<br></br>
<form method="POST" action="{{route('controloSessao.validate', ['sessao' => $sessao])}}" class="form-group">
@csrf
@method('PUT')
<div class="form-group">
<div class="form-group col-md-6">
      <label class="form-label" name="nome">Insira o ID do bilhete: </label>
      <input type="text" name="id" id="nome" class="form-control" required>
      <br>
      <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value="id"> Validar</button>
    </div>
</div>
</form>
<br></br>
<br></br>
<br></br>
<span class="mr-2 d-none d-lg-inline text-gray-600"><b>Sessão a ser controlada por {{Auth::user()->name}}</b></span>

@endsection