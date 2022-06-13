<div class="form-group col-md-6">
  <label class="control-label" for="titulo"> Título </label>
  <input type="text" name="titulo" id="titulo" class="form-control" value="{{$filme->titulo}}" required>
  @error('titulo')
  <div class="small text-danger">{{$message}}</div>
  @enderror
</div>
<div class="form-group col-md-4">
  <label for="genero"> Género </label>
  <select class="custom-select" name="genero_code" id="genero" aria-label="Genero">
    <option value="">Escolha um género</option>
    @foreach ($generos as $code=>$genero)
    <option value={{$code}} {{$code == $filme->genero_code ? 'selected' : ''}}>{{$genero}}</option>
    @endforeach
  </select>
  @error('genero')
  <div class="small text-danger">{{$message}}</div>
  @enderror
</div>
<div class="form-group col-md-6">
  <label for="sumario"> Sumário </label>
  <textarea class="form-control" name="sumario" id="sumario" rows="3"> {{$filme->sumario}}</textarea>
  @error('sumario')
  <div class="small text-danger">{{$message}}</div>
  @enderror
</div>
<div class="form-group col-md-2">
  <label class="control-label" for="cartaz"> Cartaz </label>
  <input type="file" name="cartaz" id="cartaz" class="form-control-file">
  @error('cartaz')
  <div class="small text-danger">{{$message}}</div>
  @enderror
</div>

<div class="form-group col-md-2">
  <label class="control-label" for="trailer"> Trailer </label>
  <input type="url" name="trailer_url" id="trailer">
  @error('trailer')
  <div class="small text-danger">{{$message}}</div>
  @enderror
</div>