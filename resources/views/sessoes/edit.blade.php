@extends('layout_admin')
@section('title','Alterar Curso' )
@section('content')
    <form method="POST" action="{{route('admin.cursos.update', ['curso' => $curso]) }}" class="form-group">
        @csrf
        @method('PUT')
        @include('cursos.partials.create-edit')
        <div class="form-group text-right">
            @can('update', $curso)
                <button type="submit" class="btn btn-success" name="ok">Save</button>
            @endcan
                <a href="{{route('admin.cursos.edit', ['curso' => $curso]) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
