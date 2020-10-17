@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('obras.index')}}">Listar obras</a></li>
            <li class="breadcrumb-item"><a href="{{route('obras.show',['obra' => $exemplar->obra()->first()])}}">Ver obra 
                {{$exemplar->obra()->first()->titulo}} </a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar exemplar</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i><strong>Visualizar exemplar</strong></div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Código de barras:</label> {{$exemplar->codigo_barras}}</p>
                    <p><label class="font-weight-bold">Edição:</label> @if($exemplar->edicao){{$exemplar->edicao}}@else - @endif</p>
                    <p><label class="font-weight-bold">Ano:</label> @if($exemplar->ano){{$exemplar->ano}}@else - @endif</p>
                    <p><label class="font-weight-bold">Observação:</label> @if($exemplar->observacao){{$exemplar->observacao}}@else - @endif</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$exemplar->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$exemplar->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection