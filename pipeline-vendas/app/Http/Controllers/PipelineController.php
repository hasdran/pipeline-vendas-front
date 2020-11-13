<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pipeline;
use App\Http\Controllers\SituacaoController;

class PipelineController extends Controller {
    public function show(Request $request ) {   
        if(isset($request)) {              
            $uri = $request->path();
        }else {
            $uri = "";
        }
        
        $pipeline = Pipeline::all();
        $situacao = SituacaoController::find(1);
        $situacoes_lst = SituacaoController::getAll();

        $pipeline_lst = $pipeline;
        
        foreach($pipeline as $result => $value) {       
            $situacao = SituacaoController::find($value->ID_SITUACAO);
            $value->ID_SITUACAO = $situacao;
            
            $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
            $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
            $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

            $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
            $value->DT_INICIO = date("d-m-Y", strtotime($value->DT_INICIO));

            $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
            $value->DT_ABERTURA = date("d-m-Y", strtotime($value->DT_ABERTURA));

            if(isset($value->DT_ENCERR)) {                
                $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
                $value->DT_ENCERR = date("d-m-Y", strtotime($value->DT_ENCERR));
            }
        }        
        if($uri == 'create') {            
            return view('pipeline')->with('pipeline', $pipeline)
            ->with('pipeline_lst', $pipeline_lst)
            ->with('situacoes_lst', $situacoes_lst)
            ->with('op', $uri);
        }if($uri == 'update') {            
            return view('pipeline')->with('pipeline', $pipeline)
            ->with('pipeline_lst', $pipeline_lst)
            ->with('situacoes_lst', $situacoes_lst)
            ->with('op', $uri);
        }else {
            return view('pipeline')->with('pipeline', $pipeline)
            ->with('pipeline_lst', $pipeline_lst);
        }    
    }

    public function create(Request $request) {
        $pipeline = new Pipeline();
        if(isset($request->cliente)) {            
            $pipeline->CLIENTE = $request->cliente;
        }
        if(isset($request->projeto)) {            
            $pipeline->PROJETO = $request->projeto;
        }
        if(isset($request->valor_m3)) {            
            $pipeline->VALOR = $request->valor_m3;
        }        
        if(isset($request->volume_m3)) {            
            $pipeline->VOLUME = $request->volume_m3;
        }    
        if(isset($request->dt_abertura)) {
            $pipeline->DT_ABERTURA = $request->dt_abertura;
        }
        if(isset($request->dt_abertura)) {
            $pipeline->DT_ENCERRAMENTO = $request->dt_abertura;
        }        
        if(isset($request->dt_inicio_op)) {
            $pipeline->DT_INICIO = $request->dt_inicio_op;
        }        
        if(isset($request->prazo_contrato)) {            
            $pipeline->PRAZO = $request->prazo_contrato;
        }              
        if(isset($request->probabilidade)) {
            $pipeline->PROBAB = $request->probabilidade;
        }        
        if(isset($request->situacao)) {
            $pipeline->ID_SITUACAO = $request->situacao;
        }          
        if(isset($request->tempo)) {
            $pipeline->TEMPO = $request->tempo;   
        }     
        if(isset($request->tempo)) {
            $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";
        }
        $pipeline->DTOPEINC = '2020-09-01';

        $pipeline->ID_FECHAMENTO = 1;

        $dt_atual = date('Y-m-d');
        list($ano_atual, $mes_atual, $dia_atual) = explode("-", $dt_atual);
        list($ano_dt_abertura, $mes_dt_abertura, $dia_dt_abertura) = explode("-", $pipeline->DT_ABERTURA);
        list($ano_dt_encerr, $mes_dt_encerr, $dia_dt_encerr) = explode("-", $pipeline->DT_ENCERRAMENTO);

        // if ($ano_dt_abertura == $ano_atual && $mes_dt_abertura == $mes_atual) {
        //     $pipeline->MUDANCA_STS = "Nova";
        // }
        // else if($ano_dt_encerr == ano_atual && $mes_dt_encerr == $mes_atual){
        //     $pipeline->MUDANCA_STS = "Nova";
        // }
        $situacao = SituacaoController::find($request->situacao);
        echo $situacao[0]->SITUACAO;

        $op = "sucess";
        try {
            // $pipeline->save();
        } catch (\Throwable $th) {            
            $op = "error";
        }
        // return $ano;
        // return redirect()->action(
        //     'PipelineController@show', ['op' => $op]
        // );
    }    
    public function delete(Request $request) {
        $op = "sucess";
        $code = 200;
        try {
            Pipeline::where('ID_PIPELINE', '=', $request->id)->delete();            
        } catch (\Throwable $th) {
            $op = "error";
            $code = 200;
        }
        return response()-> json([
            'status' => $op,
        ], $code);
    }
    public function update(Request $request) {
        $pipeline = new Pipeline();
        $request_sts = false;
        
        // if() {                                      
            // if {     
                // if {         
                    // if(isset($request->volume_m3)) {       
                        // if(isset($request->dt_abertura)) {
                        //     if(isset($request->dt_inicio_op)) {
                        //         if(isset($request->prazo_contrato)) {       
                        //             if(isset($request->probabilidade)) {
                        //                 if(isset($request->situacao)) {
                        //                     if(isset($request->tempo)) {
                        //                         if(isset($request->mudanca_sts)) { 
                                                    $pipeline->ID_PIPELINE = $request->id;  
                                                    $pipeline->CLIENTE = $request->cliente;       
                                                    $pipeline->PROJETO = $request->projeto;                       
                                                    $pipeline->VALOR = $request->valor_m3;                             
                                                    $pipeline->VOLUME = $request->volume_m3;                            
                                                    $pipeline->DT_ABERTURA = $request->dt_abertura;                                
                                                    $pipeline->DT_INICIO = $request->dt_inicio_op;                                         
                                                    $pipeline->PRAZO = $request->prazo_contrato;                                        
                                                    $pipeline->PROBAB = $request->probabilidade;                                            
                                                    $pipeline->ID_SITUACAO = $request->situacao;                                                
                                                    $pipeline->TEMPO = $request->tempo;                                                     
                                                    $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";                                                                                                                                                            
                                                    $pipeline->ID_FECHAMENTO = 1;       
                                                    $pipeline->MUDANCA_STS = $request->mudanca_sts;
                                                    $request_sts = true;                                                
            //                                     }                                                 
            //                                 }                                             
            //                             }                                        
            //                         }
            //                     }                                 
            //                 }                            
            //             }                        
            //         }                     
            //     }                
            // }                          
        // }
        $op = "sucess";   
        $code = 200;     
        if($request_sts == true) {
            try {
                Pipeline::where('ID_PIPELINE', '=', $pipeline->ID_PIPELINE)
                    ->update(array(
                        'CLIENTE' => $pipeline->CLIENTE,
                        'PROJETO' => $pipeline->PROJETO,
                        'VALOR' => $pipeline->VALOR,
                        'VOLUME' => $pipeline->VOLUME,
                        'DT_ABERTURA' => $pipeline->DT_ABERTURA,
                        'DT_INICIO' => $pipeline->DT_INICIO,
                        'PRAZO' => $pipeline->PRAZO,
                        'PROBAB' => $pipeline->PROBAB,
                        'ID_SITUACAO' => $pipeline->ID_SITUACAO,
                        'TEMPO' => $pipeline->TEMPO,
                        'DURACAO' => $pipeline->DURACAO
                    ));
                $op = "sucess";    
                $code = 200;
            } catch (\Throwable $th) {            
                $op = $th;    
                $code = 500;            
            }
        }else { 
            $op = "error";
            $code = 500;
        }
        
        return response()-> json([
            'status' => $op,
        ], $code);  
    }
}
