@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('obras.index')}}">Listar obras</a></li>
            <li class="breadcrumb-item"><a href="{{route('obras.show',['obra' => $exemplar->obra()->first()])}}">Ver obra 
                {{$exemplar->obra()->first()->titulo}} </a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar exemplar</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold"><i class="fa fa-fw fa-edit"></i>Editar exemplar</div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('exemplares.update',['exemplar'=>$exemplar]) }}">
                        @method('patch')
                        @csrf

                        <input type="hidden" name="obra" value="{{ $exemplar->obra()->first()->id }}">
                        <div class="form-group row">
                            <label for="codigo_barras" class="col-md-4 col-form-label text-md-right">Código de barras
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="number" name="codigo_barras" id="codigo_barras" min="0" step="1" required
                                    value="{{old('codigo_barras',$exemplar->codigo_barras)}}" class="form-control @error('codigo_barras') is-invalid  
                            @enderror" @error('codigo_barras') autofocus @enderror">

                                @error('codigo_barras')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="edicao" class="col-md-4 col-form-label text-md-right">Edição</label>

                            <div class="col-md-6">
                                <input type="number" name="edicao" id="edicao" min="0" step="1"
                                value="{{old('edicao',$exemplar->edicao)}}" class="form-control @error('edicao') is-invalid  
                        @enderror" @error('edicao') autofocus @enderror">

                                @error('edicao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ano" class="col-md-4 col-form-label text-md-right">Ano</label>

                            <div class="col-md-6">
                                <input type="number" name="ano" id="ano" min="0" step="1"
                                    value="{{old('ano',$exemplar->ano)}}" class="form-control @error('ano') is-invalid  
                            @enderror" @error('ano') autofocus @enderror">

                                @error('ano')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="observacao" class="col-md-4 col-form-label text-md-right">Observação</label>

                            <div class="col-md-6">
                                <textarea id="observacao" type="text" @error('observacao') autofocus @enderror
                                    class="form-control @error('observacao') is-invalid  @enderror"
                                    name="observacao">{{ old('observacao',$exemplar->observacao) }}</textarea>

                                @error('observacao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-fw fa-edit"></i>
                                    Editar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection