@extends('layout')
@section('title','Novo User' )
@section('content')
<form method="POST" action="{{route('users.admin.store')}}" class="form-group" enctype="multipart/form-data" novalidate>
    @csrf
    @include('administracao.partials.create-edit')
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <a href="{{route('users.admin')}}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection