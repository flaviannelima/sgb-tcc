<?php

namespace App\Http\Controllers;

use App\Atendente;
use App\Http\Requests\Atendente\StoreRequest;
use App\User;
use Illuminate\Http\Request;
use PDF;
class AtendenteController extends Controller
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
        $user = User::find($request->user);
        if(!$user->ativo){
            return redirect()->route('users.index')->withErrors(['msg' => 'Este usuário não pode ser cadastrado como 
            atendente, pois está desativado']);
        }
        $atendente = Atendente::create($request->all());
        return redirect()->route('users.index')
        ->with('success','Usuário '.$atendente->user()->first()->name.' cadastrado como 
        atendente com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atendente  $atendente
     * @return \Illuminate\Http\Response
     */
    public function show(Atendente $atendente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atendente  $atendente
     * @return \Illuminate\Http\Response
     */
    public function edit(Atendente $atendente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Atendente  $atendente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Atendente $atendente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atendente  $atendente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Atendente $atendente)
    {
        $atendente->ativo = false;
        $atendente->save();
        return redirect()->route('users.index')
        ->with('success','Atendente '.$atendente->user()->first()->name.' desativado com sucesso!');
    }

    public function ativa(Atendente $atendente){
        $atendente->ativo = true;
        $atendente->save();
        return redirect()->route('users.index')
        ->with('success','Atendente '.$atendente->user()->first()->name.' ativado com sucesso!');
    }

    public function pdf(){
        $atendentes = Atendente::all();
        $pdf = PDF::loadView('atendentes.pdf', ['atendentes'=>$atendentes]);
        return $pdf->download('atendentes.pdf');
    }
}
