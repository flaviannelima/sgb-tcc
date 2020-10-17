@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<div class="container">
  @foreach ($errors->all() as $error)


  <div class="alert alert-danger alert-block">

      <button type="button" class="close" data-dismiss="alert">×</button>

      <strong>{{ $error}}</strong>

  </div>

  @endforeach
  <h1>Relatório</h1>
  <div class="card mt-2">
    <div class="card-header">
      <i class="fa fa-fw fa-search"></i> Pesquisar Data

    </div>
    <div class="card-body">
      <div class="col-sm-12">

        <form method="post" action="{{route('relatorio.busca')}}">
          @csrf
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Mês/Ano</label>
                <input type="text"  pattern="(0[1-9]|1[012])[\/](19|20)\d\d$" name="mesano" id="mesano" class="form-control" value=@if(!isset($request->mesano))""
                @else"{{$request->mesano}}"@endif
                placeholder="MM/AAAA">
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">

                <div class="mt-4">
                  <button type="submit" name="submit" value="search" id="submit" class="btn btn-primary mt-2"
                    title="Buscar"><i class="fa fa-fw fa-search"></i>
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
  <div class="row">
    <div class="col-md-4">
      <div class="card bg-light" style="max-width: 18rem;">
        <div class="card-body">
          <p class="card-title">Exemplares emprestados atualmente</p>
          <p class="card-text h2">{{$exemplaresEmprestados}}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{$percEmprestados}}%;"
              aria-valuenow="{{$percEmprestados}}" aria-valuemin="0" aria-valuemax="100">{{$percEmprestados}}%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-light" style="max-width: 18rem;">
        <div class="card-body">
          <p class="card-title">Leitores com empréstimo atrasado</p>
          <br>
          <p class="card-text h2">{{$leitoresAtrasados}}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{$percLeitoresAtrasados}}%;"
              aria-valuenow="{{$percLeitoresAtrasados}}" aria-valuemin="0" aria-valuemax="100">
              {{$percLeitoresAtrasados}}%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-light" style="max-width: 18rem;">
        <div class="card-body">
          <p class="card-title">Leitores com multas não pagas</p>
          <br>
          <p class="card-text h2">{{$leitoresMultasNaoPagas}}</p>
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{$percLeitoresMultasNaoPagas}}%;"
              aria-valuenow="{{$percLeitoresMultasNaoPagas}}" aria-valuemin="0" aria-valuemax="100">
              {{$percLeitoresMultasNaoPagas}}%</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row mt-2">

    <div class="col-md-4">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title">Obras</p>
          <div id="chartObras"></div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title">Exemplares</p>
          <div id="chartExemplares"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-12">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title h5">Leitores com empréstimos atrasados</p>
          @if(count($leitoresAtrasadosNomes))
          <table class="table table-bordered" id="leitoresAtrasados">
            <thead>
              <tr>
                <th data-priority="1">Nome</th>
                <th>E-mail</th>
                <th data-priority="2"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($leitoresAtrasadosNomes as $leitor)
              <tr>
                <td>{{$leitor->name}}</td>
                <td>{{$leitor->email}}</td>
                <td><a href="{{route('leitores.show',$leitor->leitor)}}"><i class="fa fa-fw fa-eye"></i> Ver</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
          <p>Não há registros</p>
          @endif
        </div>
      </div>
    </div>

  </div>
  <div class="row mt-2">
    <div class="col-md-4">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title">Multas pagas</p>
          <div id="chartMultasPagas"></div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title">Movimentações</p>
          <div id="chartMovimentacoes"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-2">

    <div class="col-md-12">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title h5">Leitores com multas não pagas</p>
          @if(count($leitoresMultasNaoPagasNomes))
          <table class="table table-bordered" id="leitoresMultas">
            <thead>
              <tr>
                <th data-priority="1">Nome</th>
                <th>E-mail</th>
                <th data-priority="2"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($leitoresMultasNaoPagasNomes as $leitor)
              <tr>
                <td>{{$leitor->name}}</td>
                <td>{{$leitor->email}}</td>
                <td><a href="{{route('leitores.show',$leitor->leitor)}}"><i class="fa fa-fw fa-eye"></i> Ver</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
          <p>Não há registros</p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-12">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title">Usuários</p>
          <div id="chartUsuarios"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-2">

    <div class="col-md-12">
      <div class="card bg-light">
        <div class="card-body">
          <p class="card-title h5">Obras mais emprestadas</p>
          @if(count($maisEmprestados))
          <table class="table table-bordered display responsive nowrap" id="maisEmprestados">
            <thead>
              <tr>
                
                <th data-priority="1">Título</th>
                <th>Quantidade de empréstimos</th>
                <th data-priority="2"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($maisEmprestados as $me)
              <tr>
                
                <td>{{$me->titulo}}</td>
                <td>{{$me->quantidade}}</td>
                <td><a href="{{route('obras.show',$me->id)}}"><i class="fa fa-fw fa-eye"></i> Ver</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
          <p>Não há registros</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .apexcharts-menu-item.exportCSV {
    display: none;
  }
  td{
       word-wrap:break-word;!important
    }
</style>
<script>
  $("#mesano").mask('99/9999');
  $('#leitoresAtrasados').DataTable({"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json",
            },
            responsive: true,
         
            dom: 'Bfrtip',
            buttons: [

                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1]
                    }
                },
          
            ]
          });
            $('#leitoresMultas').DataTable({"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json",
              },
              responsive: true,
              
              dom: 'Bfrtip',
              buttons: [

                  {
                      extend: 'excelHtml5',
                      exportOptions: {
                          columns: [ 0, 1]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                          columns: [ 0, 1]
                      }
                  },
          
            ]});
            $('#maisEmprestados').DataTable({"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json",
              },
              responsive: true,
              
              "ordering": false,
              dom: 'Bfrtip',
              buttons: [

                  {
                      extend: 'excelHtml5',
                      exportOptions: {
                          columns: [ 0, 1]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                          columns: [ 0, 1]
                      }
                  },
          
            ]});
        var options = {
                  series: [{
                    name: "Quantidade",
                    data: [{{$obrasMes}},{{$obrasMesesAnteriores}},{{$obras}}]
                  }],
                  chart: {
                  height: 350,
                  type: 'bar',
                  events: {
                    click: function(chart, w, e) {
                      // console.log(chart, w, e)
                    }
                  }
                },
              
                plotOptions: {
                  bar: {
                    columnWidth: '45%',
                    distributed: true
                  }
                },
                dataLabels: {
                  enabled: false
                },
                legend: {
                  show: false
                },
                xaxis: {
                  categories: [
                    ['Cadastradas', 'no mês'],
                    ['Cadastradas','nos meses', 'anteriores'],
                    ['Total']
                  ],
                  labels: {
                    style: {
                  
                      fontSize: '12px'
                    }
                  }
                },
               
                };

                var chartObras = new ApexCharts(document.querySelector("#chartObras"), options);
                chartObras.render();

                var options = {
                  series: [{
                    name: "Quantidade",
                    data: [{{$exemplaresTotal-$exemplaresEmprestados}},{{$exemplaresMes}},{{$exemplares-$exemplaresMes}},{{$exemplaresTotal}}]
                  }],
                  chart: {
                  height: 350,
                  type: 'bar',
                  events: {
                    click: function(chart, w, e) {
                      // console.log(chart, w, e)
                    }
                  }
                },
              
                plotOptions: {
                  bar: {
                    columnWidth: '45%',
                    distributed: true
                  }
                },
                dataLabels: {
                  enabled: false
                },
                legend: {
                  show: false
                },
                xaxis: {
                  categories: [
                    ['Disponíveis', 'atualmente'],
                    ['Cadastrados', 'no mês'],
                    ['Cadastrados', 'nos meses','anteriores'],
                    ['Total']
                
                  ],
                  labels: {
                    style: {
                  
                      fontSize: '12px'
                    }
                  }
                }
                };

                var chartExemplares = new ApexCharts(document.querySelector("#chartExemplares"), options);
                chartExemplares.render();
              
                var options = {
                  series: [{
                    name: 'No mês',
                    type: 'column',
                    data: [{{$emprestimosMes}}, {{$renovacoesMes}}, {{$devolucoesMes}}]
                  },  {
                    name: 'Nos meses anteriores',
                    type: 'line',
                    data: [{{$emprestimosMesesAnteriores}}, {{$renovacoesMesesAnteriores}}, {{$devolucoesMesesAnteriores}}]
                  },{
                    name: 'Total',
                    type: 'area',
                    data: [{{$emprestimos}}, {{$renovacoes}}, {{$devolucoes}}]
                  }],
                    chart: {
                    height: 350,
                    type: 'line',
                    stacked: false,
                  },
                  stroke: {
                    width: [0, 5, 2],
                    curve: 'smooth'
                  },
                  plotOptions: {
                    bar: {
                      columnWidth: '50%'
                    }
                  },
                    fill: {
                    opacity: [0.85, 1, 0.25],
                    gradient: {
                      inverseColors: false,
                      shade: 'light',
                      type: "vertical",
                      opacityFrom: 0.85,
                      opacityTo: 0.55,
                      stops: [0, 100, 100, 100]
                    }
                  },
                  labels: ['Empréstimos', 'Renovações', 'Devoluções'
                  ],
                  markers: {
                    size: 0
                  },
                  yaxis: {
                    min: 0
                  },
                  tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                      formatter: function (y) {
                        if (typeof y !== "undefined") {
                          return y.toFixed(0);
                        }
                        return y;
                  
                 }
                  }
                  }
                };

                var chartMovimentacoes = new ApexCharts(document.querySelector("#chartMovimentacoes"), options);
                chartMovimentacoes.render();

                var options = {
                  series: [{
                    name: "Reais",
                    data: [{{$multasPagasMes}},{{$multasPagasMesesAnteriores}},{{$multasPagas}}]
                  }],
                  chart: {
                  height: 350,
                  type: 'bar',
                  events: {
                    click: function(chart, w, e) {
                      // console.log(chart, w, e)
                    }
                  }
                },
              
                plotOptions: {
                  bar: {
                    columnWidth: '45%',
                    distributed: true
                  }
                },
                dataLabels: {
                  enabled: false
                },
                legend: {
                  show: false
                },
                xaxis: {
                  categories: [
                    ['No mês'],
                    ['Nos meses','anteriores'],
                    ['Total']
                
                  ],
                  labels: {
                    style: {
                  
                      fontSize: '12px'
                    }
                  }
                }
                };
                var chartMultasPagas = new ApexCharts(document.querySelector("#chartMultasPagas"), options);
                chartMultasPagas.render();


                var options = {
                  series: [{
                    name: 'No mês',
                    type: 'column',
                    data: [{{$leitoresMes}}, {{$atendentesMes}}, {{$coordenadoresMes}}, {{$usersMes}}]
                  },  {
                    name: 'Nos meses anteriores',
                    type: 'line',
                    data: [{{$leitoresMesesAnteriores}}, {{$atendentesMesesAnteriores}}, {{$coordenadoresMesesAnteriores}},{{$usersMesesAnteriores}}]
                  },{
                    name: 'Total',
                    type: 'area',
                    data: [{{$leitores}}, {{$atendentes}}, {{$coordenadores}},{{$users}}]
                  }],
                    chart: {
                    height: 350,
                    type: 'line',
                    stacked: false,
                  },
                  stroke: {
                    width: [0, 5, 2],
                    curve: 'smooth'
                  },
                  plotOptions: {
                    bar: {
                      columnWidth: '50%'
                    }
                  },
                    fill: {
                    opacity: [0.85, 1, 0.25],
                    gradient: {
                      inverseColors: false,
                      shade: 'light',
                      type: "vertical",
                      opacityFrom: 0.85,
                      opacityTo: 0.55,
                      stops: [0, 100, 100, 100]
                    }
                  },
                  labels: ['Leitores', 'Atendentes', 'Coordenadores', 'Usuários'
                  ],
                  markers: {
                    size: 0
                  },
                  yaxis: {
                    min: 0
                  },
                  tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                      formatter: function (y) {
                        if (typeof y !== "undefined") {
                          return y.toFixed(0);
                        }
                        return y;
                  
                 }
                  }
                  }
                };

                var chartUsuarios = new ApexCharts(document.querySelector("#chartUsuarios"), options);
                chartUsuarios.render();
      
</script>
</div>
@endsection