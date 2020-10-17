<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Http\Requests\Categoria\StoreRequest;
use App\Http\Requests\Categoria\UpdateRequest;
use Illuminate\Http\Request;
use PDF;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::orderBy('descricao')->paginate(10);
        return view('categorias.index',['categorias' => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $categoria = Categoria::create($request->all());
        return redirect()->route('categorias.index')
        ->with('success','Categoria '.$categoria->descricao.' cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        return view('categorias.show',['categoria' => $categoria]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {

        return view('categorias.edit',['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Categoria $categoria)
    {

        $categoria->update($request->all());
        return redirect()->route('categorias.index')
        ->with('success','Categoria '.$categoria->descricao.' editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        if(count($categoria->obras()->get())){
            return redirect()->route('categorias.index')->withErrors(['msg' => 'Esta categoria não
            pode ser excluída, pois está associada a obras cadastradas']);
        }
        $categoria->delete();
        return redirect()->route('categorias.index')
        ->with('success','Categoria '.$categoria->descricao.' excluída com sucesso!');
    }


    /**
     * Busca e mostra os assuntos que contém a descrição pesquisada.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request){
        $busca = $this->pesquisa($request);
        $categorias = $busca
        ->orderBy('descricao')
        ->paginate(10);
  
        return view('categorias.index',['categorias' => $categorias, 
        'request' => $request]);
    }

    private function pesquisa(Request $request){
        $busca = Categoria::query();
        if ($request->descricao)
            $busca->where('descricao', 'LIKE', '%' . $request->descricao . '%');
        return $busca;
    }

    public function pdf(Request $request){
        $categorias = $this->pesquisa($request)->orderBy('descricao')->get();
        $pdf = PDF::loadView('categorias.pdf', ['categorias'=>$categorias]);
        return $pdf->download('categorias.pdf');
    }
}
