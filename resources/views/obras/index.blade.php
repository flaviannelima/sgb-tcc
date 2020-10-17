@extends('layouts.app')
@section('content')
<div class="container">
    @if ($message = Session::get('success'))

    <div class="alert alert-success alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <strong>{{ $message }}</strong>

    </div>

    @endif
    @foreach ($errors->all() as $error)


    <div class="alert alert-danger alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <strong>{{ $error}}</strong>

    </div>

    @endforeach
    <h1>Obras</h1>
    <div class="d-flex flex-row-reverse">

        @coordenador
        <a href="{{route('obras.create')}}" class="float-right ml-2 btn btn-success btn-sm" title="Cadastrar">
            <i class="fa fa-fw fa-plus-circle"></i> Cadastrar

        </a>
        <form action="{{route('obras.pdf')}}" method="POST">
            @csrf
            @if(isset($request))
            <input type="hidden" name="titulo" value="{{$request->titulo}}" />
            @if($request->autores)
            @foreach($request->autores as $a)
            <input type="hidden" name="autores[]" value="{{$a}}" />
            @endforeach
            @endif
            @if($request->assuntos)
            @foreach($request->assuntos as $a)
            <input type="hidden" name="assuntos[]" value="{{$a}}" />
            @endforeach
            @endif
            <input type="hidden" name="tipo_material" value="{{$request->tipo_material}}" />
            <input type="hidden" name="editora" value="{{$request->editora}}" />
            <input type="hidden" name="volume" value="{{$request->volume}}" />
            <input type="hidden" name="codigo_barras" value="{{$request->codigo_barras}}" />
            <input type="hidden" name="situacao" value="{{$request->situacao}}" />
            @endif
            <button type="submit" class="float-right btn btn-primary btn-sm" title="Gerar pdf de obras">
                <i class="fa fa-fw fa-download"></i> Gerar pdf
            </button>
        </form>

        @endcoordenador
    </div>


    <div class="card mt-2">
        <div class="card-header">
            <i class="fa fa-fw fa-search"></i> Pesquisar Obra



        </div>
        <div class="card-body">
            <div class="col-sm-12">
                <form method="post" action="{{route('obras.busca')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-control"
                                    value=@if(!isset($request->titulo))"" @else"{{$request->titulo}}"@endif
                                placeholder="Digite o título da obra">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Autores</label>
                                <select name="autores[]" id="autores" data-live-search="true" data-actions-box="true"
                                    class="form-control selectpicker" multiple>
                                    @foreach ($autores as $autor)
                                    <option value="{{$autor->id}}" @if(isset($request->autores) &&
                                        in_array($autor->id,$request->autores)) selected @endif>{{$autor->nome}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Assuntos</label>
                                <select name="assuntos[]" id="assuntos" data-live-search="true" data-actions-box="true"
                                    class="form-control selectpicker" multiple>
                                    @foreach ($assuntos as $assunto)
                                    <option value="{{$assunto->id}}" @if(isset($request->assuntos) &&
                                        in_array($assunto->id,$request->assuntos)) selected
                                        @endif>{{$assunto->descricao}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tipo de material</label>
                                <select name="tipo_material" id="tipo_material" data-live-search="true"
                                    class="form-control selectpicker">
                                    <option value=""></option>
                                    @foreach ($tiposMaterial as $tipo)
                                    <option value="{{$tipo->id}}" @if(isset($request->tipo_material) &&
                                        $tipo->id==$request->tipo_material) selected @endif>{{$tipo->descricao}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Editora</label>
                                <select name="editora" id="editora" data-live-search="true"
                                    class="form-control selectpicker">
                                    <option value=""></option>
                                    @foreach ($editoras as $editora)
                                    <option value="{{$editora->id}}" @if(isset($request->editora) &&
                                        $editora->id==$request->editora) selected @endif>{{$editora->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Volume</label>
                                <input type="number" name="volume" id="volume" class="form-control" 
                                value=@if(!isset($request->volume))"" @else"{{$request->volume}}"@endif>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Código de barras do exemplar</label>
                                <input type="number" name="codigo_barras" id="codigo_barras"class="form-control"
                                value=@if(!isset($request->codigo_barras))"" @else"{{$request->codigo_barras}}"@endif>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Situação</label>
                                <select name="situacao" id="situacao" class="form-control">
                                    <option value="" @if(isset($request) && ($request->situacao=="" || $request->situacao==null)) selected @endif>Todas</option>
                                    <option value="1" @if(isset($request) && $request->situacao) selected @endif>Ativa</option>
                                    <option value="0" @if(isset($request) && $request->situacao==='0') selected @endif>Desativada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">

                                <div class="mt-4">
                                    <button type="submit" name="submit" value="search" id="submit"
                                        class="btn btn-primary mt-2" title="Buscar"><i class="fa fa-fw fa-search"></i>
                                        Buscar</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr>
    @if(count($obras))
    <div class="row">
        @foreach($obras as $obra)
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body bg-primary text-white">
                    <p class="card-title">{{$obra->titulo}}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Autor(es):
                        <ul>
                            @foreach ($obra->autores()->get() as $autor)
                            <li>{{$autor->nome}}</li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="list-group-item">Assunto(s):
                        <ul>
                            @foreach ($obra->assuntos()->get() as $assunto)
                            <li>{{$assunto->descricao}}</li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="list-group-item">Tipo de material: {{$obra->tipoMaterial()->first()->descricao}}</li>
                    <li class="list-group-item">Categoria: {{$obra->categoria()->first()->descricao}}</li>
                    <li class="list-group-item">Volume: @if($obra->volume){{$obra->volume}}@else - @endif
                    </li>
                    <li class="list-group-item">Localização: {{$obra->localizacao}}</li>
                    <li class="list-group-item @if(count($obra->exemplaresDisponiveis()->get()) && $obra->ativo) bg-success @else bg-danger @endif text-white">
                        @if(!$obra->ativo)
                        Obra desativada
                        @elseif(count($obra->exemplaresDisponiveis()->get()))
                        Possui {{count($obra->exemplaresDisponiveis()->get())}} exemplar(es) disponível(eis)
                        @else
                        Não possui exemplar disponível
                        @endif
                    </li>
                </ul>
                <div class="card-body">
                    <a href="{{route('obras.show',['obra'=>$obra])}}" class="text-dark btn btn-link" title="Ver"><i
                            class="fa fa-fw fa-eye"></i> Ver</a> @coordenador |
                    <a href="{{route('obras.edit',['obra'=>$obra])}}" class="text-primary btn btn-link"
                        title="Editar"><i class="fa fa-fw fa-edit"></i> Editar</a> |

                    @if($obra->ativo)
                    <form action="{{route('obras.destroy',['obra'=>$obra])}}" class="inline" style="display: inline"
                        method="POST">
                        @method('delete')
                        @csrf
                        <button class="btn btn-link text-danger" title="Desativar obra"><i class="fa fa-fw fa-ban"></i>
                            <span class="pull-left">Desativar</span></button>
                    </form>
                    @else
                    <form action="{{route('obras.ativa',['obra'=>$obra])}}" class="inline" style="display: inline"
                        method="POST">
                        @csrf
                        <button class="btn btn-link text-success" title="Ativar obra"><i
                                class="fa fa-fw fa-check-circle"></i>
                            <span class="pull-left">Ativar</span></button>
                    </form>
                    @endif
                    @endcoordenador
                </div>
            </div>
        </div>
        @if ($loop->iteration % 3 == 0)
    </div>
    <div class="row mt-4">
        @endif
        @endforeach
    </div>
    <div class="mt-4">
        {{ $obras->links() }}
    </div>
    @else
    <p>Nenhum registro encontrado.</p>
    @endif
    <!--/.col-sm-12-->
</div>


@endsection