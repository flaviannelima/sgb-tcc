@extends('layouts.app')
@section('content')
<div class="container">
   
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Listar usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar leitor</li>
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
    @if ($multasNaoPagas)

    <div class="alert alert-warning alert-block">

        <strong>Leitor possui multa no valor de R$ {{$multasNaoPagas}}</strong> 
       
    </div>

    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-fw fa-eye"></i><strong>Visualizar leitor</strong>
                    <a href="#" class="float-right btn btn-dark btn-sm dropdown-toggle"
                    role="button" 
                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="fa fa-fw fa-list"></i> Histórico</a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{route('leitores.historico',$leitor)}}">Empréstimos</a>
                                <a class="dropdown-item" href="{{route('leitores.multas',$leitor)}}">Multas</a>
               
                            </div>
                </div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Nome:</label> {{$leitor->user()->first()->name}}</p>
                    <p><label class="font-weight-bold">E-mail:</label> {{$leitor->user()->first()->email}}</p>
                    <p><label class="font-weight-bold">CPF:</label> {{$leitor->cpf}}</p>
                    <p><label class="font-weight-bold">Data de nascimento:</label>
                        {{$leitor->data_nascimento->format('d/m/Y')}}</p>
                    <p><label class="font-weight-bold">Endereço:</label> {{$leitor->endereco}}</p>
                    <p><label class="font-weight-bold">Telefone residencial fixo:</label>
                        @if($leitor->telefone_residencial){{$leitor->telefone_residencial}}@else - @endif</p>
                    <p><label class="font-weight-bold">Celular:</label> {{$leitor->celular}}</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$leitor->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$leitor->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i><strong>Emprestar exemplar</strong></div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('emprestimos.store') }}">
                        @csrf
                        <input type="hidden" name="leitor" value="{{$leitor->id }}">
                        <div class="form-group row">
                            <label for="exemplar" class="col-md-4 col-form-label text-md-right">Código de barras do
                                exemplar<span class="text-danger"> *</span>:</label>

                            <div class="col-md-6">
                                <input type="number" name="exemplar" id="exemplar" required value="{{old('exemplar')}}"
                                    class="form-control @error('exemplar') is-invalid  
                                    @enderror" @error('exemplar') autofocus @enderror>
                                @error('exemplar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha do leitor<span class="text-danger"> *</span>:</label>

                            <div class="col-md-6">
                                <input type="password" name="password" id="password" required class="form-control @error('password') is-invalid  
                                    @enderror" @error('password') autofocus @enderror>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">

                                    Emprestar
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        
    </div>
    <div class="row justify-content-center">
        @if(count($emprestimos))
        <div class="table-responsive mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código de barras</th>
                        <th>Título</th>
                        <th>Data do empréstimo</th>
                        <th>Data prevista de devolução</th>
                        <th>Usuário que emprestou</th>
                        <th>Quantidade de renovações</th>
                        <th>Dias de atraso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emprestimos as $emprestimo)
                    <tr>
                        <td>{{$emprestimo->exemplar()->first()->codigo_barras}}</td>
                        <td>{{$emprestimo->exemplar()->first()->obra()->first()->titulo}}</td>
                        <td>{{$emprestimo->created_at->format('d/m/Y')}}</td>
                        <td>@if(!count($emprestimo->renovacoes()->get()))
                            {{$emprestimo->data_prevista_devolucao->format('d/m/Y')}} @else
                            {{$emprestimo->renovacoes()->orderBy('data_prevista_devolucao','desc')->first()
                        ->data_prevista_devolucao->format('d/m/Y')}}@endif</td>
                        <td>{{$emprestimo->usuarioEmprestou()->first()->name}}</td>
                        <td>{{count($emprestimo->renovacoes()->get())}}</td>
                        <td>
                            @php
                            if(!count($emprestimo->renovacoes()->get()) &&
                            strtotime($emprestimo->data_prevista_devolucao) < strtotime(date('Y-m-d'))){
                                $diferenca=  strtotime(date('Y-m-d')) - strtotime($emprestimo->data_prevista_devolucao);
                                $dias = floor($diferenca / (60 * 60 * 24));
                                }
                                else if(count($emprestimo->renovacoes()->get())){
                                if($emprestimo->renovacoes()->orderBy('data_prevista_devolucao','desc')->first()
                                ->data_prevista_devolucao < date('Y-m-d')){ $diferenca=strtotime(date('Y-m-d')) - 
                                strtotime($emprestimo->
                                    renovacoes()->orderBy('data_prevista_devolucao','desc')->first()
                                    ->data_prevista_devolucao);
                                    $dias = floor($diferenca / (60 * 60 * 24));
                                    }
                                    else{
                                    $dias = 0;
                                    }
                                    }
                                    else{
                                    $dias = 0;
                                    }
                                    @endphp
                                    {{$dias}}
                        </td>
                        <td>
                            @php
                               $renovacao = $emprestimo->renovacoes()->orderBy('data_prevista_devolucao','desc')->first();
                               $dataPrevista = (!isset($renovacao->data_prevista_devolucao)) ? 
                               $emprestimo->data_prevista_devolucao : $renovacao->data_prevista_devolucao;
                            @endphp
                            @if($dataPrevista < date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')))
                            <form action="{{route('renovacoes.store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="emprestimo" value="{{$emprestimo->id}}">
                                <button type="submit" class="btn btn-primary">Renovar</button>
                            </form>
                            @endif
                            <a href="{{route('emprestimos.devolucao',['emprestimo'=>$emprestimo])}}"
                                class="mt-2 btn btn-success">Devolver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>Leitor não possui exemplar emprestado</p>
        @endif
    </div>
</div>


@endsection