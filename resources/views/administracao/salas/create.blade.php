@extends('layout')
@section('title','Nova Sala' )
@section('content')
<form method="POST" action="{{route('config.store.sala')}}" class="form-group">
    @csrf
    @include('administracao.salas.partials.create-edit')
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <a href="{{route('config.index')}}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection