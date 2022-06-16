@extends('layout')
@section('title','Histórico de Recibos')
@section('content')

<table class="table">
    <thead>
        <tr>
            <th>Nº Recibo</th>
            <th>Data</th>
            <th>Tipo de Pagamento</th>
            <th>Referência</th>
            <th>Total Com Iva</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recibos as $recibo)
        <tr>
            <td>{{$recibo->id}}</td>
            <td>{{$recibo->data}}</td>
            <td>{{$recibo->tipo_pagamento}}</td>
            <td>{{$recibo->ref_pagamento}}</td>
            <td>{{$recibo->preco_total_com_iva}}</td>
            <td>
                <a href="{{route('cliente.recibo', ['user' => Auth()->user(), 'recibo' => $recibo])}}" target="_blank" class="btn btn-info btn-sm">
                    Consultar </a>
            </td>
            <td>
                <a href="{{route('cliente.recibo.pdf', ['user' => Auth()->user(), 'recibo' => $recibo])}}" target="_blank" class="btn btn-success btn-sm">
                    Descarregar PDF </a>
            </td>
        </tr>
        </form>
        @endforeach
    </tbody>
    {{$recibos->withQueryString()->links()}}
</table>
@endsection