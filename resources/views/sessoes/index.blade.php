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
        $lugaresDisponiveis = ($sessao->totalLugares) - ($sessao->lugaresOcupados);
        ?>

        <tr>
            <td>{{$sessao->titulo}}</td>
            <td>{{$sessao->data}}</td>
            <td>{{$sessao->horario_inicio}}</td>
            <td>{{$sessao->sala}}</td>
            <td>{{$lugaresDisponiveis}}</td>
            <td>
                <a class="card-link" href="{{route('add.cart', ['id' => $sessao->id])}}">Adicionar ao Carrinho</a>
            </td>
        </tr>
        </form>
        @endforeach
    </tbody>
    {{$sessoes->withQueryString()->links()}}
</table>
@endsection