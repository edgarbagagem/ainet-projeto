<div class="form-group">
    <label for="inputAbr">Abreviatura</label>
    <input type="text" class="form-control" name="abreviatura" id="inputAbr" value="{{old('abreviatura', $curso->abreviatura)}}" >
    @error('abreviatura')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputNome">Nome</label>
    <input type="text" class="form-control" name="nome" id="inputNome" value="{{old('nome', $curso->nome)}}" >
    @error('nome')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputTipo">Tipo de Curso</label>
    <select class="form-control" name="tipo" id="inputTipo">
        <option {{old('tipo', $curso->tipo) == 'Licenciatura' ? 'selected' : ''}}>Licenciatura</option>
        <option {{old('tipo', $curso->tipo) == 'Mestrado' ? 'selected' : ''}}>Mestrado</option>
        <option {{old('tipo', $curso->tipo) == 'Curso Técnico Superior Profissional' ? 'selected' : ''}}>Curso Técnico Superior Profissional</option>
    </select>
    @error('tipo')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputSemestres">Semestres</label>
    <input type="text" class="form-control" name="semestres" id="inputSemestres" value="{{old('semestres', $curso->semestres)}}">
    @error('semestres')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputECTS">ECTS</label>
    <input type="text" class="form-control" name="ECTS" id="inputECTS" value="{{old('ECTS', $curso->ECTS)}}">
    @error('ECTS')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputVagas">Vagas</label>
    <input type="text" class="form-control" name="vagas" id="inputVagas" value="{{old('vagas', $curso->vagas)}}">
    @error('vagas')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputContato">Contato</label>
    <input type="text" class="form-control" name="contato" id="inputContato" value="{{old('contato', $curso->contato)}}">
    @error('contato')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
<div class="form-group">
    <label for="inputObjetivos">Objetivos</label>
    <textarea class="form-control" name="objetivos" id="inputObjetivos" rows=10>{{old('objetivos', $curso->objetivos)}}</textarea>
    @error('objetivos')
        <div class="small text-danger">{{$message}}</div>
    @enderror
</div>
