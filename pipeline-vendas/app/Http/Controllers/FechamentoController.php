<?php

namespace App\Http\Controllers;

use App\Fechamento;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\SituacaoController;
use Illuminate\Http\Request;

class FechamentoController extends Controller {
  public function show(Request $request) {

    $fechamentos = Fechamento::all();
    date_default_timezone_set('America/Sao_Paulo');
    $dt_atual = date('Y/m/d');
    list($ano_atual, $mes_atual, $dia_atual) = explode("/", $dt_atual);
    $fechamento_aberto[0] = new Fechamento();

   $ultimo_fechamento = Fechamento::orderBy('ID_FECHAMENTO', 'DESC')->first();
   $dt_fechamento = str_replace(" 00:00:00.000", "", $ultimo_fechamento["DT_REFERENCIA"]);
   list($ano_fechamento, $mes_fechamento, $dia_fechamento) = explode("-", $dt_fechamento);
        // o condicional tem que validar se a data é igual o mes atual e o fechamento estiver aberto
    // se a data atual for maior que o ultimo fechamento deve ser mostrada a informaçao a cima
    if($mes_fechamento !=  $mes_atual && $ano_fechamento == $ano_atual){      
      $fechamento_aberto[0]->DT_FECHAMENTO = "$dia_atual/$mes_atual/$ano_atual";
      $fechamento_aberto[0]->DT_REFERENCIA = ["mes" => $mes_atual, "ano" =>$ano_atual];
      $ultimo_dia = date("t", mktime(0, 0, 0, $mes_atual, '01', $ano_atual));
      $fechamento_aberto[0]->PERIODO = "01/$mes_atual/$ano_atual à $ultimo_dia/$mes_atual/$ano_atual";    
    }else {
      $fechamento_aberto = null;
    }

    //print_r($fechamento_aberto[0]->DT_REFERENCIA["ano"]);
  //  echo"<br><h1 style='color: white'>".$dt_fechamento."</h1>";
    //   ->whereYear("DT_REFERENCIA", "=", $ano_atual)
    //   ->get();

    // } else {
    //   foreach ($fechamentos_abertos as $result => $value) {

    //     $situacao = SituacaoController::find($value->ID_SITUACAO);
    //     $value->ID_SITUACAO = $situacao;

    //     $value->DT_FECHAMENTO = str_replace(" 00:00:00.000", "", $value->DT_FECHAMENTO);
    //     $value->DT_FECHAMENTO = date("d-m-Y", strtotime($value->DT_FECHAMENTO));

    //     $value->DT_REFERENCIA = str_replace(" 00:00:00.000", "", $value->DT_REFERENCIA);
    //     $value->DT_REFERENCIA = date("m/Y", strtotime($value->DT_REFERENCIA));
    //   }
    // }

    foreach ($fechamentos as $result => $value) {

      $situacao = SituacaoController::find($value->ID_SITUACAO);
      $value->ID_SITUACAO = $situacao;

      $value->DT_FECHAMENTO = str_replace(" 00:00:00.000", "", $value->DT_FECHAMENTO);
      $value->DT_FECHAMENTO = date("d-m-Y", strtotime($value->DT_FECHAMENTO));

      $value->DT_REFERENCIA = str_replace(" 00:00:00.000", "", $value->DT_REFERENCIA);
      $value->DT_REFERENCIA = date("m/Y", strtotime($value->DT_REFERENCIA));
    }

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
    return view("fechamento")
      ->with("fechamentos", $fechamentos)
      ->with("fechamento_aberto", $fechamento_aberto);
  }
  public static function create($request) {
    $fechamento = new Fechamento();

    $fechamento->DT_FECHAMENTO = $request["DT_FECHAMENTO"];
    $fechamento->DT_REFERENCIA = $request["DT_REFERENCIA"];
    $fechamento->ID_SITUACAO = $request["ID_SITUACAO"];
    $fechamento->TOT_REC_EST = $request["TOT_REC_EST"];
    $fechamento->TOT_REC_ESP = $request["TOT_REC_ESP"];
    $fechamento->TOT_IMPACTO = $request["TOT_IMPACTO"];

    return $fechamento->save();
  }
  public function update(Request $request) {

  }
  public function find(Request $request) {
    $fechamento = Fechamento::where('ID_FECHAMENTO', '=', $request->id_fechamento)->get();
    return $fechamento;
  }

  public function getDetalhes(Request $request) {
    list($mes, $ano) = explode("/", $request->dt_fechamento);
    $pipeline = PipelineController::findByMonth($mes, $ano);

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_SITUACAO);
      $value->ID_SITUACAO = $situacao;

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
    return $pipeline;
  }

  public function getDetalhesFato(Request $request) {
    list($mes, $ano) = explode("/", $request->dt_fechamento);
    $pipeline = PipelineController::findByMonthFato($mes, $ano);

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_SITUACAO);
      $value->ID_SITUACAO = $situacao;

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
    return $pipeline;
  }

  public function confirmarFechamento(Request $request) {
    $dt_fechamento = $request->dt_fechamento;
    list($mes, $ano) = explode("/", $dt_fechamento);
    
    $dt_atual = date('Y/m/d');
    list($ano_atual, $mes_atual, $dia_atual) = explode("/", $dt_atual);

    $fechamento = Fechamento::whereMonth('DT_REFERENCIA', '=', $mes)
     ->whereYear('DT_REFERENCIA', '=', $ano)
     ->get();
 
     $status_op = "sucess";
     $code = 200;
     // não esquecer de validar se o fechamento está em aberto 
     if (($ano_atual == $ano && $mes_atual == $mes)) {

      date_default_timezone_set('America/Sao_Paulo');
      $pipeline_mensal = PipelineController::findByMonth($mes, $ano);
      $dt_fechamento = date('Y/m/d');
      $dt_referencia = date('Y/m/d');
      $id_situacao = 2; // <<<<<<<<<< VERIFICAR A NECESSIDADE DESTE CAMPO <<<<<<<<<<<<<<<<<<<,
      $total_rec_est = 0;
      $total_rec_esp = 0;
      $total_rec_impacto = 0;
  
      foreach ($pipeline_mensal as $result) {
  
        $total_rec_est += $result->REC_ESTIMADA;
        $total_rec_esp += $result->REC_ESPERADA;
        $total_rec_impacto += $result->IMPACTO;
      }
  
      $novo_fechamento = new Fechamento();
  
      $novo_fechamento->DT_FECHAMENTO = $dt_fechamento;
      $novo_fechamento->DT_REFERENCIA = $dt_referencia;
      $novo_fechamento->ID_SITUACAO = $id_situacao;
      $novo_fechamento->TOT_REC_EST = $total_rec_est;
      $novo_fechamento->TOT_REC_ESP = $total_rec_esp;
      $novo_fechamento->TOT_IMPACTO = $total_rec_impacto;

      try {
        $novo_fechamento->save();
        $status_op = true;
      } catch (\Throwable $th) {
        $status_op = "erro";
        $code = 500;
      }
      if ($code == 200) {
        try {
         $pipeline = PipelineController::gravarPipelineFato($novo_fechamento);
          $status_op = "sucess";
        } catch (\Throwable $th) {
          $status_op = "erro";
          $code = 500;      
        }                 
      }
    }else {
      $status_op = "erro";
      $code = 500;
    }     

    $code = 200;
    return response()->json([
      'status' => $status_op,
    ], $code);
  }
}
