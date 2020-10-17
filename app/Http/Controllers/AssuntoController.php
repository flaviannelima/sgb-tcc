<?php

namespace App\Http\Controllers;

use App\Assunto;
use App\Http\Requests\Assunto\StoreRequest;
use App\Http\Requests\Assunto\UpdateRequest;
use Illuminate\Http\Request;
use PDF;

class AssuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assuntos = Assunto::orderBy('descricao')->paginate(10);
        return view('assuntos.index',['assuntos' => $assuntos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assuntos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $assunto = Assunto::create($request->all());
        return redirect()->route('assuntos.index')
        ->with('success','Assunto '.$assunto->descricao.' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */
    public function show(Assunto $assunto)
    {
        return view('assuntos.show',['assunto' => $assunto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */
    public function edit(Assunto $assunto)
    {
        return view('assuntos.edit',['assunto' => $assunto]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Assunto $assunto)
    {
        $assunto->update($request->all());
        return redirect()->route('assuntos.index')
        ->with('success','Assunto '.$assunto->descricao.' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assunto  $assunto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assunto $assunto)
    {
        if(count($assunto->obras()->get())){
            return redirect()->route('assuntos.index')->withErrors(['msg' => 'Este assunto não
            pode ser excluído, pois está associado a obras cadastradas']);
        }
        $assunto->delete();
        return redirect()->route('assuntos.index')
        ->with('success','Assunto '.$assunto->descricao.' excluído com sucesso!');
    }

    /**
     * Busca e mostra os assuntos que contém a descrição pesquisada.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request){
        $busca = $this->pesquisa($request);
        $assuntos = $busca
        ->orderBy('descricao')
        ->paginate(10);
  
        return view('assuntos.index',['assuntos' => $assuntos, 
        'request' => $request]);
    }

    private function pesquisa(Request $request){
        $busca = Assunto::query();
        if ($request->descricao)
            $busca->where('descricao', 'LIKE', '%' . $request->descricao . '%');
        return $busca;
    }

    public function pdf(Request $request){
        $assuntos = $this->pesquisa($request)->orderBy('descricao')->get();
        $pdf = PDF::loadView('assuntos.pdf', ['assuntos'=>$assuntos]);
        return $pdf->download('assuntos.pdf');
    }
}
