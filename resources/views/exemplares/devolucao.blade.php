@extends('layouts.app')
@section('content')
<div class="container">
    @if ($message = Session::get('success'))

    <div class="alert alert-success alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <strong>{{ $message }}</strong>

    </div>

    @endif
    @if (isset($emprestimo))

    <div class="alert alert-warning alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>
        @php
        $renovacao = $emprestimo->renovacoes()->orderBy('data_prevista_devolucao','desc')->first();
        $dataPrevista = (!isset($renovacao->data_prevista_devolucao)) ? 
        $emprestimo->data_prevista_devolucao : $renovacao->data_prevista_devolucao;
        @endphp
        <strong>Deseja realmente devolver o exemplar: <br>Código de barras: 
            {{$emprestimo->exemplar()->first()->codigo_barras}}<br>Título:
            {{$emprestimo->exemplar()->first()->obra()->first()->titulo}}<br>Leitor:
            {{$emprestimo->leitor()->first()->user()->first()->name}}<br>Data prevista de devolução:{{$dataPrevista->format('d/m/Y')}}?<br>
        </strong>
            <a href="{{route('emprestimos.devolucao',['emprestimo'=>$emprestimo])}}"
                class="mt-2 btn btn-success">Sim</a>
            <button type="button" class="mt-2 btn btn-danger" data-dismiss="alert">Não</button>
        

    </div>

    @endif
    @foreach ($errors->all() as $error)


    <div class="alert alert-danger alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <strong>{{ $error}}</strong>

    </div>

    @endforeach
    <h1>Devolução</h1>


    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-sign-in-alt"></i> <strong>Devolver exemplar</strong>


        </div>
        <div class="card-body">
            <div class="col-sm-12">
                <form method="post" action="{{route('emprestimos.buscaPorCodigoDeBarrasDevolucao')}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group">
                                <label>Código de barras do exemplar</label>
                                <input type="number" step="1" min="0" name="codigo_barras" id="codigo_barras" class="form-control"
                                    placeholder="Digite o código de barras" required>
                            </div>
                        </div>



                        <div class="col-sm-2">
                            <div class="form-group">

                                <div class="mt-4">
                                    <button type="submit" name="submit"  id="submit"
                                        class="btn btn-primary mt-2" title="Devolver"><i class="fas fa-sign-in-alt"></i>
                                        Devolver</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr>


</div>


@endsection