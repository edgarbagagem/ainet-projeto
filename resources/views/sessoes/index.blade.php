@extends('layout')
@section('title','Sessões' )
@section('content')

<<<<<<< HEAD
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
=======

<div class="row">
    <div class="col">
        <div class="row mb-2">
            <div class="col-3">
                @if(isset($id))
                @can('create', App\Models\Sessao::class)
                <a href="{{route('config.create.sessao', ['id' => $id])}}" class="btn btn-success" role="button" aria-pressed="true">Nova Sessão</a>
                @endcan
                @endif
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sala</th>
                    <th>Lugares Disponíveis</th>
                    <th>Bilhetes</th>
                    <th></th>
                    <th></th>
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
                    <td>
                        @if($sessao->lugaresOcupados == 0)
                        <form action="{{route('config.edit.sessao', ['id' => $id, 'sessao' => $sessao])}}">
                            @csrf
                            <input type="submit" class="btn btn-info btn-sm" value="Alterar">
                        </form>
                        @endif
                    </td>
                    <td>
                        @if($sessao->lugaresOcupados == 0)
                        <form action="{{route('config.delete.sessao', ['sessao' => $sessao])}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                        </form>
                        @endif
                    </td>
                </tr>
>>>>>>> 412a927da481f6c8092caac1a048b5cd102fcd14
                </form>
                @endforeach
            </tbody>
            {{$sessoes->withQueryString()->links()}}
        </table>
    </div>
</div>
@endsection