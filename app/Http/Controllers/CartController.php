<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Sessao;
use Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Bilhete;
use App\Models\Filme;
use App\Models\Sala;
use App\Service\Payment;
use App\Models\Recibo;



class CartController extends Controller
{
    public function index(Request $request)
    {
        $configuracao = DB::table('configuracao')->first();
        $carrinho = $request->session()->get('carrinho', []);
        
        return view('carrinho.index')
            ->withConfiguracao($configuracao)
            ->with('pageTitle', 'Carrinho de compras')
            ->with('carrinho', session('carrinho') ?? []);
    }

    
    public function store_bilhete(Request $request, Sessao $sessao)
    {
        $configuracao = DB::table('configuracao')->first();
        $titulo = Filme::query();
        $titulo = $titulo->select('id as id', 'titulo AS titulo')->where('id', '=', $sessao->filme_id)->first();
        $sala = Sala::query();
        $sala = $sala->select('id as id', 'nome as nome')->where('id', '=', $sessao->sala_id)->first();
        $carrinho = $request->session()->get('carrinho', []);
        $qtd = ($carrinho[$sessao->id]['qtd'] ?? 0) + 1;
        $carrinho[$sessao->id] = [
            'id' => $sessao->id,
            'qtd' => $qtd,
            'titulo' => $titulo->titulo,
            'horario_inicio' => $sessao->horario_inicio,
            'sala' => $sala->nome,
            'data' => $sessao->data,
        ];
        $request->session()->put('carrinho', $carrinho);
        return back()
        ->withConfiguracao($configuracao)
            ->with('alert-msg', 'Foi adicionado um bilhete para a sessão do filme "' . $titulo->titulo . '" para o dia '.$sessao->data.' ás ' . $sessao->horario_inicio . ' ao carrinho! Quantidade de bilhetes = ' .  $qtd)
            ->with('alert-type', 'success');
    }

    public function destroy(Request $request)
    {
        $configuracao = DB::table('configuracao')->first();
        $request->session()->forget('carrinho');
        return back()
        ->withConfiguracao($configuracao)
            ->with('alert-msg', 'Carrinho foi limpo!')
            ->with('alert-type', 'danger');
    }

    public function update_sessao(Request $request, Sessao $sessao)
    {
        $configuracao = DB::table('configuracao')->first();
        $titulo = Filme::query();
        $titulo = $titulo->select('id as id', 'titulo AS titulo')->where('id', '=', $sessao->filme_id)->first();
        $sala = Sala::query();
        $sala = $sala->select('id as id', 'nome as nome')->where('id', '=', $sessao->sala_id)->first();
        $carrinho = $request->session()->get('carrinho', []);
        $qtd = $carrinho[$sessao->id]['qtd'] ?? 0;
        $qtd += $request->quantidade;
        if ($request->quantidade < 0) {
            $msg = 'Foram removidos ' . -$request->quantidade . ' bilhete(s) para a sessão do filme "' . $titulo->titulo . '" para o dia '.$sessao->data.' ás '. $sessao->horario_inicio . '! Quantidade de bilhetes atuais = ' .  $qtd;
        } elseif ($request->quantidade > 0) {
            $msg = 'Foram adicionadas ' . $request->quantidade . ' bilhete(s) para a sessão do filme "' . $titulo->titulo . '" para o dia ' .$sessao->data. ' ás '. $sessao->horario_inicio . '! Quantidade de bilhetes atuais = ' .  $qtd;
        }
        if ($qtd <= 0) {
            unset($carrinho[$sessao->id]);
            $msg = 'Foram removidos todos os bilhetes para a sessão do filme "' . $titulo->titulo . '" para o dia '. $sessao->data .' ás '.$sessao->horario_inicio;
        } else {
            $carrinho[$sessao->id] = [
                'id' => $sessao->id,
                'qtd' => $qtd,
                'titulo' => $titulo->titulo,
                'horario_inicio' => $sessao->horario_inicio,
                'sala' => $sala->nome,
                'data' => $sessao->data,
            ];
        }
        $request->session()->put('carrinho', $carrinho);
        return back()
        ->withConfiguracao($configuracao)
            ->with('alert-msg', $msg)
            ->with('alert-type', 'success');
    }

    public function destroy_sessao(Request $request, Sessao $sessao)
    {
        $configuracao = DB::table('configuracao')->first();
        $titulo = Filme::query();
        $titulo = $titulo->select('id as id', 'titulo AS titulo')->where('id', '=', $sessao->filme_id)->first();
        $sala = Sala::query();
        $sala = $sala->select('id as id', 'nome as nome')->where('id', '=', $sessao->sala_id)->first();
        $carrinho = $request->session()->get('carrinho', []);
        if (array_key_exists($sessao->id, $carrinho)) {
            unset($carrinho[$sessao->id]);
            $request->session()->put('carrinho', $carrinho);
            return back()
            ->withConfiguracao($configuracao)
                ->with('alert-msg', 'Foram removidos todos os bilhetes para a sessão do filme "' . $titulo->titulo . '" para o dia '. $sessao->data .' ás '.$sessao->horario_inicio)
                ->with('alert-type', 'success');
        }
        return back()
        ->withConfiguracao($configuracao)
            ->with('alert-msg', 'A sessão do filme  "' . $titulo->titulo .  '" para o dia ' . $sessao->data . ' ás '.$sessao->horario_inicio . ' já não tinha inscrições no carrinho!')
            ->with('alert-type', 'warning');
    }

    public function preparePayment(Request $request)
    {
        $precoFinal = $request['precoFinal'];
        
        $configuracao = DB::table('configuracao')->first();
                
        $tipoPagamento = $request['tipoPagamento'];
        
 
        return view('carrinho.payment')->withTipoPagamento($tipoPagamento)->withConfiguracao($configuracao)->withPrecoFinal($precoFinal)->with('carrinho', session('carrinho') ?? []);;
    }

    public function store(Request $request){
        $precoFinal = $request['precoFinal'];
        $configuracao = DB::table('configuracao')->first();
        $user = \Auth::user();
        $user_name = User::where('id', '=', $user->id)->first();

        $cliente = Cliente::where('id', '=', $user->id)->first();
        $mytime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $data = Carbon\Carbon::parse($mytime)->format($format1);
        $carrinho = $request->session()->get('carrinho', []);
        $sessoes = $request->session()->get('carrinho', ['id']);
         if($request['mbway'] != null){
            $mbwayNumero = $request['mbway'];
            if(Payment::payWithMBway($mbwayNumero)){
                
                $recibo = new Recibo;
                $recibo->cliente_id = $cliente->id;
                $recibo->data = $data;

                $recibo->preco_total_sem_iva = $precoFinal - ($precoFinal * ($configuracao->percentagem_iva / 100));
                $recibo->preco_total_sem_iva = number_format($recibo->preco_total_sem_iva, 2,'.',' ');
                
                $recibo->preco_total_com_iva = $precoFinal;

                $recibo->iva = $configuracao->percentagem_iva;
                $recibo->iva = number_format($recibo->iva, 2,'.',' ');
              
                if($cliente->nif != null){
                    $recibo->nif = $cliente->nif;
                }else{
                    $recibo->nif = null;
                }

                $recibo->nome_cliente = $user_name->name;
                $recibo->tipo_pagamento = "MBWAY";
                $recibo->ref_pagamento = $request['mbway'];
                $recibo_id = Recibo::query();
                $recibo_id = $recibo_id->select('id as id')->orderBy('id','DESC')->first();
                
                $recibo->save();

                foreach($carrinho as $sessao){
                    $bilhete = new Bilhete;
                    
                    //bilhete->recibo_id ta a ficar com -1 do que era supost por causa  $recibo_id->select('id as id')->orderBy('id','DESC')->first();
                    $bilhete->cliente_id = $cliente->id;
                    $bilhete->preco_sem_iva = $configuracao->preco_bilhete_sem_iva;
                    $bilhete->estado = "não usado";
                    $bilhete->sessao_id = $sessao['id'];
                    $bilhete->lugar_id = 18;
                    
                  // $bilhete->recibo_id = $id_ultimo_recibo;
                    $ultimo_recibo = Recibo::query();
                    $ultimo_recibo = $ultimo_recibo->orderBy('id','DESC')->value('id');
                    $bilhete->recibo_id = $ultimo_recibo;
                   // dd($bilhete);

                    $bilhete->save();
                }

                
                return $this->destroy($request);

            }else{
                
               /* return redirect()->route('carrinho.index')
                ->with('alert-msg', "Pagamento não autorizado!")
                ->with('alert-type', 'danger');*/
            }
        }

        if($request['paypal'] != null){
            $paypalMail = $request['paypal'];
            if(Payment::payWithPaypal($paypalMail)){
                
                $recibo = new Recibo;
                $recibo->cliente_id = $cliente->id;
                $recibo->data = $data;

                $recibo->preco_total_sem_iva = $precoFinal - ($precoFinal * ($configuracao->percentagem_iva / 100));
                $recibo->preco_total_sem_iva = number_format($recibo->preco_total_sem_iva, 2,'.',' ');
                
                $recibo->preco_total_com_iva = $precoFinal;

                $recibo->iva = $configuracao->percentagem_iva;
                $recibo->iva = number_format($recibo->iva, 2,'.',' ');
              
                if($cliente->nif != null){
                    $recibo->nif = $cliente->nif;
                }else{
                    $recibo->nif = null;
                }

                $recibo->nome_cliente = $user_name->name;
                $recibo->tipo_pagamento = "PAYPAL";
                $recibo->ref_pagamento = $request['paypal'];
                $recibo_id = Recibo::query();
                $recibo_id = $recibo_id->select('id as id')->orderBy('id','DESC')->first();
 
                $recibo->save();

                
                $bilhete = new Bilhete;
                $bilhete->recibo_id = $recibo_id;
                $bilhete->cliente_id = $cliente->id;
                $bilhete->preco_sem_iva = $configuracao->preco_bilhete_sem_iva;
                $bilhete->estado = "não usado";
              
                $bilhete->save();
                
                return $this->destroy($request);

            }else{
               /* return redirect()->route('carrinho.index')
                ->with('alert-msg', "Pagamento não autorizado!")
                ->with('alert-type', 'danger');*/
            }
        }

        if(($request['visa_digitos'] && $request['visa_cvc']) != null){
            $visaDigitos = $request['visa_digitos'];
            if(Payment::payWithVisa($visaDigitos)){
                
                $recibo = new Recibo;
                $recibo->cliente_id = $cliente->id;
                $recibo->data = $data;

                $recibo->preco_total_sem_iva = $precoFinal - ($precoFinal * ($configuracao->percentagem_iva / 100));
                $recibo->preco_total_sem_iva = number_format($recibo->preco_total_sem_iva, 2,'.',' ');
                
                $recibo->preco_total_com_iva = $precoFinal;

                $recibo->iva = $configuracao->percentagem_iva;
                $recibo->iva = number_format($recibo->iva, 2,'.',' ');
              
                if($cliente->nif != null){
                    $recibo->nif = $cliente->nif;
                }else{
                    $recibo->nif = null;
                }

                $recibo->nome_cliente = $user_name->name;
                $recibo->tipo_pagamento = "VISA";
                $recibo->ref_pagamento = $request['visa_digitos'];
                $recibo_id = Recibo::query();
                $recibo_id = $recibo_id->select('id as id')->orderBy('id','DESC')->first();
                
 
                $recibo->save();

                
                $bilhete = new Bilhete;
                $bilhete->recibo_id = $recibo_id;
                $bilhete->cliente_id = $cliente->id;
                $bilhete->preco_sem_iva = $configuracao->preco_bilhete_sem_iva;
                $bilhete->estado = "não usado";
               
                $bilhete->save();
                
                return $this->destroy($request);

            }else{
                //return $this->index($request)->with('alert-msg', 'Pagamento Inválido!')->with('alert-type', 'danger');
               /* return redirect()->route('carrinho.index')
                ->with('alert-msg', "Pagamento não autorizado!")
                ->with('alert-type', 'danger');*/
            }
            
        }
        

    }
    
}


