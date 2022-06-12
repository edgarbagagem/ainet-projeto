@extends('layout')
@section('title','Alterar User' )
@section('content')
<form method="POST" action="{{route('users.admin.update', ['user'=>$user])}}" class="form-group" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('administracao.users.partials.create-edit')
    <div class="form-group text-right">
        @can('update', $user)
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        @endcan
        <a href="{{route('users.admin') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection