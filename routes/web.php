<?php

use App\Leitor;
use App\Multa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\AssignOp\Mul;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm');

//Auth::routes();

Route::middleware(['auth','coordenador'])->group(function(){
    Route::post('autores/pdf','AutorController@pdf')->name('autores.pdf');
    Route::post('autores/{autor}/ativa','AutorController@ativa')->name('autores.ativa');
    Route::resource('autores', 'AutorController')->parameters([
        'autores' => 'autor',
    ]);
    Route::post('/autores/busca','AutorController@busca')->name('autores.busca');
    Route::post('tiposmaterial/pdf','TipoMaterialController@pdf')->name('tiposmaterial.pdf');
    Route::post('/tipos/material/busca','TipoMaterialController@busca')
    ->name('tiposmaterial.busca');
    Route::post('tiposmaterial/{tipoMaterial}/ativo','TipoMaterialController@ativa')->name('tiposmaterial.ativa');
    Route::resource('tiposmaterial', 'TipoMaterialController')->parameters([
        'tiposmaterial' => 'tipomaterial',
    ]);
    Route::post('assuntos/pdf','AssuntoController@pdf')->name('assuntos.pdf');
    Route::post('assuntos/{assunto}/ativa','AssuntoController@ativa')->name('assuntos.ativa');
    Route::resource('assuntos', 'AssuntoController')->parameters([
        'assuntos' => 'assunto',
    ]);
    Route::post('/assuntos/busca','AssuntoController@busca')
    ->name('assuntos.busca');
    Route::post('categorias/pdf','CategoriaController@pdf')->name('categorias.pdf');
    Route::post('categorias/{categoria}/ativa','CategoriaController@ativa')->name('categorias.ativa');
    Route::resource('categorias', 'CategoriaController')->parameters([
        'categorias' => 'categoria',
    ]);
    Route::post('/categorias/busca','CategoriaController@busca')
    ->name('categorias.busca');

    Route::post('editoras/pdf','EditoraController@pdf')->name('editoras.pdf');
    Route::post('/editoras/busca','EditoraController@busca')
    ->name('editoras.busca');
    Route::post('editoras/{editora}/ativa','EditoraController@ativa')->name('editoras.ativa');
    Route::resource('editoras', 'EditoraController')->parameters([
        'editoras' => 'editora',
    ]);
    Route::post('obras/pdf','ObraController@pdf')->name('obras.pdf');
    Route::post('/obras/geraLocalizacao','ObraController@geraLocalizacao')
    ->name('obras.geraLocalizacao');
    Route::post('obras/{obra}/ativa','ObraController@ativa')->name('obras.ativa');

    Route::resource('obras', 'ObraController', ['except' => ['index', 'show']])->parameters([
        'obras' => 'obra',
    ]);
    Route::post('exemplares/{exemplar}/ativa','ExemplarController@ativa')->name('exemplares.ativa');
    Route::resource('exemplares', 'ExemplarController', ['except' => ['index', 'show','create']])->parameters([
        'exemplares' => 'exemplar',
    ]);
    
    Route::get('/exemplares/create/{obra}','ExemplarController@create')->name('exemplares.create');
    Route::post('users/pdf','UserController@pdf')->name('users.pdf');

    Route::post('users/{user}/ativo','UserController@ativa')->name('users.ativa');

    Route::resource('users','UserController',['only' => ['destroy']]);

    Route::post('coordenadores/{coordenador}/ativa','CoordenadorController@ativa')->name('coordenadores.ativa');
    Route::resource('coordenadores', 'CoordenadorController', ['only' => ['store', 'destroy']])
    ->parameters([
        'coordenadores' => 'coordenador',
    ]);


    Route::post('atendentes/{atendente}/ativa','AtendenteController@ativa')->name('atendentes.ativa');
    Route::resource('atendentes', 'AtendenteController', ['only' => ['store', 'destroy']])
    ->parameters([
        'atendentes' => 'atendente',
    ]);


});

Route::middleware(['auth'])->group(function(){
    Route::resource('obras', 'ObraController', ['only' => ['index', 'show']])->parameters([
        'obras' => 'obra',
    ]);
    Route::post('/obras/busca','ObraController@busca')
    ->name('obras.busca');
    Route::resource('exemplares', 'ExemplarController', ['only' => ['show']])->parameters([
        'exemplares' => 'exemplar',
    ]);
    Route::patch('users/alterarsenha','UserController@alterarSenha')->name('users.alterarSenha');
    Route::get('users/alterarsenha',function(){
        return view('users.alterarsenha');
    })->name('users.alterarSenhaFormulario');
    
});
Route::middleware(['auth','atendenteoucoordenador'])->group(function(){
    Route::resource('users','UserController',['except' => ['destroy']]);
    Route::post('/users/busca','UserController@busca')
    ->name('users.busca');
    Route::post('leitores/{leitor}/ativa','LeitorController@ativa')->name('leitores.ativa');

    Route::resource('leitores', 'LeitorController',['except' => ['index','create']])->parameters([
        'leitores' => 'leitor',
    ]);
    Route::get('/leitores/create/{user}','LeitorController@create')->name('leitores.create');
    Route::resource('emprestimos', 'EmprestimoController',['only' => ['store']])->parameters([
        'emprestimos' => 'emprestimo',
    ]);
    Route::get('/devolucao/{emprestimo}','EmprestimoController@devolucao')->name('emprestimos.devolucao');
    Route::get('/devolucao',function(){
        return view('exemplares.devolucao');
    })->name('exemplares.devolucao');
    Route::post('/devolucao','EmprestimoController@buscaPorCodigoDeBarrasDevolucao')
    ->name('emprestimos.buscaPorCodigoDeBarrasDevolucao');
    Route::resource('renovacoes', 'RenovacaoController',['only' => ['store']])->parameters([
        'renovacoes' => 'renovacao',
    ]);
    Route::post('multas/pagar','MultaController@pagar')->name('multas.pagar');
    Route::get('/relatorio','RelatorioController@index')->name('relatorio');
    Route::post('/relatorio/busca','RelatorioController@index')->name('relatorio.busca');
    Route::get('/multa/{multa}',function(Multa $multa){
        return view('leitores.multa',['multa' => $multa]);
    })->name('multa');

});
Route::get('/leitores/{leitor}/historico',function(Leitor $leitor){ 
    return view('leitores.historico',['leitor' => $leitor]);
})->name('leitores.historico')->middleware(['auth','historico']);
Route::get('/leitores/{leitor}/multas',function(Leitor $leitor){
    $multas = Multa::selectRaw('multas.*')->join('emprestimos','emprestimo','emprestimos.id')->where('leitor',$leitor->id)
    ->orderBy('multas.id','desc')->get();
    return view('leitores.multas',['leitor' => $leitor,'multas' => $multas]);
})->name('leitores.multas')->middleware(['auth','historico']);
Auth::routes();


Route::get('/redirect', 'SocialAuthGoogleController@redirect')->name('redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback')->name('callback');