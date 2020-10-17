@extends('layouts.app')
@section('content')
<div class="container">
	@if ($message = Session::get('success'))

	<div class="alert alert-success alert-block">

		<button type="button" class="close" data-dismiss="alert">×</button>

		<strong>{{ $message }}</strong>

	</div>

	@endif

	@foreach ($errors->all() as $error)


    <div class="alert alert-danger alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <strong>{{ $error}}</strong>

    </div>

	@endforeach
	<h1>Autores</h1>
	<div class="d-flex flex-row-reverse">
		<a href="{{route('autores.create')}}" class="float-right btn btn-success btn-sm ml-2" title="Cadastrar">
			<i class="fa fa-fw fa-plus-circle"></i> Cadastrar
		</a>
		<form action="{{route('autores.pdf')}}" method="POST">
			@csrf
			@if(isset($request))
			<input type="hidden" name="nome" value="{{$request->nome}}" />
			@endif
			<button type="submit" class="float-right btn btn-primary btn-sm" title="Gerar pdf de autores">
				<i class="fa fa-fw fa-download"></i> Gerar pdf
			</button>
		</form>

		
	</div>
	<div class="card mt-2">
		<div class="card-header">
			<i class="fa fa-fw fa-search"></i> Pesquisar Autor
		
			
		</div>
		<div class="card-body">
			<div class="col-sm-12">
				
				<form method="post" action="{{route('autores.busca')}}">
					@csrf
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">
								<label>Nome</label>
								<input type="text" name="nome" id="nome" class="form-control"
									value=@if(!isset($request))"" @else"{{$request->nome}}"@endif
									placeholder="Digite o nome do autor">
							</div>
						</div>

						<div class="col-sm-2">
							<div class="form-group">

								<div class="mt-4">
									<button type="submit" name="submit" value="search" id="submit"
										class="btn btn-primary mt-2" title="Buscar"><i class="fa fa-fw fa-search"></i>
										Buscar</button>

								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<hr>
	@if(count($autores))
	<div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr class="bg-primary text-white">
					<th>Nome</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($autores as $autor)
				<tr>
					<td>
						{{$autor->nome}}
					</td>
					<td class="text-center">
						<a href="{{route('autores.show',['autor'=>$autor])}}" class="text-dark btn btn-link"
							title="Ver"><i class="fa fa-fw fa-eye"></i> Ver</a> |
						<a href="{{route('autores.edit',['autor'=>$autor])}}" class="text-primary btn btn-link"
							title="Editar"><i class="fa fa-fw fa-edit"></i> Editar</a> |
	
						<form action="{{route('autores.destroy',['autor'=>$autor])}}" class="inline"
							style="display: inline" method="POST">
							@method('delete')
							@csrf
							<button class="btn btn-link text-danger" title="Excluir autor" 
							onclick="return confirm('Tem certeza que deseja excluir o autor {{$autor->nome}}?');"><i
									class="fa fa-fw fa-trash"></i>
								<span class="pull-left">Excluir</span></button>
						</form>

					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
		{{ $autores->links() }}
	</div>
	@else 
	<p>Nenhum registro encontrado.</p>
	@endif
	<!--/.col-sm-12-->
</div>


@endsection