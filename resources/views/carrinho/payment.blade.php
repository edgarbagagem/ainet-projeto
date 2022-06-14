@extends('layout')
@section('title','Pagamento' )
@section('content')

<form method="POST" action="{{route('carrinho.store')}}" class="form-group">
    @csrf

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
</form>

@endsection