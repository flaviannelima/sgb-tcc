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
        <h1 class="text-center">Coordenadores</h1>
    </header>
    @if(count($coordenadores))
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th>Nome</th>
                <th>E-mail</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coordenadores as $coordenador)
            <tr>
                <td>{{$coordenador->user()->first()->name}}</td>
                <td>{{$coordenador->user()->first()->email}}</td>
                <td>@if($coordenador->user()->first()->ativo && $coordenador->ativo)Ativo @else Desativado @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Nenhum registro encontrado</p>
    @endif
    <footer>
        Sistema de Gestão de Biblioteca - Gerado em: {{date('d/m/Y H:i')}} - Usuário: {{auth()->user()->name}}
    </footer>
</body>

</html>