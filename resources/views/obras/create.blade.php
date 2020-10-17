@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('obras.index')}}">Lista de obras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar obra</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold"><i class="fa fa-fw fa-plus-circle"></i>Cadastrar obra</div>

                <div class="card-body">
                    <p class="card-title">Campos com <span class="text-danger">*</span> são obrigatórios!</p>
                    <form method="POST" action="{{ route('obras.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="tipo_material" class="col-md-4 col-form-label text-md-right">Tipo de
                                material<span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="tipo_material" data-live-search="true" data-actions-box="true"
                                    class="form-control selectpicker @error('tipo_material') is-invalid  @enderror"
                                    @error('tipo_material') autofocus @enderror name="tipo_material"
                                    value="{{ old('tipo_material') }}" required>
                                    <option value=""></option>
                                    @foreach ($tiposMaterial as $tipo)
                                    <option value="{{$tipo->id}}"
                                        {{ (old("tipo_material") == $tipo->id ? "selected":"") }}>{{$tipo->id}}-{{$tipo->descricao}}
                                    </option>
                                    @endforeach
                                </select>

                                @error('tipo_material')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="categoria" class="col-md-4 col-form-label text-md-right">Categoria
                                <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="categoria" data-live-search="true" data-actions-box="true"
                                    class="form-control selectpicker @error('categoria') is-invalid  @enderror"
                                    @error('categoria') autofocus @enderror name="categoria"
                                    value="{{ old('categoria') }}" required>
                                    <option value=""></option>
                                    @foreach ($categorias as $categoria)
                                    <option value="{{$categoria->id}}"
                                        {{ (old("categoria") == $categoria->id ? "selected":"") }}>{{$categoria->id}}-{{$categoria->descricao}}
                                    </option>
                                    @endforeach
                                </select>

                                @error('categoria')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="titulo" class="col-md-4 col-form-label text-md-right">Título<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="titulo" type="text"
                                    class="form-control @error('titulo') is-invalid  @enderror" name="titulo"
                                    @error('titulo') autofocus @enderror value="{{ old('titulo') }}" required>

                                @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="autores" class="col-md-4 col-form-label text-md-right">Autores<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="autores" data-live-search="true" multiple data-actions-box="true"
                                    @error('autores') autofocus @enderror
                                    class="form-control selectpicker @error('autores') is-invalid  @enderror"
                                    name="autores[]" required>
                                    @foreach ($autores as $autor)
                                    <option value="{{$autor->id}}"
                                        @if(old("autores")){{ (in_array($autor->id, old("autores")) ? "selected":"") }}@endif>
                                        {{$autor->id}}-{{$autor->nome}}</option>
                                    @endforeach
                                </select>

                                @error('autores')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="assuntos" class="col-md-4 col-form-label text-md-right">Assuntos<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="assuntos" multiple data-live-search="true" data-actions-box="true"
                                    class="form-control selectpicker @error('assuntos') is-invalid  @enderror"
                                    name="assuntos[]" required>
                                    @foreach ($assuntos as $assunto)
                                    <option value="{{$assunto->id}}" @error('assuntos') autofocus @enderror
                                        @if(old("assuntos")){{ (in_array($assunto->id, old("assuntos")) ? "selected":"") }}@endif>
                                        {{$assunto->id}}-{{$assunto->descricao}}
                                    </option>
                                    @endforeach
                                </select>

                                @error('assuntos')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="editora" class="col-md-4 col-form-label text-md-right">Editora<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="editora" data-live-search="true" data-actions-box="true" @error('editora')
                                    autofocus @enderror
                                    class="form-control selectpicker @error('editora') is-invalid  @enderror"
                                    name="editora" value="{{ old('editora') }}" required>
                                    <option value=""></option>
                                    @foreach ($editoras as $editora)
                                    <option value="{{$editora->id}}"
                                        {{ (old("editora") == $editora->id ? "selected":"") }}>{{$editora->id}}-{{$editora->nome}}
                                    </option>
                                    @endforeach
                                </select>

                                @error('editora')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="volume" class="col-md-4 col-form-label text-md-right">Volume</label>

                            <div class="col-md-6">
                                <input id="volume" type="number" min="1" step="1" @error('volume') autofocus @enderror
                                    class="form-control @error('volume') is-invalid  @enderror" name="volume"
                                    value="{{ old('volume') }}">

                                @error('volume')
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
                                    name="observacao">{{ old('observacao') }}</textarea>

                                @error('observacao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="localizacao" class="col-md-4 col-form-label text-md-right">Localização<span
                                    class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="localizacao" type="text"
                                    class="form-control @error('localizacao') is-invalid @enderror" name="localizacao"
                                    value="{{ old('localizacao') }}" placeholder="99.99.99 AA AA" @error('localizacao')
                                    autofocus @enderror required>
                                <a href="#" id="geraLocalizacao">Gerar localização</a>

                                @error('localizacao')
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
    $('#localizacao').mask('99.99.99 SS SS');
    $('#geraLocalizacao').click((event) => {
        event.preventDefault();
       
        $.ajax({
        method: "POST",
        url: "{{route('obras.geraLocalizacao')}}",
        data: { titulo: $('#titulo').val(), autores: $('#autores').val(), _token:$('input[name=_token]').val() },
        success: (msg) => {

            $("#localizacao").val(msg);
        },
        error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
        }
        });
    });
    
</script>
@endsection