<?php

namespace App\Http\Controllers;

use App\Autor;
use App\Http\Requests\Autor\StoreRequest;
use App\Http\Requests\Autor\UpdateRequest;
use PDF;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $autores = Autor::orderBy('nome')->paginate(10);
        return view('autores.index',['autores' => $autores]);
    }

    /**
     * Busca e mostra os autores que contém o nome pesquisado.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request){
        $busca = $this->pesquisa($request);
        $autores = $busca->orderBy('nome')->paginate(10);
        return view('autores.index',['autores' => $autores, 'request' => $request]);
    }

    private function pesquisa(Request $request){
        $busca = Autor::query();
        if ($request->nome)
            $busca->where('nome', 'LIKE', '%' . $request->nome . '%');
        return $busca;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
       
        $autor = Autor::create($request->all());
        return redirect()->route('autores.index')
        ->with('success','Autor '.$autor->nome.' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function show(Autor $autor)
    {
        return view('autores.show',['autor' => $autor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function edit(Autor $autor)
    {
        return view('autores.edit',['autor' => $autor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Autor $autor)
    {
        $autor->update($request->all());
        return redirect()->route('autores.index')
        ->with('success','Autor '.$autor->nome.' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Autor  $autor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Autor $autor)
    {
        if(count($autor->obras()->get())){
            return redirect()->route('autores.index')->withErrors(['msg' => 'Este autor não
            pode ser excluído, pois possui obras cadastradas']);
        }
        $autor->delete();
        return redirect()->route('autores.index')
        ->with('success','Autor '.$autor->nome.' excluído com sucesso!');
    }


    public function pdf(Request $request){
        $autores = $this->pesquisa($request)->orderBy('nome')->get();
        $pdf = PDF::loadView('autores.pdf', ['autores'=>$autores]);
        return $pdf->download('autores.pdf');
    }
}
