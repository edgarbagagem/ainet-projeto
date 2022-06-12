@extends('layout')
@section('title','Controlo de Sessões' )
@section('content')
<div class="container">
<div class="row mb-3">
<form method="GET" action="{{route('controloSessao.index')}}" class="form-group">
            <div class="input-group">
                <select class="custom-select" name="filme" id="inputFilme" aria-label="Filme">
                    <option value="" {{'' == old('filme', $selectedFilme) ? 'selected' : ''}}>Todos
                        Os Filmes</option>
                    @foreach ($filmes as $id=>$filme)
                    <option value="{{$id}}" {{$filme == old('filme', $selectedFilme) ? 'selected' : ''}}>{{$filme}}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
                </div>
            </div>
            <br>
            {{$sessoes->withQueryString()->links()}}
        </form>
        
<table class="table">
    <thead>
        <tr>
            <th>Filme</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Sala</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sessoes as $sessao)

        <tr>
            <td>{{$sessao->titulo}}</td>
            <td>{{$sessao->data}}</td>
            <td>{{$sessao->horario_inicio}}</td>
            <td>{{$sessao->sala}}</td>
            <td> <a class="card-link" href="{{route('controloSessao.sessao', ['id' => $sessao->id])}}"> Controlar Sessão </a></td>
            
            <!--<td>
                @can('view', $sessao)
                <a href="{{route('admin.sessaos.edit', ['sessao' => $sessao]) }}" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Alterar</a>
                @endcan
            </td> -->
            <!-- <td>
                @can('delete', $sessao)
                <form action="{{route('admin.sessoes.destroy', ['sessao' => $sessao]) }}"" method=" POST">
                    @csrf
                    @method("DELETE")
                    <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                </form>
                @endcan
            </td> -->
        </tr>
</form>
        @endforeach
    </tbody>
</table>

</div>
</div>
</div>
</div>
@endsection