<?php

namespace App\Http\Controllers;

use App\Fechamento;
use App\FechamentoItens;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\SituacaoController;
use App\Resumo;
use App\TipoTotal;
use Illuminate\Http\Request;
use App\Services\FechamentoService;

class FechamentoController extends Controller {

  protected $fechamentoService;
  /***
   *
   */
  public function __construct(FechamentoService $fechamentoService) {

    $this->fechamentoService = $fechamentoService;
    $this->middleware('auth');
  }

  public function criar(Request $request) {
    return $this->fechamentoService->salvar($request);
  }

  public function show(Request $request) {
    date_default_timezone_set('America/Sao_Paulo');
    $dt_atual = date('Y/m/d');

    $situacao = SituacaoController::findBySituacao("Aberto", 0);
    $fechamentos = Fechamento::where("ID_TAB_SITUACAO", "<>", $situacao[0]->ID_TAB_SITUACAO)->get();
    $fechamento_aberto = Fechamento::where('ID_TAB_SITUACAO', '=', $situacao[0]->ID_TAB_SITUACAO)->get();
    $qtde_fech_aberto = count($fechamento_aberto);
    if (count($fechamento_aberto) == 0) {
      list($ano, $mes, $dia) = explode("/", $dt_atual);

      $fechamento_aberto[0] = new Fechamento();

    } else {
      $dt_fechamento = str_replace(" 00:00:00.000", "", $fechamento_aberto[0]->DT_REFERENCIA);
      list($ano, $mes, $dia) = explode("-", $dt_fechamento);
    }

    $fechamento_aberto[0]->DT_FECHAMENTO = "$dia/$mes/$ano";
    $fechamento_aberto[0]->DT_REFERENCIA = ["mes" => $mes, "ano" => $ano];
    $ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano));
    $fechamento_aberto[0]->PERIODO = "01/$mes/$ano à $ultimo_dia/$mes/$ano";

    // if ($qtde_fech_aberto == 0) {
    //   Fechamento::insert([
    //     'DT_FECHAMENTO' => $dt_atual,
    //     'DT_REFERENCIA' => $dt_atual,
    //     'ID_TAB_SITUACAO' => $situacao[0]->ID_TAB_SITUACAO,
    //     'TOT_REC_EST' => 0,
    //     'TOT_REC_ESP' => 0,
    //     'TOT_IMPACTO' => 0,
    //   ]);
    // }
    foreach ($fechamentos as $result => $value) {

      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);

      $value->ID_TAB_SITUACAO = $situacao;

      $value->DT_FECHAMENTO = str_replace(" 00:00:00.000", "", $value->DT_FECHAMENTO);
      $value->DT_FECHAMENTO = date("d-m-Y", strtotime($value->DT_FECHAMENTO));

      $value->DT_REFERENCIA = str_replace(" 00:00:00.000", "", $value->DT_REFERENCIA);
      $value->DT_REFERENCIA = date("m/Y", strtotime($value->DT_REFERENCIA));
      list($mes, $dia) = explode("/", $value->DT_REFERENCIA);
      $value->DT_REFERENCIA = ["mes" => $mes, "ano" => $ano];
    }

    return view("fechamento")
      ->with("fechamentos", $fechamentos)
      ->with("fechamento_aberto", $fechamento_aberto);
  }
  /***
   *
   */
  public static function create($request) {
    $fechamento = new Fechamento();

    $fechamento->DT_FECHAMENTO = $request["DT_FECHAMENTO"];
    $fechamento->DT_REFERENCIA = $request["DT_REFERENCIA"];
    $fechamento->ID_TAB_SITUACAO = $request["ID_TAB_SITUACAO"];
    $fechamento->TOT_REC_EST = $request["TOT_REC_EST"];
    $fechamento->TOT_REC_ESP = $request["TOT_REC_ESP"];
    $fechamento->TOT_IMPACTO = $request["TOT_IMPACTO"];

    return $fechamento->save();
  }
  /***
   *
   */
  public function cancelFechamento(Request $request) {
    $situacao = SituacaoController::findBySituacao("Cancelado", 0);
    $status_op = "sucess";
    $status_code = "200";

    try {
      Fechamento::where("ID_TAB_FECHAMENTO", "=", $request->id_fechamento)
        ->update(
          array(
            'ID_TAB_SITUACAO' => $situacao[0]->ID_TAB_SITUACAO,
          )
        );
    } catch (\Throwable $th) {
      $status_op = "error";
      $status_code = "401";
    }

    return response()->json([
      'msg' => $status_op,
    ], $status_code);
  }
  /***
   *
   */
  public function find(Request $request) {
    $fechamento = Fechamento::where('ID_FECHAMENTO', '=', $request->id_fechamento)->get();
    return $fechamento;
  }
  /***
   *
   */
  public function getDetalhes(Request $request) {
    list($mes, $ano) = explode("/", $request->dt_fechamento);
    $pipeline = PipelineController::findByDate($mes, $ano);

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);
      $value->ID_TAB_SITUACAO = $situacao;

      $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
      $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
      $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

      $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
      $value->DT_INICIO = date("d/m/Y", strtotime($value->DT_INICIO));

      $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
      $value->DT_ABERTURA = date("d/m/Y", strtotime($value->DT_ABERTURA));

      if (isset($value->DT_ENCERR)) {
        $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
        $value->DT_ENCERR = date("d/m/Y", strtotime($value->DT_ENCERR));
      }
    }
    return $pipeline;
  }
  /***
   *
   */
  public function getDetalhesFato(Request $request) {
    list($mes, $ano) = explode("/", $request->dt_fechamento);
    $pipeline = PipelineController::findByDateFato($mes, $ano);

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);
      $value->ID_TAB_SITUACAO = $situacao;

      $value->REC_ESTIMADA = number_format($value->REC_ESTIMADA, 2, ".", '');
      $value->REC_ESPERADA = number_format($value->REC_ESPERADA, 2, ".", '');
      $value->IMPACTO = number_format($value->IMPACTO, 2, ".", '');

      $value->DT_INICIO = str_replace(" 00:00:00.000", "", $value->DT_INICIO);
      $value->DT_INICIO = date("d/m/Y", strtotime($value->DT_INICIO));

      $value->DT_ABERTURA = str_replace(" 00:00:00.000", "", $value->DT_ABERTURA);
      $value->DT_ABERTURA = date("d/m/Y", strtotime($value->DT_ABERTURA));

      if (isset($value->DT_ENCERR)) {
        $value->DT_ENCERR = str_replace(" 00:00:00.000", "", $value->DT_ENCERR);
        $value->DT_ENCERR = date("d/m/Y", strtotime($value->DT_ENCERR));
      }
    }
    return $pipeline;
  }

  public function buscaTotalReceitaAnterior() {
    return FechamentoItens::join(
      'TAB_TIPO_TOT',
      'TAB_TIPO_TOT.ID_TAB_TIPO_TOT',
      '=',
      'TAB_FECHAMENTO_ITENS.ID_TAB_TIPO_TOT'
    )
      ->join('TAB_SITUACAO', 'TAB_SITUACAO.ID_TAB_SITUACAO', '=', 'TAB_TIPO_TOT.ID_TAB_SITUACAO')
      ->where('TAB_FECHAMENTO_ITENS.ID_TAB_FECHAMENTO', '=', 2)
      ->select('TAB_FECHAMENTO_ITENS.*', 'TAB_TIPO_TOT.ABREV', 'TAB_SITUACAO.SITUACAO')->get();
  }
  /***
   *
   */
  public function confirmaTeste() {

    // $teste = (object) array("teste" => 2);
    // echo $teste->teste;
    // $this->getResumoFato()
    $receitas_pipeline_atual = PipelineController::buscaTotalReceitaAtual();
    $teste = self::buscaTotalReceitaAnterior();

    foreach ($receitas_pipeline_atual as $key => $value) {
      echo $key;
      echo " ";
      echo $receitas_pipeline_atual[$key]->getReceitaEsp();
      echo " ";
    }

    $status_op = "sucess";
    $status_code = "200";
    return response()->json([
      'msg1' => $teste,
      'msg2' => $receitas_pipeline_atual,
    ], $status_code);
  }

  public function confirmarFechamento(Request $request) {
    date_default_timezone_set('America/Sao_Paulo');
    $dt_atual = date('Y-m-d');
    $id_fechamento = $request->id_fechamento;
    $result_up_fechamento = true;
    $result_ins_novo_fechamento = true;

    $fechamento = Fechamento::where("ID_TAB_FECHAMENTO", "=", $id_fechamento)->get();

    // if (count($fechamento) > 0) {

    //   $result_insert_fato = PipelineController::gravarPipelineFato($fechamento[0]);

    //   if ($result_insert_fato == "sucess") {
    //     $situacao = SituacaoController::findBySituacao("PROCESSADO", 0);

    //     $fechamento[0]->ID_TAB_SITUACAO = $situacao[0]->ID_TAB_SITUACAO;
    //     $total_fechamento = PipelineController::somaTotalReceitas($fechamento[0], 0);
    //     $total_fechamento_anterior = PipelineController::somaTotalReceitas($fechamento[0], 0);

    //     try {
    //       Fechamento::where("ID_TAB_FECHAMENTO", "=", $fechamento[0]->ID_TAB_FECHAMENTO)
    //         ->update(
    //           array(
    //             'DT_FECHAMENTO' => $dt_atual,
    //             'ID_TAB_SITUACAO' => $situacao[0]->ID_TAB_SITUACAO,
    //             'TOT_REC_EST' => $total_fechamento[0]->REC_ESTIMADA,
    //             'TOT_REC_ESP' => $total_fechamento[0]->REC_ESPERADA,
    //             'TOT_IMPACTO' => $total_fechamento[0]->IMPACTO)
    //         );
    //     } catch (\Throwable $th) {$result_up_fechamento = false;}

    //     if ($result_up_fechamento) {
    //       $dt_fechamento = str_replace(" 00:00:00.000", "", $fechamento[0]->DT_REFERENCIA);
    //       list($ano, $mes, $dia) = explode("-", $dt_fechamento);
    //       try {
    //         $situacao = SituacaoController::findBySituacao("Aberto", 0);

    //         $novo_fechamento = new Fechamento();
    //         $mes == 12 ? $mes = 1 && $ano += 1 : $mes += 1;
    //         $novo_fechamento->DT_FECHAMENTO = "$ano-$mes-$dia";
    //         $novo_fechamento->DT_REFERENCIA = "$ano-$mes-$dia";
    //         $novo_fechamento->ID_TAB_SITUACAO = $situacao[0]->ID_TAB_SITUACAO;
    //         $novo_fechamento->TOT_REC_EST = 0;
    //         $novo_fechamento->TOT_REC_ESP = 0;
    //         $novo_fechamento->TOT_IMPACTO = 0;

    //         $novo_fechamento->save();
    //       } catch (\Throwable $th) {$result_up_fechamento = false;}
    //     }
    //   }
    // }

    $status_op = "sucess";
    $status_code = "200";
    return response()->json([
      'msg' => $status_op,
    ], $status_code);
  }
  /***
   *
   */
  public function getResumo(Request $request) {

    $fechamento = Fechamento::where("ID_TAB_FECHAMENTO", "=", $request->id)->get();

    // if (count($fechamento) > 0) {

    //   $total_fechamento = PipelineController::somaTotalReceitas($fechamento[0], 1);

    //   $fechamento[0]->TOT_REC_EST = number_format($total_fechamento[0]->REC_ESTIMADA, 2, ".", '');
    //   $fechamento[0]->TOT_REC_ESP = number_format($total_fechamento[0]->REC_ESPERADA, 2, ".", '');
    //   $fechamento[0]->TOT_IMPACTO = number_format($total_fechamento[0]->IMPACTO, 2, ".", '');

    //   $fechamento[0]->DT_FECHAMENTO = str_replace(" 00:00:00.000", "", $fechamento[0]->DT_FECHAMENTO);
    //   $fechamento[0]->DT_FECHAMENTO = date("d/m/Y", strtotime($fechamento[0]->DT_FECHAMENTO));

    //   $fechamento[0]->DT_REFERENCIA = str_replace(" 00:00:00.000", "", $fechamento[0]->DT_REFERENCIA);
    //   list($ano, $mes, $dia) = explode("-", $fechamento[0]->DT_REFERENCIA);
    //   $fechamento[0]->DT_REFERENCIA = "$mes/$ano";

    //   if ($mes == 1) {
    //     $ultimo_dia = date("t", mktime(0, 0, 0, $mes += 11, '01', $ano -= 1));
    //     $fechamento[0]->PERIODO = "01/$mes/$ano à $ultimo_dia/$mes/$ano";
    //   } else {
    //     $ultimo_dia = date("t", mktime(0, 0, 0, $mes -= 1, '01', $ano));

    //     $fechamento[0]->PERIODO = ($mes < 10) ? "01/0$mes/$ano à $ultimo_dia/0$mes/$ano" : "01/$mes/$ano à $ultimo_dia/$mes/$ano";
    //   }
    // }

    $status_code = "200";
    return response()->json([
      'fechamento' => $fechamento[0],
    ], $status_code);
  }
  /***
   *
   */
  public function getResumoFato(Request $request) {

    $situacoes = array("NOVA", "FECHADA", "DECLINADA", "ATIVA", "REF. ANTERIOR", "REF. ATUAL");
    $fechamento = Fechamento::where("ID_TAB_FECHAMENTO", "=", $request->id)->get();
    $resumos = array();

    foreach ($situacoes as $sit) {
      $situacao = SituacaoController::findBySituacao($sit, 1);
      $tipo_totais = TipoTotal::where("ID_TAB_SITUACAO", "=", $situacao[0]->ID_TAB_SITUACAO)->get();

      $resumo = new Resumo($sit);
      foreach ($tipo_totais as $key => $value) {

        $tot_fechamento_itens = FechamentoItens::where("ID_TAB_FECHAMENTO", "=", 2)
          ->where("ID_TAB_TIPO_TOT", "=", $id_tot_est = $tipo_totais[$key]["ID_TAB_TIPO_TOT"])->get();

        if ($tipo_totais[$key]["ABREV"] == "TOT_REC_EST") {
          $resumo->setReceitaEst($tot_fechamento_itens[0]->TOTAL);
        }

        if ($tipo_totais[$key]["ABREV"] == "TOT_REC_ESP") {
          $resumo->setReceitaEsp($tot_fechamento_itens[0]->TOTAL);
        }
        if ($tipo_totais[$key]["ABREV"] == "TOT_REC_IMP") {
          $resumo->setImpacto($tot_fechamento_itens[0]->TOTAL);
        }
      }
      if ($sit == "REF. ANTERIOR") {
        $resumos["ANTERIOR"] = $resumo;
      }
      if ($sit == "ATIVA") {
        $resumos["ATIVA"] = $resumo;
      }
      if ($sit == "NOVA") {
        $resumos["NOVA"] = $resumo;
      }
      if ($sit == "FECHADA") {
        $resumos["FECHADA"] = $resumo;
      }
      if ($sit == "DECLINADA") {
        $resumos["DECLINADA"] = $resumo;
      }
      if ($sit == "REF. ATUAL") {
        $resumos["ATUAL"] = $resumo;
      }
    }
    list($ano, $mes, $dia) = explode("-", $fechamento[0]->DT_REFERENCIA);
    $dt_ref_atual = "$mes/$ano";
    $mes_anterior = $mes == 1 ? $mes + 11 : $mes - 1;
    $ano_anterior = $mes == 1 ? $ano - 1 : $mes - 1;
    $dt_ref_anterior = "$mes_anterior/$ano_anterior";

    $status_code = "200";
    return response()->json([
      'dt_referencia_anterior' => $dt_ref_anterior,
      'dt_referencia_atual' => $dt_ref_atual,
      'rec_est_anterior' => $resumos["ANTERIOR"]->getReceitaEst(),
      'rec_esp_anterior' => $resumos["ANTERIOR"]->getReceitaEsp(),
      'rec_imp_anterior' => $resumos["ANTERIOR"]->getImpacto(),
      'rec_est_ativa' => $resumos["ATIVA"]->getReceitaEst(),
      'rec_esp_ativa' => $resumos["ATIVA"]->getReceitaEsp(),
      'rec_imp_ativa' => $resumos["ATIVA"]->getImpacto(),
      'rec_est_nova' => $resumos["NOVA"]->getReceitaEst(),
      'rec_esp_nova' => $resumos["NOVA"]->getReceitaEsp(),
      'rec_imp_nova' => $resumos["NOVA"]->getImpacto(),
      'rec_est_fechada' => $resumos["FECHADA"]->getReceitaEst(),
      'rec_esp_fechada' => $resumos["FECHADA"]->getReceitaEsp(),
      'rec_imp_fechada' => $resumos["FECHADA"]->getImpacto(),
      'rec_est_declinada' => $resumos["DECLINADA"]->getReceitaEst(),
      'rec_esp_declinada' => $resumos["DECLINADA"]->getReceitaEsp(),
      'rec_imp_declinada' => $resumos["DECLINADA"]->getImpacto(),
      'rec_est_atual' => $resumos["ATUAL"]->getReceitaEst(),
      'rec_esp_atual' => $resumos["ATUAL"]->getReceitaEsp(),
      'rec_imp_atual' => $resumos["ATUAL"]->getImpacto(),
    ], $status_code);
  }
}
