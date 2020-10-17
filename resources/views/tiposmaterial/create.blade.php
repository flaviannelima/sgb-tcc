@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('tiposmaterial.index')}}">Lista de tipos de material</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar tipo de material</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold"><i class="fa fa-fw fa-plus-circle"></i>Cadastrar tipo de material</div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('tiposmaterial.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="descricao" class="col-md-4 col-form-label text-md-right">Descrição<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="descricao" type="text" class="form-control @error('descricao') is-invalid @enderror"
                                    name="descricao" value="{{ old('descricao') }}" required autofocus>

                                @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-fw fa-plus-circle"></i>
                                    Cadastrar
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