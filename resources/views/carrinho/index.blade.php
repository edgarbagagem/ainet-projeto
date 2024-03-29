@extends('layout')
@section('title','Carrinho de Compras' )
@section('content')

<?php $precoCompra = 0;
$precoFinal=0; ?>
<div>
    <p>
    <form action="{{ route('carrinho.destroy') }}" method="POST">
        @csrf
        @method("DELETE")
        <button type="submit" class="btn btn-primary" name="destroy">Limpar carrinho</button>
    </form>
    </p>
</div>
<table class="table">
    <thead>
        <tr>
            <th>Quantidade</th>
            <th>Titulo</th>
            <th>Hora de Inicio</th>
            <th>Sala</th>
            <th>Data</th>
            <th></th>
            <th></th>
            <th></th>
            <th>Preço c/ IVA</th>
            <th>IVA</th>
    </thead>
    <tbody>

        @foreach ($carrinho as $row)
        <tr>
            <td>{{ $row['qtd'] }} </td>
            <td>{{ $row['titulo'] }} </td>
            <td>{{ $row['horario_inicio'] }} </td>
            <td>{{ $row['sala'] }} </td>
            <td>{{ $row['data'] }} </td>
            <td>
                <form action="{{route('carrinho.update_sessao', $row['id'])}}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="quantidade" value="1">
                    <button type="submit" class="btn btn-success btn-sm" name="store">Incrementar</button>
                </form>
            </td>
            <td>
                <form action="{{route('carrinho.update_sessao', $row['id'])}}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="quantidade" value="-1">
                    <button type="submit" class="btn btn-danger btn-sm" name="decrement">Decrementar</button>
                </form>
            </td>
            <td>
                <form action="{{route('carrinho.destroy_sessao', $row['id'])}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-warning btn-sm" name="remove">Remover</button>
                </form>
            </td>
            <td>
                <?php

                $iva = $configuracao->percentagem_iva / 100;
                $precoCadaBilhete = $configuracao->preco_bilhete_sem_iva + ($configuracao->preco_bilhete_sem_iva * $iva);
                if ($row['qtd'] != 0) {
                    
                    $precoFinal += $precoCadaBilhete * $row['qtd'];
                    $precoCompra = $precoCadaBilhete * $row['qtd'];
                }
                if ($row['qtd'] == 0) {
                    $precoCompra = 0;
                }
                ?>
                {{ number_format($precoCompra, 2, '.', ' ') }} €
            </td>


            <td>{{$configuracao->percentagem_iva}} %</td>
        </tr>
        @endforeach
    </tbody>
</table>
<hr>
<br></br>
@if(Auth()->check())
@if(Auth()->user()->tipo == 'C' )
@if($carrinho != [])
<div>
    <p></p>
    <form action="{{ route('carrinho.preparePayment') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-dark" name="confirm">Confirmar Carrinho</button>
        <br></br>
        <div class="form-group">
        
        <input type="hidden" name="precoFinal" value="{{number_format($precoFinal, 2,'.',' ')}}"></input>
            <input type="text" name="precoFinalMostrar" value="{{number_format($precoFinal, 2,'.',' ')}}" disabled> <b>€</input>
            <br></br>
            <label class="control-label" for="tipo_pagamento">Tipo de Pagamento Preferido</label>
            <select name="tipoPagamento" id="tipo_pagamento">
                <option value="mbway" selected>MBWAY</option> <!-- mudar estas liambas dos values e no nif, fica $client->.. -->
                <option value="paypal">PAYPAL</option>
                <option value="visa">VISA</option>
            </select>
        </div>
    </form>
</div>
@endif
@endif
@endif

@endsection