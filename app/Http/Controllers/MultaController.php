<?php

namespace App\Http\Controllers;

use App\Leitor;
use App\Multa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultaController extends Controller
{
    public function pagar(Request $request){
        if(!$request->valor_pago){
            return redirect()->back()->withErrors(['valor_pago' => 'Campo valor pago obrigatório']);
        }
        $multa = Multa::findOrFail($request->multa);
        if($request->valor_pago+$multa->valor_pago > $multa->valor_multa){
            return redirect()->back()->withErrors(['valor_pago' => 'Valor pago não pode ser maior que o valor da multa']);
        }
        $multa->valor_pago += $request->valor_pago;
        $multa->save();
        return redirect()->route('leitores.multas',$multa->emprestimo()->first()->leitor)->with('success','Multa paga com sucesso!');
    }
}
