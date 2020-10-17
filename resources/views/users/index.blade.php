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
    <h1>Usuários</h1>
    <div class="d-flex flex-row-reverse">
       
            <a href="{{route('users.create')}}" class="float-right ml-2 btn btn-success btn-sm" title="Cadastrar">
                <i class="fa fa-fw fa-plus-circle"></i> Cadastrar
            </a>
            @coordenador
            <form action="{{route('users.pdf')}}" method="POST">
                @csrf
                @if(isset($request))
                <input type="hidden" name="name" value="{{$request->name}}" />
                <input type="hidden" name="email" value="{{$request->email}}" />
                <input type="hidden" name="cadastro" value="{{$request->cadastro}}" />
                <input type="hidden" name="situacao" value="{{$request->situacao}}" />
                @endif
                <button type="submit" class="float-right btn btn-primary btn-sm" title="Gerar pdf de usuários">
                    <i class="fa fa-fw fa-download"></i> Gerar pdf
                </button>
            </form>
            @endcoordenador
          
            
    
        
    </div>
    <div class="card mt-2">
        <div class="card-header">
            <i class="fa fa-fw fa-search"></i> Pesquisar Usuário

        </div>
        <div class="card-body">
            <div class="col-sm-12">

                <form method="post" action="{{route('users.busca')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value=@if(!isset($request->name))"" @else"{{$request->name}}"@endif
                                placeholder="Digite o nome do usuário">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value=@if(!isset($request->email))"" @else"{{$request->email}}"@endif
                                placeholder="Digite o e-mail do usuário">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Cadastro</label>
                                <select name="cadastro" id="cadastro" class="form-control"
                                    value=@if(!isset($request->cadastro))"" @else"{{$request->cadastro}}"@endif
                                    placeholder="Cadastro que o usuário possui">
                                    <option value=""></option>
                                    <option value="atendente" @if(isset($request->cadastro) && $request->cadastro ==
                                        "atendente")
                                        selected @endif>Atendente</option>
                                    <option value="coordenador" @if(isset($request->cadastro) && $request->cadastro ==
                                        "coordenador")
                                        selected @endif>Coordenador</option>
                                    <option value="leitor" @if(isset($request->cadastro) && $request->cadastro ==
                                        "leitor")
                                        selected @endif>Leitor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Situação</label>
                                <select name="situacao" id="situacao" class="form-control">
                                    <option value="" @if(isset($request) && ($request->situacao=="" || $request->situacao==null)) selected @endif>Todos</option>
                                    <option value="1" @if(isset($request) && $request->situacao) selected @endif>Ativo</option>
                                    <option value="0" @if(isset($request) && $request->situacao==='0') selected @endif>Desativado</option>
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
    @if(count($users))
    <div class="row">
        @foreach($users as $user)
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body bg-primary text-white">
                    <p class="card-title">{{$user->name}}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">E-mail: {{$user->email}}
                    </li>
                    <li class="list-group-item">Cadastro(s):
                        @if(!count($user->atendente()->get()) && !count($user->coordenador()->get()) &&
                        !count($user->leitor()->get())) Não possui
                        @else
                        <ul>
                            @if(count($user->atendente()->get()))
                            <li>Atendente @if(count(auth()->user()->coordenador()->get()))
                                @if($user->atendente()->first()->ativo)
                                <form
                                    action="{{route('atendentes.destroy',['atendente'=>$user->atendente()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-link text-danger" title="Desativar atendente"><i
                                            class="fa fa-fw fa-ban"></i></button>
                                </form>
                                @else
                                <form action="{{route('atendentes.ativa',['atendente'=>$user->atendente()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @csrf
                                    <button class="btn btn-link text-success" title="Ativar atendente"><i
                                            class="fa fa-fw fa-check-circle"></i></button>
                                </form>
                                @endif
                                @endif</li>
                            @endif
                            @if(count($user->coordenador()->get()))
                            <li>Coordenador @if(count(auth()->user()->coordenador()->get()))
                                @if($user->coordenador()->first()->ativo)
                                <form
                                    action="{{route('coordenadores.destroy',['coordenador'=>$user->coordenador()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-link text-danger" title="Desativar coordenador"><i
                                            class="fa fa-fw fa-ban"></i></button>
                                </form>
                                @else
                                <form
                                    action="{{route('coordenadores.ativa',['coordenador'=>$user->coordenador()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @csrf
                                    <button class="btn btn-link text-success" title="Ativar coordenador"><i
                                            class="fa fa-fw fa-check-circle"></i></button>
                                </form>
                                @endif
                                @endif
                            </li>
                            @endif
                            @if(count($user->leitor()->get()))
                            <li>Leitor
                                <a class="btn btn-link text-dark" href="{{route('leitores.show',['leitor' => 
                           $user->leitor()->first()->id])}}" title="Visualizar cadastro de leitor">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-link text-primary" href="{{route('leitores.edit',['leitor' => 
                           $user->leitor()->first()->id])}}" title="Editar cadastro de leitor">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                                @if($user->leitor()->first()->ativo)
                                <form action="{{route('leitores.destroy',['leitor'=>$user->leitor()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-link text-danger" title="Desativar leitor"><i
                                            class="fa fa-fw fa-ban"></i></button>
                                </form>
                                @else
                                <form action="{{route('leitores.ativa',['leitor'=>$user->leitor()->first()])}}"
                                    class="inline" style="display: inline" method="POST">
                                    @csrf
                                    <button class="btn btn-link text-success" title="Ativar leitor"><i
                                            class="fa fa-fw fa-check-circle"></i></button>
                                </form>
                                @endif

                            </li>
                            @endif
                        </ul>
                        @endif
                    </li>
                    @if(!count($user->coordenador()->get()) && count(auth()->user()->coordenador()->get()))
                    <li class="list-group-item">
                        <form action="{{route('coordenadores.store')}}" class="inline" style="display: inline"
                            method="POST">
                            @csrf
                            <input type="hidden" name="user" value="{{$user->id}}">
                            <button class="btn btn-link text-primary" title="Cadastrar coordenador"><i
                                    class="fa fa-fw fa-plus"></i> Cadastrar coordenador
                            </button>
                        </form>
                    </li>
                    @endif
                    @if(!count($user->atendente()->get()) && count(auth()->user()->coordenador()->get()))
                    <li class="list-group-item">
                        <form action="{{route('atendentes.store')}}" class="inline" style="display: inline"
                            method="POST">
                            @csrf
                            <input type="hidden" name="user" value="{{$user->id}}">
                            <button class="btn btn-link text-primary" title="Cadastrar atendente"><i
                                    class="fa fa-fw fa-plus"></i> Cadastrar atendente
                            </button>
                        </form>
                    </li>
                    @endif
                    @if(!count($user->leitor()->get()))
                    <li class="list-group-item">
                        <a class="btn btn-link text-primary" title="Cadastrar leitor"
                            href="{{route('leitores.create',['user'=>$user->id])}}"><i class="fa fa-fw fa-plus"></i>
                            Cadastrar leitor
                        </a>

                    </li>
                    @endif
                </ul>
                <div class="card-body">
                    <a href="{{route('users.show',['user'=>$user])}}" class="text-dark btn btn-link" title="Ver"><i
                            class="fa fa-fw fa-eye"></i> Ver</a> @if(!(count($user->coordenador()->get()) ||
                    count($user->atendente()->get())) || count(auth()->user()->coordenador()->get())) |
                    <a href="{{route('users.edit',['user'=>$user])}}" class="text-primary btn btn-link"
                        title="Editar"><i class="fa fa-fw fa-edit"></i> Editar</a>@coordenador |
                    
                    @if($user->ativo)
                    <form action="{{route('users.destroy',['user'=>$user])}}" class="inline" style="display: inline"
                        method="POST">
                        @method('delete')
                        @csrf
                        <button class="btn btn-link text-danger" title="Desativar usuário"><i
                                class="fa fa-fw fa-ban"></i>
                            <span class="pull-left">Desativar</span></button>
                    </form>
                    @else
                    <form action="{{route('users.ativa',['user'=>$user])}}" class="inline" style="display: inline"
                        method="POST">
                        @csrf
                        <button class="btn btn-link text-success" title="Ativar usuário"><i
                                class="fa fa-fw fa-check-circle"></i>
                            <span class="pull-left">Ativar</span></button>
                    </form>
                    @endif
                    @endcoordenador
                    @endif
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
        {{ $users->links() }}
    </div>
    @else
    <p>Nenhum registro encontrado.</p>
    @endif
    <!--/.col-sm-12-->
</div>


@endsection