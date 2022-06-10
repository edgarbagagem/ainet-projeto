<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Sessao;
use Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Bilhete;



class CartController extends Controller
{
    public function index($id) 
    {
        $currentTime = Carbon\Carbon::now()->subMinute(5);
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($currentTime)->format($format1);
        $time = Carbon\Carbon::parse($currentTime)->format($format2);



        $sessoes = Sessao::query();

        $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.id', '=', $id)
            ->paginate(10);


        $lugaresPorSessao = Sessao::query();
        foreach ($sessoes as $sessao) {
            $totalLugares = DB::table('lugares')->where('sala_id', '=', $sessao->sala_id)->count();
            $sessao->totalLugares = $totalLugares;
            $lugaresPorSessao = Bilhete::query();
            $sessao->lugaresOcupados = $lugaresPorSessao->where('sessao_id', '=', $sessao->id)->count();
        }

        return view('carrinho.index')->withId($id)
            ->withSessoes($sessoes);
    }
}
/*
    public function addToCart(Request $request)
    {
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'image' => $request->image,
            )
        ]);
        session()->flash('success', 'Product is Added to Cart Successfully !');

        return redirect()->route('cart.list');
    }

    public function updateCart(Request $request)
    {
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item Cart is Updated Successfully !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        session()->flash('success', 'All Item Cart Clear Successfully !');

        return redirect()->route('cart.list');
    }
*/

