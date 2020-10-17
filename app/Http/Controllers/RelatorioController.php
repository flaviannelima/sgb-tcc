<?php

namespace App\Http\Controllers;

use App\Atendente;
use App\Coordenador;
use App\Emprestimo;
use App\Exemplar;
use App\Http\Requests\Relatorio\BuscaRequest;
use App\Leitor;
use App\Multa;
use App\Obra;
use App\Renovacao;
use App\User;
use DateTime;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index(BuscaRequest $request){
        $mes = (!$request->mesano) ? date('Y-m'): DateTime::createFromFormat('m/Y', $request->mesano)->format('Y-m');
        if($mes> date('Y-m')){
            return redirect()->back()->withErrors(['msg' => 'Mês/Ano deve ser menor ou igual ou Mês/Ano atual']);
        }
        $exemplaresTotal = Exemplar::where('ativo',true)->count();
        $exemplares = Exemplar::where('ativo',true)->whereRaw(' DATE_FORMAT(created_at, "%Y-%m") <= ?',[$mes])->count();
        $exemplaresEmprestados = Emprestimo::whereNull('data_devolucao')->count();
        $percEmprestados = ($exemplaresTotal)?round($exemplaresEmprestados/$exemplaresTotal*100):0;
        $leitores = Leitor::where('ativo',true)->count();
        $leitoresAtrasados = count(Emprestimo::selectRaw('DISTINCT leitor')->whereNull('data_devolucao')
        ->whereRaw('emprestimos.data_prevista_devolucao<? AND (SELECT renovacoes.data_prevista_devolucao FROM renovacoes WHERE emprestimos.id=
        emprestimo AND renovacoes.data_prevista_devolucao >=? LIMIT 1) IS NULL',[date('Y-m-d'),date('Y-m-d')])->get());
    
        $percLeitoresAtrasados = ($leitores)?round($leitoresAtrasados/$leitores*100):0;
        $leitoresMultasNaoPagas = count(Multa::whereRaw('valor_pago<valor_multa')->selectRaw('DISTINCT leitor')
        ->join('emprestimos','emprestimo','emprestimos.id')->get());
        $percLeitoresMultasNaoPagas = ($leitores)?round($leitoresMultasNaoPagas/$leitores*100):0;
        
        $obrasMesesAnteriores = Obra::where('ativo',true)->whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $obras = Obra::where('ativo',true)->count();
        $obrasMes = Obra::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        $exemplaresMes = Exemplar::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        $leitoresAtrasadosNomes = Emprestimo::selectRaw('DISTINCT leitor,name,email')->whereNull('data_devolucao')
        ->whereRaw('emprestimos.data_prevista_devolucao<? AND (SELECT renovacoes.data_prevista_devolucao FROM renovacoes WHERE emprestimos.id=
        emprestimo AND renovacoes.data_prevista_devolucao >=? LIMIT 1) IS NULL',[date('Y-m-d'),date('Y-m-d')])
        ->join('leitores','leitores.id','leitor')
        ->join('users','users.id','user')->get();
        $leitoresMultasNaoPagasNomes = Multa::whereRaw('valor_pago<valor_multa')->selectRaw('DISTINCT leitor,name,email')
        ->join('emprestimos','emprestimo','emprestimos.id')->join('leitores','leitores.id','leitor')
        ->join('users','users.id','user')->get();
        $multasPagasMesesAnteriores = Multa::whereRaw(' DATE_FORMAT(updated_at, "%Y-%m") < ?',[$mes])->sum('valor_pago');
        $multasPagas = Multa::sum('valor_pago');
        $multasPagasMes = Multa::where('updated_at','LIKE',$mes.'%')->sum('valor_pago');
        $emprestimosMesesAnteriores = Emprestimo::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $emprestimos = Emprestimo::count();
        $emprestimosMes = Emprestimo::where('created_at','LIKE',$mes.'%')->count();
        $devolucoesMesesAnteriores = Emprestimo::whereRaw(' DATE_FORMAT(data_devolucao, "%Y-%m") < ?',[$mes])->count();
        $devolucoes = Emprestimo::whereNotNull('data_devolucao')->count();
        $devolucoesMes = Emprestimo::where('data_devolucao','LIKE',$mes.'%')->count();
        $renovacoesMesesAnteriores = Renovacao::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $renovacoes = Renovacao::count();
        $renovacoesMes = Renovacao::where('created_at','LIKE',$mes.'%')->count();
        $leitoresMesesAnteriores = Leitor::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $leitoresMes = Leitor::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        $atendentesMesesAnteriores = Atendente::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $atendentes = Atendente::where('ativo',true)->count();
        $atendentesMes = Atendente::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        $coordenadoresMesesAnteriores = Coordenador::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $coordenadores = Coordenador::where('ativo',true)->count();
        $coordenadoresMes = Coordenador::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        $usersMesesAnteriores = User::whereRaw(' DATE_FORMAT(created_at, "%Y-%m") < ?',[$mes])->count();
        $users = User::where('ativo',true)->count();
        $usersMes = User::where('ativo',true)->where('created_at','LIKE',$mes.'%')->count();
        if(!$request->mesano) $request->mesano=date('m/Y');
        $maisEmprestados = Emprestimo::selectRaw('COUNT(emprestimos.id) as quantidade, obras.titulo,obras.id')
        ->join('exemplares','exemplares.id','emprestimos.exemplar')->join('obras','obras.id','exemplares.obra')->groupBy('obras.id','obras.titulo')
        ->orderBy('quantidade','DESC')->limit(10)->get();
        return view('relatorio',["exemplares" => $exemplares,"exemplaresEmprestados" => $exemplaresEmprestados
        ,"percEmprestados" => $percEmprestados,"leitores" => $leitores,"leitoresAtrasados" => $leitoresAtrasados,
        "percLeitoresAtrasados" => $percLeitoresAtrasados,"leitoresMultasNaoPagas" => $leitoresMultasNaoPagas,
        "percLeitoresMultasNaoPagas" => $percLeitoresMultasNaoPagas,"obras" => $obras,"obrasMes" => $obrasMes
        ,"exemplaresMes" => $exemplaresMes,"leitoresAtrasadosNomes" => $leitoresAtrasadosNomes,'maisEmprestados' => $maisEmprestados
        ,"leitoresMultasNaoPagasNomes" => $leitoresMultasNaoPagasNomes,"multasPagas" => $multasPagas, 
        'obrasMesesAnteriores' => $obrasMesesAnteriores, 'multasPagasMesesAnteriores' => $multasPagasMesesAnteriores
        , 'emprestimosMesesAnteriores' => $emprestimosMesesAnteriores, 'devolucoesMesesAnteriores' => $devolucoesMesesAnteriores
        , 'renovacoesMesesAnteriores' => $renovacoesMesesAnteriores, 'leitoresMesesAnteriores' => $leitoresMesesAnteriores
        , 'atendentesMesesAnteriores' => $atendentesMesesAnteriores, 'coordenadoresMesesAnteriores' => $coordenadoresMesesAnteriores
        , 'usersMesesAnteriores' => $usersMesesAnteriores
        ,"multasPagasMes" => $multasPagasMes,"emprestimos" => $emprestimos,"emprestimosMes" => $emprestimosMes
        ,"devolucoes" => $devolucoes,"devolucoesMes" => $devolucoesMes,"renovacoes" => $renovacoes
        ,"renovacoesMes" => $renovacoesMes,"leitoresMes" => $leitoresMes,"atendentes" => $atendentes
        ,"atendentesMes" => $atendentesMes,"coordenadores" => $coordenadores,"coordenadoresMes" => $coordenadoresMes
        ,"users" => $users,"usersMes" => $usersMes,'request'=>$request, 'exemplaresTotal' => $exemplaresTotal]);
    }
}
