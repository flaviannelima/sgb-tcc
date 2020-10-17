@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Listar usuários</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('leitores.show',
            $multa->emprestimo()->first()->leitor)}}">
                    Visualizar leitor</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('leitores.multas',
            $multa->emprestimo()->first()->leitor)}}">
                Histórico de multas do leitor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pagar multa</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">$ Pagar multa
                </div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('multas.pagar') }}">
                        @csrf
                        <input type="hidden" name="multa" value="{{ $multa->id}}">
                        <div class="form-group row">
                            <label for="valor_multa" class="col-md-4 col-form-label text-md-right">Valor da multa (R$):</label>

                            <div class="col-md-6">
                                <input type="number" name="valor_multa" id="valor_multa" disabled
                                    value="{{$multa->valor_multa}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor_restante" class="col-md-4 col-form-label text-md-right">Valor que falta pagar (R$):</label>

                            <div class="col-md-6">
                                <input type="number" name="valor_restante" id="valor_restante" disabled
                                    value="{{$multa->valor_multa - $multa->valor_pago}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor_pago" class="col-md-4 col-form-label text-md-right">Valor pago (R$)
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input type="number" min="0" max="{{$multa->valor_multa - $multa->valor_pago}}" 
                                name="valor_pago" id="valor_pago" required  step="any"
                                    value="{{old('valor_pago')}}" class="form-control @error('valor_pago') is-invalid  
                            @enderror" @error('valor_pago') autofocus @enderror">

                                @error('valor_pago')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   
                                    $ Pagar
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