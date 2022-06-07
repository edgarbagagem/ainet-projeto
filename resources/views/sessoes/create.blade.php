@extends('layout_admin')
@section('title', 'Novo Curso' )
@section('content')
    <form method="POST" action="{{route('admin.cursos.store')}}" class="form-group">
        @csrf
        @include('cursos.partials.create-edit')
        <div class="form-group text-right">
                <button type="submit" class="btn btn-success" name="ok">Save</button>
                <a href="{{route('admin.cursos.create')}}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
