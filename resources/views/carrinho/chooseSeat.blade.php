@extends('layout')

@section('title', 'Escolha um Lugar')



@section('content')


<form method="POST" action="{{route('carrinho.store_bilhete', ['sessao' => $sessao])}}" class="form-group">
    @csrf
    <div class="input-group">

        <select class="custom-select" name="lugar" id="inputLugar" aria-label="Lugar">
            @foreach ($lugares as $lugar)
            <option value="{{$lugar->id}}">{{$lugar->fila . $lugar->posicao}}</option>
            @endforeach
        </select>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Submeter</button>
        </div>
    </div>
</form>


@endsection