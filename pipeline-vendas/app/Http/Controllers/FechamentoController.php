<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fechamento;

class FechamentoController extends Controller
{
    public function show(Request $request) {
       
        $fechamento = Fechamento::all();
        // $situacao = SituacaoController::find(1);

        // $pipeline_lst = $pipeline;
        
        // foreach($fechamento as $result => $value) {       
        //     $situacao = SituacaoController::find($value->ID_SITUACAO);
        //     $value->ID_SITUACAO = $situacao;
            
        //     $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
        //     $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
        //     $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

        //     $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
        //     $value->DT_INICIO = date("d-m-Y", strtotime($value->DT_INICIO));

        //     $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
        //     $value->DT_ABERTURA = date("d-m-Y", strtotime($value->DT_ABERTURA));

        //     if(isset($value->DT_ENCERR)) {                
        //         $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
        //         $value->DT_ENCERR = date("d-m-Y", strtotime($value->DT_ENCERR));
        //     }
        // }        
        // if($uri == 'create') {            
        //     return view('pipeline')->with('pipeline', $pipeline)
        //     ->with('pipeline_lst', $pipeline_lst)
        //     ->with('situacoes_lst', $situacoes_lst)
        //     ->with('op', $uri);
        // }if($uri == 'update') {            
        //     return view('pipeline')->with('pipeline', $pipeline)
        //     ->with('pipeline_lst', $pipeline_lst)
        //     ->with('situacoes_lst', $situacoes_lst)
        //     ->with('op', $uri);
        // }else {
        //     return view('pipeline')->with('pipeline', $pipeline)
        //     ->with('pipeline_lst', $pipeline_lst);
        // }            
        return $fechamento;
    }
    public function create(Request $request) {
        
    }
    public function update(Request $request) {
        
    }        
}
