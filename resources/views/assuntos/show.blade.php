@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('assuntos.index')}}">Lista de assuntos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar assunto</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i><strong>Visualizar assunto</strong></div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Descrição:</label> {{$assunto->descricao}}</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$assunto->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$assunto->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection