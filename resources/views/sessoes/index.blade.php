@extends('layout')
@section('title','Sessões' )
@section('content')
<!-- <div class="row mb-3">
    @can('create', App\Models\sessao::class)
    <a href="{{route('admin.sessoes.create')}}" class="btn btn-success" role="button" aria-pressed="true">Novo sessao</a>
    @endcan
</div> -->

<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Sala</th>
            <th>Lugares Disponíveis</th>
            <th>Bilhetes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sessoes as $sessao)
        <?php
        $lugaresDisponiveis = ($sessao->totalLugares)-($sessao->lugaresOcupados);
        ?>
        
        <tr>
            <td>{{$sessao->titulo}}</td>
            <td>{{$sessao->data}}</td>
            <td>{{$sessao->horario_inicio}}</td>
            <td>{{$sessao->sala}}</td>
            <td>{{$lugaresDisponiveis}}</td>
            <td>
            <form action="{{route('carrinho.store_bilhete', $sessao)}}" method="POST">
                @csrf
                
                <button type="submit" class="btn btn-primary" name="ok">Adicionar Bilhete ao Carrinho</button>
            </form>
            </td>

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
    {{$sessoes->withQueryString()->links()}}
</table>
@endsection