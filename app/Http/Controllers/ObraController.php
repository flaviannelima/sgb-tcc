<?php

namespace App\Http\Controllers;

use App\Assunto;
use App\Autor;
use App\Categoria;
use App\Editora;
use App\Http\Requests\Obra\StoreRequest;
use App\Obra;
use App\TipoMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ObraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obras = Obra::orderBy('titulo')->paginate(9);
        $editoras = Editora::orderBy('nome')->get();
        $tiposMaterial = TipoMaterial::orderBy('descricao')->get();
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        return view('obras.index', [
            'obras' => $obras, "editoras" => $editoras, "tiposMaterial" => $tiposMaterial,
            "autores" => $autores, "assuntos" => $assuntos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $editoras = Editora::orderBy('nome')->get();
        $tiposMaterial = TipoMaterial::orderBy('descricao')->get();
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        $categorias = Categoria::orderBy('descricao')->get();
        return view('obras.create', [
            "editoras" => $editoras, "tiposMaterial" => $tiposMaterial,
            "autores" => $autores, "assuntos" => $assuntos, "categorias" => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {


        $resultado = DB::transaction(function () use ($request) {
            $obra = Obra::create($request->all());
            $obra->autores()->attach($request->input('autores'));
            $obra->assuntos()->attach($request->input('assuntos'));
            return ["obra" => $obra];
        });
        return redirect()->route('obras.index')
            ->with('success', 'Obra ' . $resultado["obra"]->titulo . ' cadastrada com sucesso!');
    }

    public function geraLocalizacao(Request $request)
    {
        $titulo = $this->tirarAcentos($request->titulo);
        $autores = $request->autores;
       
        $busca = [];
        if($autores)
        foreach ($autores as $autor) {
            $busca = Autor::find($autor)->obras()->get();
            if (count($busca)) {
                break;
            }
        }
        if (count($busca)) {
            $localizacao = substr($busca->first()->localizacao, 0, 8);
        } else {
            $localizacao = str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT) . "." . str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT) . "." .
                str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT);
        }
        if($autores && count($autores)==1)
        $autor = Autor::find($autor);
        if (isset($autor->nome) && strlen($autor->nome) >= 2)
            $localizacao .= ' ' . strtoupper(substr($this->tirarAcentos($autor->nome), 0, 2));
        else
            $localizacao .= ' AA';
        if (strlen($titulo) >= 2)
            $localizacao .= ' ' . strtoupper(substr($titulo, 0, 2));
        else
            $localizacao .= ' AA';
        echo $localizacao;
    }

    private function tirarAcentos($texto){
        $conversao = array('á' => 'a','à' => 'a','ã' => 'a','â' => 'a', 'é' => 'e',
        'ê' => 'e', 'í' => 'i', 'ï'=>'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö"=>"o",
        'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ'=>'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
        'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï'=>'I', "Ö"=>"O", 'Ó' => 'O',
        'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' =>'C', 'Ñ'=>'N', ' ' =>'');

       return strtr($texto, $conversao); 
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Obra  $obra
     * @return \Illuminate\Http\Response
     */
    public function show(Obra $obra)
    {
        return view('obras.show',['obra' => $obra]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Obra  $obra
     * @return \Illuminate\Http\Response
     */
    public function edit(Obra $obra)
    {
        if(!$obra->ativo ){
            return redirect()->route('obras.index')->withErrors(['msg' => 'Esta obra não pode ser 
            editada, pois está desativada']);
        }
        $editoras = Editora::orderBy('nome')->get();
        $tiposMaterial = TipoMaterial::orderBy('descricao')->get();
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        $categorias = Categoria::orderBy('descricao')->get();
        return view('obras.edit', [
            'obra' => $obra, "editoras" => $editoras, "tiposMaterial" => $tiposMaterial,
            "autores" => $autores, "assuntos" => $assuntos,'categorias'=>$categorias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreRequest  $request
     * @param  \App\Obra  $obra
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Obra $obra)
    {
        if(!$obra->ativo ){
            return redirect()->route('obras.index')->withErrors(['msg' => 'Esta obra não pode ser 
            editada, pois está desativada']);
        }
        $resultado = DB::transaction(function () use ($request, $obra) {
            $obra->update($request->all());
            $obra->autores()->sync($request->input('autores'));
            $obra->assuntos()->sync($request->input('assuntos'));
            return ["obra" => $obra];
        });

        return redirect()->route('obras.index')
            ->with('success', 'Obra ' . $resultado['obra']->titulo . ' editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Obra  $obra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Obra $obra)
    {
        $obra->ativo = false;
        $obra->save();
        // $obra = DB::transaction(function () use ($obra) {
        //     $obra->ativo = false;
        //     $obra->save();
        //     $exemplares = $obra->exemplares()->get();
        //     foreach($exemplares as $exemplar){
        //         $exemplar->ativo = 0;
        //         $exemplar->save();
        //     }
   
        //     return $obra;
        // });
        return redirect()->route('obras.index')
            ->with('success', 'Obra ' . $obra->titulo . ' desativada com sucesso!');
    }

    /**
     * Busca e mostra as obras que contém os termos pesquisados.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request)
    {
        
        $busca = $this->pesquisa($request);
        $obras = $busca->orderBy('titulo')->paginate(9);
        $editoras = Editora::orderBy('nome')->get();
        $tiposMaterial = TipoMaterial::orderBy('descricao')->get();
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        return view('obras.index', ['obras' => $obras, 'request' => $request, 'editoras' => $editoras,'tiposMaterial'
        =>$tiposMaterial,'autores'=>$autores,'assuntos'=>$assuntos]);
    }

    private function pesquisa(Request $request){
        $busca = Obra::query();
        if ($request->titulo)
            $busca->where('titulo', 'LIKE', '%' . $request->titulo . '%');
        if ($request->volume)
            $busca->where('volume', $request->volume);
        if ($request->tipo_material)
            $busca->where('tipo_material', $request->tipo_material);
        if ($request->situacao!="" && $request->situacao!=null)
            $busca->where('ativo', $request->situacao);
        if ($request->editora)
            $busca->where('editora', $request->editora);
        if ($request->autores)
            $busca->whereHas('autores', function ($q) use ($request) {
                $q->whereIn('autor', $request->autores);
            });
        if ($request->assuntos)
            $busca->whereHas('assuntos', function ($q) use ($request) {
                $q->whereIn('assunto', $request->assuntos);
            });
        if ($request->codigo_barras)
            $busca->whereHas('exemplares', function ($q) use ($request) {
                $q->whereIn('codigo_barras', [$request->codigo_barras]);
            });
        return $busca;
    }

    public function ativa(Obra $obra){
        $obra->ativo = true;
        $obra->save();
        return redirect()->route('obras.index')
        ->with('success','Obra '.$obra->titulo.' ativada com sucesso!');
    }

    public function pdf(Request $request){
        // ini_set('memory_limit', '-1');
        // ini_set('max_execution_time', '-1');
        $obras = $this->pesquisa($request)->orderBy("titulo")->get();
        $pdf = PDF::loadView('obras.pdf', ['obras'=>$obras]);
        
        return $pdf->setPaper('a4', 'landscape')->download('obras.pdf');
    }
}
