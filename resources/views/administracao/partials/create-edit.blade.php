<div class="container">
  <div class="col-md-6">
    <div class="form-group">
      <label class="control-label" for="name"> Nome </label>
      <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}" required>
      @error('name')
      <div class="small text-danger">{{$message}}</div>
      @enderror
    </div>
    <div class="form-group">
      <label class="control-label" for="email"> Email </label>
      <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}" required>
      @error('email')
      <div class="small text-danger">{{$message}}</div>
      @enderror
    </div>
    <div class="form-group">
      <label class="control-label" for="tipo">Tipo de User</label>
      <select name="tipo" id="tipo">
        <option value="{{$user->tipo}}" selected> </option>
        <option value="A">Administrador</option>
        <option value="F">Funcionário</option>
      </select>
      @error('tipo')
      <div class="small text-danger">{{$message}}</div>
      @enderror
    </div>
    <div class="form-group">
      <label class="control-label" for="foto"> Foto </label>
      <input type="file" name="foto" id="foto" class="form-control-file">
      @error('foto')
      <div class="small text-danger">{{$message}}</div>
      @enderror
    </div>

  </div>

</div>