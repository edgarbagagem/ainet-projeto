@extends('layout')
@section('title','Alterar Filme' )
@section('content')
<form method="POST" action="{{route('config.update.filme', ['filme'=>$filme])}}" class="form-group" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('administracao.filmes.partials.create-edit')
    <div class="form-group text-right">

        <button type="submit" class="btn btn-success" name="ok">Save</button>

        <a href="" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection