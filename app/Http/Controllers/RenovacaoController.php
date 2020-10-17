<?php

namespace App\Http\Controllers;

use App\Emprestimo;
use App\Http\Requests\Renovacao\StoreRequest;
use App\Leitor;
use App\Renovacao;
use Illuminate\Http\Request;

class RenovacaoController extends Controller
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

        $emprestimo = Emprestimo::find($request->emprestimo);
        if(!$emprestimo->exemplar()->first()->obra()->first()->ativo ){
            return redirect()->back()->withErrors(['msg' => 'Esta obra não pode ser 
            renovada, pois está desativada']);
        }
        if(!$emprestimo->exemplar()->first()->ativo ){
            return redirect()->back()->withErrors(['msg' => 'Este exemplar não pode ser 
            renovado, pois está desativado']);
        }
        if(!$emprestimo->leitor()->first()->user()->first()->ativo || !$emprestimo->leitor()->first()->ativo ){
            return redirect()->back()->withErrors(['msg' => 'Este exemplar não pode ser 
            renovado, pois este leitor está desativado']);
        }
        $emprestimos = Emprestimo::where('leitor', $emprestimo->leitor)->whereNull('data_devolucao')->get();
        if($emprestimo->data_devolucao != null){
            return redirect()->route('leitores.show', $emprestimo->leitor)
                            ->withErrors(['msg' => 'Este exemplar já foi devolvido.']);
        }
        if($emprestimo->data_prevista_devolucao >= date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days'))
        || (isset($emprestimo->renovacoes()->orderBy('data_prevista_devolucao', 'desc')->first()->data_prevista_devolucao)
        && $emprestimo->renovacoes()->orderBy('data_prevista_devolucao', 'desc')->first()->data_prevista_devolucao >=
        date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')))){
            return redirect()->route('leitores.show', $emprestimo->leitor)
                            ->withErrors(['msg' => 'Não é possível renovar este exemplar, devido a data prevista de devolução
                            ser maior ou igual à daqui 7 dias']);
        }
        $leitor = Leitor::findOrFail($emprestimo->leitor);
        $multasNaoPagas = $leitor->emprestimos()->whereHas('multa', function ($q) use ($request) {
            $q->whereRaw('valor_pago<valor_multa');
        })->get();
        if(count($multasNaoPagas)){
            return redirect()->route('leitores.show',$emprestimo->leitor)
                ->withErrors(['msg' =>'Leitor possui débitos.']);
        }
        foreach ($emprestimos as $e) {
            if ($e->data_prevista_devolucao < date('Y-m-d')) {
                if (isset($e->renovacoes()->orderBy('data_prevista_devolucao', 'desc')->first()->data_prevista_devolucao)) {
                    if (
                        $e->renovacoes()->orderBy('data_prevista_devolucao', 'desc')->first()->data_prevista_devolucao <
                        date('Y-m-d')
                    ) {
                        return redirect()->route('leitores.show', $emprestimo->leitor)
                            ->withErrors(['msg' => 'Leitor possui débitos.']);
                    }
                }
                else{
                    return redirect()->route('leitores.show', $emprestimo->leitor)
                            ->withErrors(['msg' => 'Leitor possui débitos.']);
                }
            }
        }

        Renovacao::create([
            'emprestimo' => $request->emprestimo,
            'data_prevista_devolucao' => date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')),
            'usuario_renovou' => $request->user()->id
        ]);



        return redirect()->route('leitores.show', $emprestimo->leitor)
            ->with('success', 'Exemplar ' . $emprestimo->exemplar()->first()->codigo_barras . ' renovado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Renovacao  $renovacao
     * @return \Illuminate\Http\Response
     */
    public function show(Renovacao $renovacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Renovacao  $renovacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Renovacao $renovacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Renovacao  $renovacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Renovacao $renovacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Renovacao  $renovacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Renovacao $renovacao)
    {
        //
    }
}
