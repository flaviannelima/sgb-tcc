<?php

namespace App\Http\Controllers;

use App\Coordenador;
use App\Http\Requests\Coordenador\StoreRequest;
use App\User;
use Illuminate\Http\Request;
use PDF;

class CoordenadorController extends Controller
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
            coordenador, pois está desativado']);
        }
        $coordenador = Coordenador::create($request->all());
        return redirect()->route('users.index')
        ->with('success','Usuário '.$coordenador->user()->first()->name.' cadastrado como 
        coordenador com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coordenador  $coordenador
     * @return \Illuminate\Http\Response
     */
    public function show(Coordenador $coordenador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coordenador  $coordenador
     * @return \Illuminate\Http\Response
     */
    public function edit(Coordenador $coordenador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coordenador  $coordenador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coordenador $coordenador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coordenador  $coordenador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coordenador $coordenador)
    {
        $coordenador->ativo=false;
        $coordenador->save();
        return redirect()->route('users.index')
        ->with('success','Coordenador '.$coordenador->user()->first()->name.' desativado com sucesso!');
    }

    public function ativa(Coordenador $coordenador){
        $coordenador->ativo = true;
        $coordenador->save();
        return redirect()->route('users.index')
        ->with('success','Coordenador '.$coordenador->user()->first()->name.' ativado com sucesso!');
    }

    public function pdf(){
        $coordenadores = Coordenador::all();
        $pdf = PDF::loadView('coordenadores.pdf', ['coordenadores'=>$coordenadores]);
        return $pdf->download('coordenadores.pdf');
    }
}
