<?php

namespace App\Http\Controllers;

use App\Exemplar;
use App\Http\Requests\Exemplar\StoreRequest;
use App\Http\Requests\Exemplar\UpdateRequest;
use App\Obra;
use Illuminate\Http\Request;

class ExemplarController extends Controller
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
     * @param  Obra  $obra
     * @return \Illuminate\Http\Response
     */
    public function create(Obra $obra)
    {
        if(!$obra->ativo){
            return redirect()->route('obras.show',$obra->id)->withErrors(['msg' => 'Exemplar não 
            pode ser criado, pois esta obra está desativada']);
        }
        return view('exemplares.create', [
            "obra" => $obra
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $obra = Obra::find($request->obra);
        if(!$obra->ativo){
            return redirect()->route('obras.show',$obra->id)->withErrors(['msg' => 'Exemplar não 
            pode ser criado, pois esta obra está desativada']);
        }
        Exemplar::create($request->all());
        return redirect()->route('obras.show',$request->obra)
            ->with('success', 'Exemplar ' . $request->codigo_barras . ' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exemplar  $exemplar
     * @return \Illuminate\Http\Response
     */
    public function show(Exemplar $exemplar)
    {
        return view('exemplares.show',['exemplar' => $exemplar]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exemplar  $exemplar
     * @return \Illuminate\Http\Response
     */
    public function edit(Exemplar $exemplar)
    {
        if(!$exemplar->obra()->first()->ativo){
            return redirect()->route('obras.show',$exemplar->obra)->withErrors(['msg' => 'Este exemplar não 
            pode ser editado, pois esta obra está desativada']);
        }
        if(!$exemplar->ativo){
            return redirect()->route('obras.show',$exemplar->obra)->withErrors(['msg' => 'Este exemplar não 
            pode ser editado, pois está desativado']);
        }
        return view('exemplares.edit', [
            'exemplar' => $exemplar
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exemplar  $exemplar
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Exemplar $exemplar)
    {
        if(!$exemplar->obra()->first()->ativo){
            return redirect()->route('obras.show',$exemplar->obra)->withErrors(['msg' => 'Este exemplar não 
            pode ser editado, pois esta obra está desativada']);
        }
        if(!$exemplar->ativo){
            return redirect()->route('obras.show',$exemplar->obra)->withErrors(['msg' => 'Este exemplar não 
            pode ser editado, pois está desativado']);
        }
        $exemplar->update($request->all());
        return redirect()->route('obras.show',$request->obra)
            ->with('success', 'Exemplar ' . $request->codigo_barras . ' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exemplar  $exemplar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exemplar $exemplar)
    {
        $exemplar->ativo = false;
        $exemplar->save();
        return redirect()->route('obras.show',$exemplar->obra)
            ->with('success', 'Exemplar ' . $exemplar->codigo_barras . ' desativado com sucesso!');
    }

    public function ativa(Exemplar $exemplar){
        if(!$exemplar->obra()->first()->ativo){
            return redirect()->route('obras.show',$exemplar->obra)->withErrors(['msg' => 'Exemplar não 
            pode ser ativado, pois esta obra está desativada']);
        }
        $exemplar->ativo = true;
        $exemplar->save();
        return redirect()->route('obras.show',$exemplar->obra)
        ->with('success','Exemplar '.$exemplar->codigo_barras.' ativado com sucesso!');
    }
}
