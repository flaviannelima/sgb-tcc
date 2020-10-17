<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        <style>@page {
            margin: 0cm 0cm;
        }


        body {
            margin-top: 2cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 2cm;
        }


        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;


        }


        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            font-size: 10px;


        }
    </style>
    </style>
</head>

<body>
    <header>
        <a class="navbar-brand font-weight-bold text-info" href="{{ url('/') }}">
            <img src="https://img.icons8.com/plasticine/2x/books.png" alt="Livros" width="30">SGB
        </a>
        <h1 class="h3 text-center">Usuários</h1>
    </header>
    @if(count($users))
    <table class="table table-sm table-striped table-bordered small text-center align-middle">
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Data de nascimento</th>
                <th>Endereço</th>
                <th>Telefone residencial fixo</th>
                <th>Celular</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->leitor()->first()->cpf}}</td>
                <td>{{$user->leitor()->first()->data_nascimento->format('d/m/Y')}}</td>
                <td>{{$user->leitor()->first()->endereco}}</td>
                <td>{{$user->leitor()->first()->telefone_residencial}}</td>
                <td>{{$user->leitor()->first()->celular}}</td>
                <td>@if($user->leitor()->first()->ativo && $user->ativo)Ativo @else Desativado @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Nenhum registro encontrado</p>
    @endif
    <footer>
        Sistema de Gerenciamento de Biblioteca - Gerado em: {{date('d/m/Y H:i')}} - Usuário: {{auth()->user()->name}}
    </footer>
</body>

</html>