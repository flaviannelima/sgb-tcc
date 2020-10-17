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
	<h1>Categorias</h1>
	<div class="d-flex flex-row-reverse">
		<a href="{{route('categorias.create')}}" class="float-right btn btn-success btn-sm ml-2" title="Cadastrar">
			<i class="fa fa-fw fa-plus-circle"></i> Cadastrar
		</a>
		<form action="{{route('categorias.pdf')}}" method="POST">
			@csrf
			@if(isset($request))
			<input type="hidden" name="descricao" value="{{$request->descricao}}" />
			@endif
			<button type="submit" class="float-right btn btn-primary btn-sm" title="Gerar pdf de categorias">
				<i class="fa fa-fw fa-download"></i> Gerar pdf
			</button>
		</form>
		
	</div>
	<div class="card mt-2">
		<div class="card-header">
			<i class="fa fa-fw fa-search"></i> Pesquisar Categoria
			
		</div>
		<div class="card-body">
			<div class="col-sm-12">
				
				<form method="post" action="{{route('categorias.busca')}}">
					@csrf
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">
								<label>Descrição</label>
								<input type="text" name="descricao" id="descricao" class="form-control"
									value=@if(!isset($request))"" @else"{{$request->descricao}}"@endif
									placeholder="Digite a descrição da categoria">
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
    @if(count($categorias))
	<div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr class="bg-primary text-white">
					<th>Descrição</th>
					<th class="text-center">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($categorias as $categoria)
				<tr>
					<td>
						{{$categoria->descricao}}
					</td>
					<td class="text-center">
						<a href="{{route('categorias.show',['categoria'=>$categoria])}}" class="text-dark btn btn-link"
							title="Ver"><i class="fa fa-fw fa-eye"></i> Ver</a> |
						<a href="{{route('categorias.edit',['categoria'=>$categoria])}}" class="text-primary btn btn-link"
							title="Editar"><i class="fa fa-fw fa-edit"></i> Editar</a> |

							<form action="{{route('categorias.destroy',['categoria'=>$categoria])}}" class="inline"
								style="display: inline" method="POST">
								@method('delete')
								@csrf
								<button class="btn btn-link text-danger" title="Excluir categoria"
							onclick="return confirm('Tem certeza que deseja excluir a categoria {{$categoria->descricao}}');"><i
										class="fa fa-fw fa-trash"></i>
									<span class="pull-left">Excluir</span></button>
							</form>
						

					</td>
				</tr>
				@endforeach

			</tbody>
        </table>
        @else 
        <p>Nenhum registro encontrado.</p>
        @endif
		{{ $categorias->links() }}
	</div>
	<!--/.col-sm-12-->
</div>


@endsection