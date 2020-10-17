@extends('layouts.app')
@section('content')
<div class="container">

    @coordenadoratendente
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Listar usuários</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('leitores.show',$leitor->id)}}">
                    Visualizar leitor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Histórico de empréstimos do leitor</li>
        </ol>
    </nav>
    @endcoordenadoratendente
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
    <h1>Histórico de empréstimos</h1>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(count($leitor->emprestimos()->get()))
            <table class="table table-bordered" id="historico">
                <thead>
                    <tr>
                        <th colspan="6" class="text-center">Histórico de empréstimos do leitor {{$leitor->user()->first()->name}}</th>
                    </tr>
                    <tr>
                        <th>
                            Código de barras
                        </th>
                        <th>
                            Título
                        </th>
                        <th>
                            Tipo
                        </th>
                        <th>
                            Data do ocorrido
                        </th>
                        <th>
                            Data prevista para devolução
                        </th>
                        <th>
                            Atendente responsável
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leitor->emprestimos()->get() as $emprestimo)
                    <tr>
                        <td>{{$emprestimo->exemplar()->first()->codigo_barras}}</td>
                        <td>{{$emprestimo->exemplar()->first()->obra()->first()->titulo}}</td>
                        <td>Empréstimo</td>
                        <td>{{$emprestimo->created_at->format('d/m/Y H:i')}}</td>
                        <td>{{$emprestimo->data_prevista_devolucao->format('d/m/Y')}}</td>
                        <td>{{$emprestimo->usuarioEmprestou()->first()->name}}</td>
                    </tr>
                    @foreach ($emprestimo->renovacoes()->get() as $renovacao)
                    <tr>
                        <td>{{$emprestimo->exemplar()->first()->codigo_barras}}</td>
                        <td>{{$emprestimo->exemplar()->first()->obra()->first()->titulo}}</td>
                        <td>Renovação</td>
                        <td>{{$renovacao->created_at->format('d/m/Y H:i')}}</td>
                        <td>{{$renovacao->data_prevista_devolucao->format('d/m/Y')}}</td>
                        <td>{{$renovacao->usuarioRenovou()->first()->name}}</td>
                    </tr>
                    @endforeach
                    @if($emprestimo->data_devolucao != null)
                    <tr>
                        <td>{{$emprestimo->exemplar()->first()->codigo_barras}}</td>
                        <td>{{$emprestimo->exemplar()->first()->obra()->first()->titulo}}</td>
                        <td>Devolução</td>
                        <td>{{$emprestimo->updated_at->format('d/m/Y H:i')}}</td>
                        <td>{{$emprestimo->data_prevista_devolucao->format('d/m/Y')}}</td>
                        <td>{{$emprestimo->usuarioDevolveu()->first()->name}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @else
            Não há registros
            @endif
        </div>

    </div>

</div>
<script>
    $(document).ready(function () {

        $('#historico').DataTable({"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
            }});
        
     });
</script>

@endsection