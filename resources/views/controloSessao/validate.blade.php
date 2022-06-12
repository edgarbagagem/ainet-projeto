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
        </tr>
    </thead>
    <tbody>
        @foreach ($sessao as $s)
        
        <tr>
            <td>{{$s->titulo}}</td>
            <td>{{$s->data}}</td>
            <td>{{$s->horario_inicio}}</td>
            <td>{{$s->sala}}</td>
</tr>
@endforeach
        
    </tbody>
</table>
<br>
<span class="mr-2 d-none d-lg-inline text-gray-600"><b>Sessão a ser controlada por {{Auth::user()->name}}</b></span>
</br>
<br>
</br>

<div class="form-group">
        <form method="GET" action="route('controloSessao.validate', ['id' => $bilhete->id])" class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Insira o ID do bilhete" aria-label="Search" name="id">
            <button class="btn btn-outline-primary my-2 my-sm-0" style="margin-left: 0.5rem" type="submit" value="validate"> Validar Bilhete</button>
        </form>

        <form action="{{route('controloSessao.validate', ['id' => $bilhete=>id])}}" method="POST">
        @csrf
        @method('put')
                    <input type="submit" class="btn btn-primary btn-sm" value="Validar">
                </form>
</div>

@endsection