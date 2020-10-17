<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoMaterial\StoreRequest;
use App\Http\Requests\TipoMaterial\UpdateRequest;
use App\TipoMaterial;
use Illuminate\Http\Request;
use PDF;

class TipoMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposMaterial = TipoMaterial::orderBy('descricao')->paginate(10);
        return view('tiposmaterial.index',['tiposMaterial' => $tiposMaterial]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tiposmaterial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $tipomaterial = TipoMaterial::create($request->all());
        return redirect()->route('tiposmaterial.index')
        ->with('success','Tipo de material '.$tipomaterial->descricao.' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoMaterial  $tipoMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMaterial $tipomaterial)
    {
        return view('tiposmaterial.show',['tipomaterial' => $tipomaterial]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoMaterial  $tipoMaterial
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMaterial $tipomaterial)
    {
        return view('tiposmaterial.edit',['tipoMaterial' => $tipomaterial]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoMaterial  $tipoMaterial
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, TipoMaterial $tipomaterial)
    {
        $tipomaterial->update($request->all());
        return redirect()->route('tiposmaterial.index')
        ->with('success','Tipo de material '.$tipomaterial->descricao.' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoMaterial  $tipoMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMaterial $tipomaterial)
    {
    
        if(count($tipomaterial->obras()->get())){
            return redirect()->route('tiposmaterial.index')->withErrors(['msg' => 'Este tipo de material não
            pode ser excluído, pois está associado a obras cadastradas']);
        }
        $tipomaterial->delete();
        return redirect()->route('tiposmaterial.index')
        ->with('success','Tipo de material '.$tipomaterial->descricao.' excluído com sucesso!');
    }

    /**
     * Busca e mostra os tipos de material que contém a descrição pesquisada.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request){
       $busca = $this->pesquisa($request);
        $tiposMaterial = $busca
        ->orderBy('descricao')
        ->paginate(10);
  
        return view('tiposmaterial.index',['tiposMaterial' => $tiposMaterial, 
        'request' => $request]);
    }

    private function pesquisa(Request $request){
        $busca = TipoMaterial::query();
        if ($request->descricao)
            $busca->where('descricao', 'LIKE', '%' . $request->descricao . '%');
        return $busca;
    }


    public function pdf(Request $request){
        $tipos = $this->pesquisa($request)->orderBy('descricao')->get();
        $pdf = PDF::loadView('tiposmaterial.pdf', ['tipos'=>$tipos]);
        return $pdf->setPaper('a4')->download('tipos-material.pdf');
    }
}
