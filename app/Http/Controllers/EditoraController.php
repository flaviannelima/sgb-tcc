<?php

namespace App\Http\Controllers;

use App\Editora;
use App\Http\Requests\Editora\StoreRequest;
use App\Http\Requests\Editora\UpdateRequest;
use PDF;
use Illuminate\Http\Request;

class EditoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editoras = Editora::orderBy('nome')->paginate(10);
        return view('editoras.index',['editoras' => $editoras]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('editoras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $editora = Editora::create($request->all());
        return redirect()->route('editoras.index')
        ->with('success','Editora '.$editora->nome.' cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function show(Editora $editora)
    {
        return view('editoras.show',['editora' => $editora]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function edit(Editora $editora)
    {

        return view('editoras.edit',['editora' => $editora]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Editora $editora)
    {

        $editora->update($request->all());
        return redirect()->route('editoras.index')
        ->with('success','Editora '.$editora->nome.' editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Editora  $editora
     * @return \Illuminate\Http\Response
     */
    public function destroy(Editora $editora)
    {
        if(count($editora->obras()->get())){
            return redirect()->route('editoras.index')->withErrors(['msg' => 'Esta editora não
            pode ser excluída, pois está associada a obras cadastradas']);
        }
        $editora->delete();
        return redirect()->route('editoras.index')
        ->with('success','Editora '.$editora->nome.' excluída com sucesso!');
    }

    /**
     * Busca e mostra as editoras que contém o nome pesquisado.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request){
       $busca = $this->pesquisa($request);
        $editoras = $busca->orderBy('nome')
        ->paginate(10);
        return view('editoras.index',['editoras' => $editoras, 'request' => $request]);
    }

    private function pesquisa(Request $request){
        $busca = Editora::query();
        if ($request->nome)
            $busca->where('nome', 'LIKE', '%' . $request->nome . '%');
        return $busca;
    }

    public function pdf(Request $request){
        $editoras = $this->pesquisa($request)->orderBy('nome')->get();
        $pdf = PDF::loadView('editoras.pdf', ['editoras'=>$editoras]);
        return $pdf->download('editoras.pdf');
    }
}
