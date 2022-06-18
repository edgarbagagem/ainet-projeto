@extends('layout')
@section('title','Sessões' )
@section('content')


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
                    <th></th>
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
                        <form action="{{route('carrinho.escolher_lugar', $sessao)}}" method="POST">
                            @csrf

                            <button type="submit" class="btn btn-primary" name="ok">Adicionar Bilhete ao Carrinho</button>
                        </form>
                    </td>
                    <td>
                        @if(isset($id))
                        @if($sessao->lugaresOcupados == 0)
                        <form action="{{route('config.edit.sessao', ['id' => $id, 'sessao' => $sessao])}}">
                            @csrf
                            <input type="submit" class="btn btn-info btn-sm" value="Alterar">
                        </form>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if(isset($id))
                        @if($sessao->lugaresOcupados == 0)
                        <form action="{{route('config.delete.sessao', ['sessao' => $sessao])}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-danger btn-sm" value="Apagar">
                        </form>
                        @endif
                        @endif
                    </td>
                </tr>
                </form>
                @endforeach
            </tbody>
            {{$sessoes->withQueryString()->links()}}
        </table>
    </div>
</div>

@endsection