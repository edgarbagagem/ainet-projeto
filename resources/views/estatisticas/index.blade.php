@extends('layout')

@section('title','Estatísticas' )
@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bilhetes</h1>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <p>Estatística do preço dos bilhetes ao longo da longevidade do CineMagic:</p>
</div>



<div class="row">
    <div class="col">
        <div class="col-md-4">
            <label for="bilhete_min">Preço mais baixo</label>
            <input type="text" id="bilhete_min" class="form-control" value="{{$minimo}}" disabled>

        </div>
    </div>
    <div class="col">
        <div class="col-md-4">
            <label for="bilhete_max">Preço mais alto</label>
            <input type="text" id="bilhete_max" class="form-control" value="{{$maximo}}" disabled>

        </div>
    </div>
    <div class="col">
        <div class="col-md-4">
            <label for="bilhete_media">Média</label>
            <input type="text" id="bilhete_media" class="form-control" value="{{$media}}" disabled>

        </div>
    </div>
</div>
<br>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Filmes</h1>
</div>

<div class="row d-flex justify-content-center">
    @foreach($filmes as $filme)

    <div class="col-sm-4 d-flex pb-3">
        <div class="card" style="width: 18rem;">
            @if(!is_null($filme->cartaz_url))
            <img class="card-img-top" src="{{$filme->cartaz_url ?
         asset('storage/cartazes/' . $filme->cartaz_url) :
         asset('img/default_img.png') }}" alt="Cartaz do filme">
            @endif
            <div class="card-body">
                @if($filme->id == $idMaisVisto)
                <p class="card-text" style="color: green">Filme Mais Visto</p>
                @endif
                @if($filme->id == $idMenosVisto)
                <p class="card-text" style="color: red">Filme Menos Visto</p>
                @endif
                <h5 class="carad-title">{{$filme->titulo}}</h5>
                <p class="card-text">{{$filme->sumario}}</p>
            </div>


            <div class="d-flex align-items-end">
                <div class="card-body">
                    @if(!is_null($filme->trailer_url))
                    <p><i class="fas fa-eye"></i><a class="card-link" href="{{$filme->trailer_url}}"> Trailer </a></p>
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>



<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Géneros</h1>
</div>

<div class="row container-fluid d-flex justify-content-start">
    <div class="col-sm-8 col-md-6">
        <div class="card">
            <div class="card-header">Percentagens dos diferentes géneros de filmes</div>
            <div class="card-body">
                <div class="card-img-top" id="pie_chart"></div>
            </div>
        </div>
    </div>
</div>


<br>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    window.onresize = function() {
        startDrawingChart();
    };

    window.onload = function() {
        startDrawingChart();
    };

    startDrawingChart = function() {

        var analytics = <?php echo $genero; ?>

        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title: 'Percentagem de géneros de filmes',
                backgroundColor: 'transparent',
                legend: {
                    position: 'bottom'
                }

            };
            var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            chart.draw(data, options);

        }
    };
</script>

@endsection