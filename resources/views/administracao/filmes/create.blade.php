@extends('layout')
@section('title','Novo Filme' )
@section('content')
<form method="POST" action="{{route('config.store.filme')}}" class="form-group" enctype="multipart/form-data" novalidate>
    @csrf
    @include('administracao.filmes.partials.create-edit')
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <a href="{{route('config.index')}}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection