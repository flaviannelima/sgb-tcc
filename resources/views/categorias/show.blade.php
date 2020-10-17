@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('categorias.index')}}">Lista de categorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar categoria</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i><strong>Visualizar categoria</strong></div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Descrição:</label> {{$categoria->descricao}}</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$categoria->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$categoria->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection