@extends('layout')
@section('title','Alterar Sessão' )
@section('content')
<form method="POST" action="{{route('config.update.sessao', ['id' => $id])}}">
    @csrf
    @method('PUT')
    <div class=" form-group text-right">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <a href="{{route('sessoes.filme', ['id' => $id])}}" class="btn btn-secondary">Cancel</a>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="form-group">

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="sala"> Sala </label>
                <select class="custom-select" name="sala_id" id="sala" aria-label="Sala">
                    <option value="">Escolha uma Sala</option>
                    @foreach ($salas as $id=>$nome)
                    <option value={{$id}} {{$id == $sessao->filme_id ? 'selected' : ''}}>{{$nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="data"> Data </label>
                <input type="date" id="data" name="data" class="form-control" value="{{$sessao->data}}">
            </div>
            <div class="form-group col-md-2">
                <label for="hora"> Horário </label>
                <input type="time" id="hora" name="horario_inicio" class="form-control" value="{{$sessao->horario_inicio}}">
            </div>
        </div>
    </div>
</form>
@endsection