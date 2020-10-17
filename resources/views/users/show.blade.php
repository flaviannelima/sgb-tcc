@extends('layouts.app')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Lista de usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar usuário</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="fa fa-fw fa-eye"></i><strong>Visualizar usuário</strong></div>

                <div class="card-body">
                    <p><label class="font-weight-bold">Nome:</label> {{$user->name}}</p>
                    <p><label class="font-weight-bold">E-mail:</label> {{$user->email}}</p>
                    <p><label class="font-weight-bold">Data de criação:</label>
                        {{$user->created_at->format('d/m/Y H:i')}}</p>
                    <p><label class="font-weight-bold">Data da última atualização:</label>
                        {{$user->updated_at->format('d/m/Y H:i')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection