<div class="form-group">

  <div class="form-row">
    <div class="form-group col-md-6">
      <label class="control-label" for="nome"> Nome </label>
      <input type="text" name="nome" id="nome" class="form-control" value="{{$sala->nome}}" required>
    </div>
    <div class="form-group col-md-2">
      <label for="filas"> Nº Filas </label>
      <input type="number" min="1" max="26" name="filas" id="filas" class="form-control" value="{{$sala->filas}}" required>
    </div>


    <div class="form-group col-md-2">
      <label for="colunas"> Lugares Por Fila </label>
      <input type="number" min="1" name="colunas" id="colunas" class="form-control" value="{{$sala->colunas}}" required>
    </div>
  </div>
</div>