@extends('layout')
@section('title','Pagamento' )
@section('content')

<form method="POST" action="{{route('carrinho.store')}}" class="form-group">
    @csrf
<input class="form-control" type="hidden" name="precoFinal" value="{{$precoFinal}}"></input>
@if(strcmp($tipoPagamento, "mbway") == 0)
<div>
        <label for="inputPagamento"><b>Pagamento Feito Com MBWAY</label>
        <br></br>
        <input class="form-control" type="text" placeholder="Insira o seu número de telemóvel" name="mbway">
        <br></br>
    </div>
@endif

@if(strcmp($tipoPagamento, "visa") == 0)
        <label for="inputPagamento"><b>Pagamento Feito Com VISA</label>
        <br></br>
        <div class="row">
<div class="col">
<input class="form-control" type="text" placeholder="16 Dígitos" name="visa_digitos">
</div>
<div class="col">
<input class="form-control" type="text" placeholder="CVC" name="visa_cvc" style="width:50%";>
</div>
    </div>
@endif

@if(strcmp($tipoPagamento,"paypal") == 0)
<div>
        <label for="inputPagamento"><b>Pagamento Feito Com Paypal</label>
        <br></br>
        <input class="form-control" type="email" placeholder="Insira o seu email" name="paypal">
        <small id="emailHelp" class="form-text text-muted">Não partilharemos o seu email com ninguém.</small>

    </div>
@endif
<div class="form-group text-right">
        <button type="submit" class="btn btn-primary" name="ok">Finalizar Compra</button>
</div>

<label for="Escolha de Lugares"><b>Escolha de Lugares</b></label>
@foreach($filas as $fila)
   <p>{{$fila->fila}}</p>
@endforeach
@foreach ($carrinho as $row)
<?php
$bilhetesPorSessao = $row['qtd'];
for($i=0; $i<$bilhetesPorSessao;$i++){
   $j = $i+1;

    echo '<p>Lugares para Sessão: '.$row['id'].'<br>Sala: '.$row['sala'].'<br>Lugar: '.$j.'</p>';
    echo '<div class="row">';
    echo '<div class ="col">';
    echo '<input class="form-control" type="text" style="width:20%" placeholder="Fila" name=lugar/'.$row['id'].'/'.$j.' required /><br>';
    echo '</div>';
    echo '<div class = "col">';
    echo '<input class="form-control" type="number" style="width:20%; margin-left:5px;" placeholder="Lugar" name=lugar/'.$row['id'].'/'.$j.'required /><br>';
    echo '</div>';
    echo '</div>';
}
echo '<hr></hr>';
echo '<hr></hr>';

?>
@endforeach

</form>

@endsection