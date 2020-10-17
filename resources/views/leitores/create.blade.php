@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Listar usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar leitor</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold"><i class="fa fa-fw fa-plus-circle"></i>Cadastrar leitor
                </div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('leitores.store') }}">
                        @csrf
                        <input type="hidden" name="user" value="{{ Request()->user->id }}">
                        <div class="form-group row">
                            <label for="nome" class="col-md-4 col-form-label text-md-right">Nome:</label>

                            <div class="col-md-6">
                                <input type="text" name="nome" id="nome" disabled
                                    value="{{Request()->user->name}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail:</label>

                            <div class="col-md-6">
                                <input type="text" name="email" id="email" disabled
                                    value="{{Request()->user->email}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cpf" class="col-md-4 col-form-label text-md-right">CPF
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="text" name="cpf" id="cpf" required placeholder="999.999.999-99"
                                    value="{{old('cpf')}}" class="form-control @error('cpf') is-invalid  
                            @enderror" @error('cpf') autofocus @enderror">

                                @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="data_nascimento" class="col-md-4 col-form-label text-md-right">Data de nascimento
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="date" name="data_nascimento" id="data_nascimento" required
                                    value="{{old('data_nascimento')}}" class="form-control @error('data_nascimento') is-invalid  
                            @enderror" @error('data_nascimento') autofocus @enderror">

                                @error('data_nascimento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="endereco" class="col-md-4 col-form-label text-md-right">Endereço
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="text" name="endereco" id="endereco" required
                                    value="{{old('endereco')}}" class="form-control @error('endereco') is-invalid  
                            @enderror" @error('endereco') autofocus @enderror">

                                @error('endereco')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefone_residencial" class="col-md-4 col-form-label text-md-right">
                                Telefone residencial fixo
                            </label>

                            <div class="col-md-6">
                                <input type="text" name="telefone_residencial" id="telefone_residencial" placeholder="(99) 9999-9999"
                                    value="{{old('telefone_residencial')}}" class="form-control @error('telefone_residencial') is-invalid  
                            @enderror" @error('telefone_residencial') autofocus @enderror">

                                @error('telefone_residencial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="celular" class="col-md-4 col-form-label text-md-right">Celular
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="text" name="celular" id="celular" required placeholder="(99) 99999-9999"
                                    value="{{old('celular')}}" class="form-control @error('celular') is-invalid  
                            @enderror" @error('celular') autofocus @enderror">

                                @error('celular')
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
<script>
    $("#cpf").mask('999.999.999-99');
    $("#telefone_residencial").mask('(99) 9999-9999');
    $("#celular").mask('(99) 99999-9999');
</script>

@endsection