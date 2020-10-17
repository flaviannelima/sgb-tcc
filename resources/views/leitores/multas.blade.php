@extends('layouts.app')
@section('content')
<div class="container">

    @coordenadoratendente
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Listar usuários</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('leitores.show',$leitor->id)}}">
                    Visualizar leitor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Histórico de multas do leitor</li>
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
    <h1>Histórico de multas</h1>

    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(count($multas))
            <table class="table table-bordered" id="multas">
                <thead>
                    <tr>
                        <th colspan="6" class="text-center">Histórico de multas do leitor {{$leitor->user()->first()->name}}</th>
                    </tr>
                    <tr>
                        <th>
                            Valor da multa (R$)
                        </th>
                        <th>
                            Valor pago (R$)
                        </th>
                        <th>
                            Valor que falta pagar (R$)
                        </th>
                        <th>
                            Data da multa
                        </th>
                        <th>
                            Data do pagamento da multa
                        </th>
                        @coordenadoratendente
                        <th></th>
                        @endcoordenadoratendente
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($multas as $multa)
                    <tr>
                        <td>{{$multa->valor_multa}}</td>
                        <td>{{$multa->valor_pago}}</td>
                        <td>{{$multa->valor_multa - $multa->valor_pago}}</td>
                        <td>{{$multa->created_at->format('d/m/Y H:i')}}</td>
                        <td>@if($multa->valor_pago){{$multa->updated_at->format('d/m/Y H:i')}}@endif</td>
                        @coordenadoratendente
                        <td>@if($multa->valor_multa>$multa->valor_pago)<a href="{{route('multa',['multa' => $multa->id])}}"
                             class="btn btn-success">Pagar</button>@endif
                        </td>
                        @endcoordenadoratendente
                    </tr>
                   
                   
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

        $('#multas').DataTable({"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
            }});
     });
</script>

@endsection