@extends('layout')
@section('title','Criar sessões' )
@section('content')
<form method="POST" action="{{route('config.store.sessao', ['id' => $id])}}">
    @csrf
    <div class="form-group text-right">
        <button type="submit" class="btn btn-success" name="ok">Save</button>
        <a href="{{route('sessoes.filme', ['id' => $id])}}" class="btn btn-secondary">Cancel</a>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <table class="table table-bordered" id="dynamicAddRemove">
        <tr>
            <th>Sala</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Repete?</th>
        </tr>

        <tr>
            <td>
                <select class="custom-select" name="sessao[0][sala_id]" id="sala" aria-label="Sala">
                    <option value="">Escolha uma Sala</option>
                    @foreach ($salas as $id=>$nome)
                    <option value={{$id}}>{{$nome}}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <input type="date" id="data" name="sessao[0][data]" class="form-control">
            </td>
            <td>
                <input type="time" id="hora" name="sessao[0][horario_inicio]" class="form-control">
            </td>

            <td>
                <input type="number" min="1" id="repete" name="sessao[0][dias]" class="form-control" placeholder="Nº de dias">
            </td>
            <td><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Adicionar Sessão</button></td>
        </tr>
    </table>
</form>



<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click(function() {
        ++i;
        $("#dynamicAddRemove").append(
            `
<tr>
    <td>
        <select class="custom-select" name="sessao[${i}][sala_id]" id="sala" aria-label="Sala">
            <option value="">Escolha uma Sala</option>
            @foreach ($salas as $id=>$nome)
            <option value={{$id}}>{{$nome}}</option>
            @endforeach
        </select>
    </td>

    <td>
        <input type="date" id="data" name="sessao[${i}][data]" class="form-control">
    </td>
    <td>
        <input type="time" id="hora" name="sessao[${i}][horario_inicio]" class="form-control">
    </td>

    <td>
        <input type="number" min="1" id="repete" name="sessao[${i}][dias]" class="form-control" placeholder="Nº de dias">
    </td>
    <td><button type="button" class="btn btn-outline-danger remove-input-field"> Delete </button></td>
</tr>`

        );
    });
    $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();
    });
</script>

@endsection