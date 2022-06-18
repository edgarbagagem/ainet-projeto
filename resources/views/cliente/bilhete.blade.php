<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilhete: {{$bilhete->id}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <div class="card">
        <div class="card-body mx-4">
            <div class="container">
                <p class="my-5 mx-5" style="font-size: 30px;">CineMagic</p>
                <div class="row">
                    <div class="col">
                        <ul class="list-unstyled">
                            <li class="text-black ">Bilhete: {{$bilhete->id}} </li>
                            <li class="text-black">{{$bilhete->filme}} </li>
                            <li class="text-black mt-1">Sala: <span class="text-muted mt-1">{{$bilhete->sala}}</span></li>
                            <li class="text-black mt-1">Data: <span class="text-muted mt-1">{{$bilhete->data}}</span></li>
                            <li class="text-black mt-1">Hora: <span class="text-muted mt-1">{{$bilhete->horario}}</span></li>
                            <li class="text-black mt-1">Lugar: <span class="text-muted mt-1">{{$bilhete->lugar}}</span></li>
                            <li class="text-black mt-1">Cliente: <span class="text-muted mt-1">{{$bilhete->cliente}}</span></li>
                        </ul>
                    </div>

                    @if(isset($bilhete->foto_url))
                    <div class="col">
                        <img src="{{$bilhete->foto_url ? asset('storage/fotos/' . $bilhete->foto_url) : asset('img/default_img.png')}}" class="img-thumbnail" style="height: 15rem; width: 15rem;" />
                    </div>
                    @endif


                    @if($bilhete->pdf == true)
                    {!!QrCode::size(100)->generate('http://projeto.test/bilhetes/Auth()->user()->id/$bilhete->id');!!}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>