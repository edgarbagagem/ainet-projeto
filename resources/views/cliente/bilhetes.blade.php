@extends('layout')
@section('title','Bilhetes válidos')
@section('content')

<table class="table">
    <thead>
        <tr>
            <th>Nº Bilhete</th>
            <th>Data</th>
            <th>Filme</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bilhetes as $bilhete)
        <tr>
            <td>{{$bilhete->id}}</td>
            <td>{{$bilhete->data}}</td>
            <td>{{$bilhete->filme}}</td>
            <td>
                <a href="{{route('cliente.bilhete', ['user' => Auth()->user(), 'bilhete' => $bilhete])}}" target="_blank" class="btn btn-info btn-sm">
                    Consultar </a>
            </td>
            <td>
                <a href="{{route('cliente.bilhete.pdf', ['user' => Auth()->user(), 'bilhete' => $bilhete])}}" target="_blank" class="btn btn-success btn-sm">
                    Descarregar em PDF </a>
            </td>
        </tr>
        </form>
        @endforeach
    </tbody>
    {{$bilhetes->withQueryString()->links()}}
</table>
@endsection