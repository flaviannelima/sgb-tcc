<?php

namespace App\Http\Controllers;

use App\Http\Requests\Leitor\StoreRequest;
use App\Http\Requests\Leitor\UpdateRequest;
use App\Leitor;
use App\Multa;
use App\User;
use Illuminate\Http\Request;
use PDF;

class LeitorController extends Controller
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
    public function create(User $user)
    {

        if(!$user->ativo){
            return redirect()->route('users.index')->withErrors(['msg' => 'Este usuário não pode ser cadastrado como 
            leitor, pois está desativado']);
        }
        return view('leitores.create', [
            "user" => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $leitor = Leitor::create($request->all());
        if(!$leitor->user()->first()->ativo ){
            return redirect()->route('users.index')->withErrors(['msg' => 'Este usuário não pode ser cadastrado como 
            leitor, pois está desativado']);
        }
        return redirect()->route('users.index')
        ->with('success','Leitor '.$leitor->user()->first()->name.' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leitor  $leitor
     * @return \Illuminate\Http\Response
     */
    public function show(Leitor $leitor)
    {
        $emprestimos = $leitor->emprestimos()->whereNull('data_devolucao')->get();
        $multasNaoPagas = Multa::selectRaw("SUM(valor_multa) - SUM(valor_pago) as multa")->join('emprestimos','emprestimo','emprestimos.id')
        ->where('leitor',$leitor->id)->whereRaw('valor_pago<valor_multa')->first()->multa;
        return view('leitores.show',['leitor' => $leitor,'emprestimos' => $emprestimos,'multasNaoPagas' => $multasNaoPagas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leitor  $leitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Leitor $leitor)
    {
        if(!$leitor->user()->first()->ativo || !$leitor->ativo){
            return redirect()->route('users.index')->withErrors(['msg' => 'Este leitor não pode ser editado, pois está desativado']);
        }

        return view('leitores.edit', [
            'leitor' => $leitor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Leitor  $leitor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Leitor $leitor)
    {
        if(!$leitor->user()->first()->ativo || !$leitor->ativo){
            return redirect()->route('users.index')->withErrors(['msg' => 'Este leitor não pode ser editado, 
            pois está desativado']);
        }
        $leitor->update($request->all());
        return redirect()->route('users.index')->with('success','Leitor '.
        $leitor->user()->first()->name.' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leitor  $leitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leitor $leitor)
    {
        $leitor->ativo = false;
        $leitor->save();
        return redirect()->route('users.index')
        ->with('success', 'Leitor ' . $leitor->user()->first()->name . ' desativado com sucesso!');
    }

    public function ativa(Leitor $leitor){
        $leitor->ativo = true;
        $leitor->save();
        return redirect()->route('users.index')
        ->with('success','Leitor '.$leitor->user()->first()->name.' ativado com sucesso!');
    }

    public function pdf(){
        $leitores = Leitor::all();
        $pdf = PDF::loadView('leitores.pdf', ['leitores'=>$leitores]);
        return $pdf->setPaper('a4', 'landscape')->download('leitores.pdf');
    }

   
}
