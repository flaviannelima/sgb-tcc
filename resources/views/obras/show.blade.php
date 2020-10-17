@extends('layouts.app')
@section('content')
<div class="container">
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('obras.index')}}">Lista de obras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar obra</li>
        </ol>
    </nav>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i>
                    <strong>Visualizar obra</strong>
                    @coordenador
                    <a href="{{route('exemplares.create',['obra' => $obra])}}"
                        class="float-right btn btn-success btn-sm" title="Adicionar exemplar">
                        <i class="fa fa-fw fa-plus-circle"></i> Adicionar exemplar
                    </a>
                    @endcoordenador
                </div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Tipo de material:</label>
                        {{$obra->tipoMaterial()->first()->descricao}}</p>
                    <p><label class="font-weight-bold">Categoria:</label>
                        {{$obra->categoria()->first()->descricao}}</p>
                    <p><label class="font-weight-bold">Título:</label> {{$obra->titulo}}</p>
                    <p><label class="font-weight-bold">Autor(es):</label></p>
                    <ul>
                        @foreach ($obra->autores()->get() as $autor)
                        <li>{{$autor->nome}}</li>
                        @endforeach

                    </ul>
                    <p><label class="font-weight-bold">Assunto(s):</label></p>
                    <ul>
                        @foreach ($obra->assuntos()->get() as $assunto)
                        <li>{{$assunto->descricao}}</li>
                        @endforeach

                    </ul>
                    <p><label class="font-weight-bold">Editora:</label> {{$obra->editora()->first()->nome}}</p>
                    <p><label class="font-weight-bold">Volume:</label>@if($obra->volume) {{$obra->volume}}@else - @endif
                    </p>
                    <p><label class="font-weight-bold">Observação:</label>@if($obra->observacao)
                        {{$obra->observacao}}@else - @endif</p>
                    <p><label class="font-weight-bold">Localização:</label> {{$obra->localizacao}}</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$obra->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$obra->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(count($obra->exemplares()->get()))

            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="bg-primary text-white text-center">
                        <th colspan="6">Exemplares</th>

                    </tr>
                    <tr class="bg-primary text-white">
                        <th>Código de barras</th>
                        <th>Situação</th>
                        <th>Edição</th>
                        <th>Ano</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obra->exemplares()->get() as $exemplar)
                    <tr>
                        <td>
                            {{$exemplar->codigo_barras}}
                        </td>
                        <td>
                            @if(count($exemplar->emprestimos()->whereNull('data_devolucao')->get()))<span
                                class='text-danger'>
                                Emprestado</span>
                            @elseif(!$exemplar->obra()->first()->ativo)<span class='text-danger'> Obra desativada</span>
                            @elseif(!$exemplar->ativo) <span class='text-danger'>Desativado</span>

                            @else <span class='text-success'>Disponível</span>
                            @endif
                        </td>
                        <td>
                            {{$exemplar->edicao}}
                        </td>
                        <td>
                            {{$exemplar->ano}}
                        </td>
                        <td class="text-center">
                            <a href="{{route('exemplares.show',['exemplar'=>$exemplar])}}"
                                class="text-dark btn btn-link" title="Ver"><i class="fa fa-fw fa-eye"></i>
                                Ver</a>@coordenador |

                            <a href="{{route('exemplares.edit',['exemplar'=>$exemplar])}}"
                                class="text-primary btn btn-link" title="Editar"><i class="fa fa-fw fa-edit"></i>
                                Editar</a> |

                            @if($exemplar->ativo)
                            <form action="{{route('exemplares.destroy',['exemplar'=>$exemplar])}}" class="inline"
                                style="display: inline" method="POST">
                                @method('delete')
                                @csrf
                                <button class="btn btn-link text-danger" title="Desativar exemplar"><i
                                        class="fa fa-fw fa-ban"></i>
                                    <span class="pull-left">Desativar</span></button>
                            </form>
                            @else
                            <form action="{{route('exemplares.ativa',['exemplar'=>$exemplar])}}" class="inline"
                                style="display: inline" method="POST">
                                @csrf
                                <button class="btn btn-link text-success" title="Ativar exemplar"><i
                                        class="fa fa-fw fa-check-circle"></i>
                                    <span class="pull-left">Ativar</span></button>
                            </form>
                            @endif
                            @endcoordenador
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            @else
            <p>Esta obra não possui exemplares.</p>
            @endif
        </div>
    </div>
</div>


@endsection