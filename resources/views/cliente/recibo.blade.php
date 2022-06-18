<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo: {{$recibo->id}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <div class="card">
        <div class="card-body mx-4">
            <div class="container">
                <p class="my-5 mx-5" style="font-size: 30px;">CineMagic</p>
                <div class="row">
                    <ul class="list-unstyled">
                        <li class="text-black">{{$recibo->nome_cliente}} <span class="text-muted mt-1">{{$recibo->nif}}</span></li>
                        <li class="text-black mt-1">Recibo: <span class="text-muted mt-1">{{$recibo->id}}</span></li>
                        <li class="text-black mt-1">{{$recibo->data}}</li>
                        <li class="text-black mt-1">{{$recibo->tipo_pagamento}}</li>
                        <li class="text-black mt-1">Referência: <span class="text-muted mt-1">{{$recibo->ref_pagamento}}</span></li>
                    </ul>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nº Bilhete</th>
                            <th>Filme</th>
                            <th>Sala</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Lugar</th>
                            <th>Preço sem Iva</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bilhetes as $bilhete)
                        <tr>
                            <td>{{$bilhete->id}}</td>
                            <td>{{$bilhete->filme}}</td>
                            <td>{{$bilhete->sala}}</td>
                            <td>{{$bilhete->data}}</td>
                            <td>{{$bilhete->horario}}</td>
                            <td>{{$bilhete->lugar}}</td>
                            <td>{{$bilhete->preco_sem_iva}}
                        </tr>
                        </form>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-xl-12">
                    <p class="fw-bold">Total Sem Iva: {{$recibo->preco_total_sem_iva}}</p>

                    <p class="fw-bold">Iva: {{$recibo->iva}}</p>

                    <p class="fw-bold">Total: {{$recibo->preco_total_com_iva}}</p>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>