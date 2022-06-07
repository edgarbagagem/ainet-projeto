@extends('layout_admin')
@section('title','Sessões' )
@section('content')
<div class="row mb-3">
    @can('create', App\Models\Curso::class)
    <a href="{{route('admin.cursos.create')}}" class="btn btn-success" role="button" aria-pressed="true">Novo Curso</a>
    @endcan
</div>
<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Nome</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cursos as $curso)
        <tr>
            <td>{{$curso->abreviatura}}</td>
            <td>{{$curso->nome}}</td>
            <td>{{$curso->tipo}}</td>
            <td>{{$curso->semestres}}</td>
            <td>{{$curso->ECTS}}</td>
            <td>{{$curso->vagas}}</td>
            <td>
                @can('view', $curso)
                <a href="{{route('admin.cursos.edit', ['curso' => $curso]) }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Alterar</a>
                @endcan
            </td>
            <td>
                @can('delete', $curso)
                <form action="{{route('admin.cursos.destroy', ['curso' => $curso]) }}"" method=" POST">
                    @csrf
                    @method("DELETE")
                    <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection