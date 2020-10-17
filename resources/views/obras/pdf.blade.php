<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        <style>
            
            @page {
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
        <span class="navbar-brand font-weight-bold text-info" href="{{ url('/') }}">
            <img src="https://img.icons8.com/plasticine/2x/books.png" alt="Livros" width="30">SGB
        </span>
        <h1 class="h3 text-center">Obras</h1>
    </header>
    @if(count($obras))
    <table class="table table-sm table-striped table-bordered small text-center align-middle">
        <thead>
            <tr class="bg-primary text-white">
                <th>Título</th>
                <th>Tipo de material</th>
                <th>Autor(es)</th>
                <th>Assunto(s)</th>
                <th>Categoria</th>
                <th>Volume</th>
                <th>Localização</th>
                <th>Exemplares ativos</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obras as $obra)
            <tr>
                <td>{{$obra->titulo}}</td>
                <td>{{$obra->tipoMaterial()->first()->descricao}}</td>
                <td><ul>@foreach ($obra->autores()->get() as $autor)
                    <li>{{$autor->nome}}</li>
                @endforeach</ul></td>
                <td><ul>@foreach ($obra->assuntos()->get() as $assunto)
                    <li>{{$assunto->descricao}}</li>
                @endforeach</ul></td>
                <td>{{$obra->categoria()->first()->descricao}}</td>
                <td>{{$obra->volume}}</td>
                <td>{{$obra->localizacao}}</td>
                <td>{{count($obra->exemplares()->where('ativo',1)->get())}}</td>
                <td>@if($obra->ativo)Ativa @else Desativada @endif</td>
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