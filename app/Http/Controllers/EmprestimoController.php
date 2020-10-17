<?php

namespace App\Http\Controllers;

use App\Emprestimo;
use App\Exemplar;
use App\Http\Requests\Emprestimo\StoreRequest;
use App\Leitor;
use App\Multa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        
        if (!Hash::check($request->password,Leitor::find($request->leitor)->user()->first()->password)) {
            return redirect()->route('leitores.show',$request->leitor)
            ->withErrors(['msg' =>'Senha inválida.']);
        }
        $exemplar = Exemplar::where('codigo_barras',$request->exemplar)->first();
        if(!$exemplar->ativo){
            return redirect()->route('leitores.show',$request->leitor)->withErrors(['msg' => 'Este exemplar não 
            pode ser emprestado, pois está desativado']);
        }

        if(!$exemplar->obra()->first()->ativo){
            return redirect()->route('leitores.show',$request->leitor)->withErrors(['msg' => 'Este exemplar não 
            pode ser emprestado, pois a obra a qual ele está associado está desativada']);
        }
        $leitor = Leitor::find($request->leitor);
        if(!$leitor->ativo || !$leitor->user()->first()->ativo){
            return redirect()->route('leitores.show',$request->leitor)->withErrors(['msg' => 'Este exemplar não 
            pode ser emprestado, pois o leitor está desativado']);
        }
        $emprestimo = Emprestimo::where('exemplar',$exemplar->id)->whereNull('data_devolucao')->get();
        if(count($emprestimo)){
            return redirect()->route('leitores.show',$request->leitor)
            ->withErrors(['msg' =>'Exemplar '.$exemplar->codigo_barras.' já está emprestado.']);
        }
        $emprestimo = Emprestimo::where('leitor',$request->leitor)->whereNull('data_devolucao')->get();
        if(count($emprestimo)>1){
            return redirect()->route('leitores.show',$request->leitor)
            ->withErrors(['msg' =>'Leitor excedeu o limite de empréstimo.']);
        }
        if(count($emprestimo)){
            $dataPrevista = (!count($emprestimo[0]->renovacoes()->get())) ? $emprestimo[0]->data_prevista_devolucao :
            $emprestimo[0]->renovacoes()->orderBy('data_prevista_devolucao','desc')->first()->data_prevista_devolucao;
            if($dataPrevista < date('Y-m-d'))
                return redirect()->route('leitores.show',$request->leitor)
                ->withErrors(['msg' =>'Leitor possui débitos.']);
        }
        $multasNaoPagas = $leitor->emprestimos()->whereHas('multa', function ($q) use ($request) {
            $q->whereRaw('valor_pago<valor_multa');
        })->get();
        if(count($multasNaoPagas)){
            return redirect()->route('leitores.show',$request->leitor)
                ->withErrors(['msg' =>'Leitor possui débitos.']);
        }

        Emprestimo::create([
            'exemplar' => $exemplar->id,
            'leitor' => $request->leitor,
            'usuario_emprestou' => $request->user()->id,
            'data_prevista_devolucao' => date('Y-m-d',strtotime(date('Y-m-d').' + 14 days'))
        ]);

        return redirect()->route('leitores.show',$request->leitor)
            ->with('success', 'Exemplar ' . $request->exemplar . ' emprestado com sucesso!');
    }

    public function devolucao(Emprestimo $emprestimo)
    {
        if($emprestimo->data_devolucao != null){
            return redirect()->route('leitores.show',$emprestimo->leitor)
            ->withErrors(['msg' =>'Este exemplar já foi devolvido.']);
        }
        DB::transaction(function () use ($emprestimo) {
            if($emprestimo->data_prevista_devolucao<date('Y-m-d')){
                $renovacao = $emprestimo->renovacoes()->orderBy('data_prevista_devolucao','desc')->first();
                if(!isset($renovacao->data_prevista_devolucao) || $renovacao->data_prevista_devolucao<date('Y-m-d'))
                    Multa::create([
                        "emprestimo" => $emprestimo->id,
                        "valor_multa" => (!isset($renovacao->data_prevista_devolucao))?
                        floor((strtotime(date('Y-m-d'))-strtotime($emprestimo->data_prevista_devolucao) ) / (60 * 60 * 24)):
                        floor((strtotime(date('Y-m-d'))-strtotime($renovacao->data_prevista_devolucao)) / (60 * 60 * 24))
                    ]);
            }
            $emprestimo->data_devolucao = date('Y-m-d');
            $emprestimo->usuario_devolveu = auth()->user()->id;
            $emprestimo->save();
        });
        return redirect()->back()
            ->with('success', 'Exemplar ' . $emprestimo->exemplar()->first()->codigo_barras . ' devolvido com sucesso!');
    
    }

    public function buscaPorCodigoDeBarrasDevolucao(Request $request){
        $exemplar = Exemplar::where('codigo_barras',$request->codigo_barras)->first();
        if($exemplar == null){
            return redirect()->route('exemplares.devolucao')
            ->withErrors( 'Exemplar com o código de barras ' . $request->codigo_barras . ' não foi encontrado.');
        }
        $emprestimo = Emprestimo::whereNull('data_devolucao')->where('exemplar',$exemplar->id)->first();
        if($emprestimo == null){
            return redirect()->route('exemplares.devolucao')
            ->withErrors('Exemplar com o código de barras ' . $request->codigo_barras . ' não está emprestado.');
        }
        return view('exemplares.devolucao',['emprestimo'=>$emprestimo]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function show(Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function edit(Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Emprestimo  $emprestimo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emprestimo $emprestimo)
    {
        //
    }
}
