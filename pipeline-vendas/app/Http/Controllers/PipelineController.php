<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pipeline;
use App\Http\Controllers\SituacaoController;

class PipelineController extends Controller
{
    public function show(Request $request )
    {   if (isset($request)) {              
        $uri = $request->path();
        }else{
            $uri = "";
        }
        
        $pipeline = Pipeline::all();
        $situacao = SituacaoController::find(1);
        $situacoes_lst = SituacaoController::getAll();

        $pipeline_lst = $pipeline;
        
        foreach ($pipeline as $result => $value) {       
            $situacao = SituacaoController::find($value->ID_SITUACAO);
            $value->ID_SITUACAO = $situacao[0]->SITUACAO;
            
            $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
            $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
            $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

            $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
            $value->DT_INICIO = date("d-m-Y", strtotime($value->DT_INICIO));

            $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
            $value->DT_ABERTURA = date("d-m-Y", strtotime($value->DT_ABERTURA));

            if (isset($value->DT_ENCERR)) {                
                $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
                $value->DT_ENCERR = date("d-m-Y", strtotime($value->DT_ENCERR));
            }
        }
        if ($uri == 'create') {            
            return view('pipeline')->with('pipeline', $pipeline)
            ->with('pipeline_lst', $pipeline_lst)
            ->with('situacoes_lst', $situacoes_lst)
            ->with('op', $uri);
        }else{
            return view('pipeline')->with('pipeline', $pipeline)
            ->with('pipeline_lst', $pipeline_lst);
        }    

    }

    public function create(Request $request)
    {
        $pipeline = new Pipeline();
        if(isset($request->cliente)){            
            $pipeline->CLIENTE = $request->cliente;
        }
        if (isset($request->projeto)) {            
            $pipeline->PROJETO = $request->projeto;
        }
        if (isset($request->valor_m3)) {            
            $pipeline->VALOR = $request->valor_m3;
        }        
        if (isset($request->volume_m3)) {            
            $pipeline->VOLUME = $request->volume_m3;
        }    
        if (isset($request->dt_abertura)) {
            $pipeline->DT_ABERTURA = $request->dt_abertura;
        }
        if (isset($request->dt_inicio_op)) {
            $pipeline->DT_INICIO = $request->dt_inicio_op;
        }        
        if (isset($request->prazo_contrato)) {            
            $pipeline->PRAZO = $request->prazo_contrato;
        }              
        if (isset($request->probabilidade)) {
            $pipeline->PROBAB = $request->probabilidade;
        }        
        if (isset($request->situacao)) {
            $pipeline->ID_SITUACAO = $request->situacao;
        }          
        if (isset($request->tempo)) {
            $pipeline->TEMPO = $request->tempo;   
        }     
        if (isset($request->tempo)) {
            $pipeline->TEMPO = $request->tempo;   
        }         

        $pipeline->DTOPEINC = '2020-09-01';

        $pipeline->ID_FECHAMENTO = 1;

        if (isset($request->mudanca_sts)) {            
            $pipeline->MUDANCA_STS = $request->mudanca_sts;
        }

        $op = "sucess";
        try {
            $pipeline->save();
        } catch (\Throwable $th) {            
            $op = "error";
        }

        return redirect()->action(
            'PipelineController@show', ['op' => $op]
        );
    }    
}
