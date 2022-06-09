@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-0 text-gray-800">Alterar Password</h1>
    <br>
    <form method="POST" action="{{route('user.updatePassword')}}" class="needs-validation" novalidate> 
        @csrf
        <input name="_method" type="hidden" value="PUT">
    
        <div class="col-md-6">
            <label class="control-label" for="old_password"> Antiga password </label>
            <input type="password" name="old_password" id="old_password" class="form-control" required><br>
            
            <label class="control-label" for="new_password"> Nova password </label>
            <input type="password" name="new_password" id="new_password" class="form-control" required><br>
            
            <label class="control-label" for="conf_password"> Confirmar password </label>
            <input type="password" name="conf_password" id="conf_password" class="form-control" required><br>

            <button type="submit" class="btn btn-primary mr-auto">Alterar Password</button>
        </div>
    </form>
</div>
@endsection
