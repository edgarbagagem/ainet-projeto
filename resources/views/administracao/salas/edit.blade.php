@extends('layout')
@section('title','Alterar Sala' )
@section('content')
<form method="POST" action="{{route('config.update.sala', ['sala' => $sala])}}" class="form-group">
    @csrf
    @method('PUT')
    @include('administracao.salas.partials.create-edit')
    <div class="form-group text-right">

        <button type="submit" class="btn btn-success" name="ok">Save</button>

        <a href="" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection