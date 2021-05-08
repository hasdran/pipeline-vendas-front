<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SituacaoController;
use App\Pipeline;
use App\PipelineFato;
use App\Resumo;
use App\Services\PipelineService;
use Illuminate\Http\Request;

class PipelineController extends Controller {

  protected $pipelineService;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(PipelineService $pipelineService) {

    $this->pipelineService = $pipelineService;
    $this->middleware('auth');
  }

  public function mostrar() {

    return $this->pipelineService->mostrarTodos();
  }

  public function criar(Request $request) {
    return $this->pipelineService->salvar($request);

  }

  public function alterar(Request $request) {
    return $this->pipelineService->alterar($request);
  }

  public function remover(Request $request) {
    return $this->pipelineService->remover($request);
  }

  public function show(Request $request) {
    $opt_declinadas = (object) array(
      "tot_rec_est" => 0,
      "tot_rec_esp" => 0,
      "tot_impacto" => 0,
    );
    $opt_fechadas = (object) array(
      "tot_rec_est" => 0,
      "tot_rec_esp" => 0,
      "tot_impacto" => 0,
    );
    $opt_mudancas = (object) array(
      "tot_rec_est" => 0,
      "tot_rec_esp" => 0,
      "tot_impacto" => 0,
    );
    $opt_novas = (object) array(
      "tot_rec_est" => 0,
      "tot_rec_esp" => 0,
      "tot_impacto" => 0,
    );

    $pipeline = Pipeline::where('SITUACAO', '=', '1')->orderBy("ID_PIPELINE", "DESC")->get();
    $situacao_pipeline = 1;
    $situacoes_lst = SituacaoController::findByTipo($situacao_pipeline);

    $pipeline_lst = $pipeline;

    foreach ($pipeline as $result => $value) {
      $situacao = SituacaoController::find($value->ID_TAB_SITUACAO);
      $value->ID_TAB_SITUACAO = $situacao;

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

      if ($value->MUDANCA_STS == "Declinada") {
        $opt_declinadas->tot_rec_est += $value->REC_ESTIMADA;
        $opt_declinadas->tot_rec_esp += $value->REC_ESPERADA;
        $opt_declinadas->tot_impacto += $value->IMPACTO;

      }
      if ($value->MUDANCA_STS == "Fechada") {
        $opt_fechadas->tot_rec_est += $value->REC_ESTIMADA;
        $opt_fechadas->tot_rec_esp += $value->REC_ESPERADA;
        $opt_fechadas->tot_impacto += $value->IMPACTO;
      }
      if ($value->MUDANCA_STS == "Mudança") {
        $opt_mudancas->tot_rec_est += $value->REC_ESTIMADA;
        $opt_mudancas->tot_rec_esp += $value->REC_ESPERADA;
        $opt_mudancas->tot_impacto += $value->IMPACTO;
      }
      if ($value->MUDANCA_STS == "Nova") {

        $opt_novas->tot_rec_est += $value->REC_ESTIMADA;
        $opt_novas->tot_rec_esp += $value->REC_ESPERADA;
        $opt_novas->tot_impacto += $value->IMPACTO;
      }

    }

    return view('pipeline')->with('pipeline', $pipeline)
      ->with('pipeline_lst', $pipeline_lst);
  }

  public function create(Request $request) {
    $op = "sucess";
    $status_code = 200;
    date_default_timezone_set('America/Sao_Paulo');

    $pipeline = new Pipeline();

    if (isset($request->cliente)) {
      if (isset($request->projeto)) {
        if (isset($request->valor_m3)) {
          if (isset($request->volume_m3)) {
            if (isset($request->dt_abertura)) {
              if (isset($request->dt_inicio_op)) {
                if (isset($request->prazo_contrato)) {
                  if (isset($request->probabilidade)) {
                    if (isset($request->id_tab_situacao)) {
                      if (isset($request->tempo)) {

                        $pipeline->CLIENTE = $request->cliente;
                        $pipeline->PROJETO = $request->projeto;
                        $pipeline->VALOR = $request->valor_m3;
                        $pipeline->VOLUME = $request->volume_m3;
                        $pipeline->DT_ABERTURA = $request->dt_abertura;
                        if (isset($request->dt_encerramento)) {
                          $pipeline->DT_ENCERRAMENTO = $request->dt_encerramento;
                        }
                        $pipeline->DT_INICIO = $request->dt_inicio_op;
                        $pipeline->PRAZO = $request->prazo_contrato;
                        $pipeline->PROBAB = $request->probabilidade;
                        $pipeline->ID_TAB_SITUACAO = $request->id_tab_situacao;
                        $pipeline->TEMPO = $request->tempo;
                        $pipeline->SITUACAO = 1;
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    if (isset($request->tempo)) {
      $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";
    }

    $dt_atual = date('Y-m-d');

    $pipeline->DTOPEINC = $dt_atual; // DATA INCLUSAO

    $pipeline->MUDANCA_STS = "Nova"; // CONFIRMAR SE DEVE HAVER VALIDACAO

    $op = "sucess";

    try {
      $pipeline->save();
    } catch (\Throwable $th) {
      $op = "error";
      $status_code = 401;
    }

    return response()->json([
      'msg' => $op,
    ], $status_code);
  }

  public function delete(Request $request) {
    $op = "sucess";
    $status_code = 200;
    try {
      $pipeline = Pipeline::where('ID_PIPELINE', '=', $request->id)
        ->update(
          array(
            'SITUACAO' => 0,
          )
        );

    } catch (\Throwable $th) {
      $op = "error";
      $status_code = 200;
    }
    return response()->json([
      'msg' => $op,
    ], $status_code);
  }

  public function update(Request $request) {
    $pipeline = new Pipeline();
    $request_sts = false;

    if (isset($request->cliente)) {
      if (isset($request->projeto)) {
        if (isset($request->valor_m3)) {
          if (isset($request->volume_m3)) {
            if (isset($request->dt_abertura)) {
              if (isset($request->dt_inicio_op)) {
                if (isset($request->prazo_contrato)) {
                  if (isset($request->probabilidade)) {
                    if (isset($request->id_tab_situacao)) {
                      if (isset($request->tempo)) {
                        $pipeline->ID_PIPELINE = $request->id;
                        $pipeline->CLIENTE = $request->cliente;
                        $pipeline->PROJETO = $request->projeto;
                        $pipeline->VALOR = $request->valor_m3;
                        $pipeline->VOLUME = $request->volume_m3;
                        $pipeline->DT_ABERTURA = $request->dt_abertura;
                        $pipeline->DT_INICIO = $request->dt_inicio_op;
                        $pipeline->PRAZO = $request->prazo_contrato;
                        $pipeline->PROBAB = $request->probabilidade;
                        $pipeline->ID_TAB_SITUACAO = $request->id_tab_situacao;
                        $pipeline->TEMPO = $request->tempo;
                        $pipeline->DURACAO = ($request->prazo_contrato > 11) ? "Longo prazo" : "Curto prazo";
                        $pipeline->MUDANCA_STS = $request->mudanca_sts;
                        $request_sts = true;
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $op = "sucess";
    $status_code = 200;

    if ($request_sts == true) {
      try {
        Pipeline::where('ID_PIPELINE', '=', $pipeline->ID_PIPELINE)
          ->where('SITUACAO', '=', '1')
          ->update(array(
            'CLIENTE' => $pipeline->CLIENTE,
            'PROJETO' => $pipeline->PROJETO,
            'VALOR' => $pipeline->VALOR,
            'VOLUME' => $pipeline->VOLUME,
            'DT_ABERTURA' => $pipeline->DT_ABERTURA,
            'DT_INICIO' => $pipeline->DT_INICIO,
            'PRAZO' => $pipeline->PRAZO,
            'PROBAB' => $pipeline->PROBAB,
            'ID_TAB_SITUACAO' => $pipeline->ID_TAB_SITUACAO,
            'TEMPO' => $pipeline->TEMPO,
            'DURACAO' => $pipeline->DURACAO,
          ));
        $op = "sucess";
        $status_code = 200;
      } catch (\Throwable $th) {
        $op = $th;
        $status_code = 401;
      }
    } else {
      $op = "error";
      $status_code = 401;
    }

    return response()->json([
      'msg' => $op,
    ], $status_code);
  }

  public static function findByDate($mes, $ano) {
    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)
      ->where('SITUACAO', '=', '1')
      ->get();
    return $pipeline;
  }

  public static function findByDateFato($mes, $ano) {
    $pipeline = PipelineFato::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)->get();
    return $pipeline;
  }

  public static function gravarPipelineFato($fechamento) {
    $dt_fechamento = str_replace(" 00:00:00.000", "", $fechamento->DT_REFERENCIA);
    list($ano, $mes, $dia) = explode("-", $dt_fechamento);

    $pipeline = Pipeline::whereMonth('DT_ABERTURA', '=', $mes)
      ->whereYear('DT_ABERTURA', '=', $ano)
      ->where('SITUACAO', '=', '1')
      ->get();
    foreach ($pipeline as $indice => $result) {
      $pipeline[$indice]->ID_TAB_FECHAMENTO = $fechamento->ID_TAB_FECHAMENTO;
      $pipeline[$indice]->DTOPEINC = date('Y-m-d H:i:s');
      $pipeline[$indice]->SITUACAO = "Processado";
    }

    $status_op = "sucess";
    try {
      foreach ($pipeline as $chave => $result) {
        PipelineFato::insert([
          [
            "CLIENTE" => $result->CLIENTE,
            "PROJETO" => $result->PROJETO,
            "VALOR" => $result->VALOR,
            "VOLUME" => $result->VOLUME,
            "DT_ABERTURA" => $result->DT_ABERTURA,
            "DT_INICIO" => $result->DT_INICIO,
            "DT_ENCERR" => $result->DT_ENCERRAMENTO,
            "PRAZO" => $result->PRAZO,
            "PROBAB" => $result->PROBAB,
            "ID_TAB_SITUACAO" => $result->ID_TAB_SITUACAO,
            "SITUACAO" => "Processado",
            "TEMPO" => $result->TEMPO,
            "REC_ESTIMADA" => $result->REC_ESTIMADA,
            "REC_ESPERADA" => $result->REC_ESPERADA,
            "IMPACTO" => $result->IMPACTO,
            "DURACAO" => $result->DURACAO,
            "DTOPEINC" => date('Y/m/d H:i:s'),
            "MUDANCA_STS" => $result->MUDANCA_STS,
            "ID_TAB_FECHAMENTO" => $fechamento->ID_TAB_FECHAMENTO,
          ],
        ]);
      }
    } catch (\Throwable $th) {$status_op = $th;}

    return $status_op;
  }

  public static function buscaTotalReceitaAtual() {

    $pipeline_lst = Pipeline::selectRaw('sum(REC_ESTIMADA) as REC_ESTIMADA, sum(REC_ESPERADA) as REC_ESPERADA, sum(IMPACTO) as IMPACTO, MUDANCA_STS')
      ->where("SITUACAO", 1)
      ->groupBy("MUDANCA_STS")
      ->get();

    $mudanca_sts = ["Ativa", "Antiga", "Declinada", "Nova"];
    $resumo_receitas = [];

    foreach ($mudanca_sts as $value) {
      $resumo = self::filtraReceitas($pipeline_lst, $value);
      $resumo_receitas[strtoupper($value)] = $resumo;
    }

    return $resumo_receitas;
  }

  public static function filtraReceitas($receitas, $status) {
    $resumo = new Resumo($status);

    foreach ($receitas as $indice => $result) {

      if ($receitas[$indice]->MUDANCA_STS == $status) {
        $resumo->setReceitaEst($receitas[$indice]->REC_ESTIMADA);
        $resumo->setReceitaEsp($receitas[$indice]->REC_ESPERADA);
        $resumo->setImpacto($receitas[$indice]->IMPACTO);
        $resumo->setSituacao($status);
        return $resumo;
      }
    }
    $resumo->setReceitaEst(0);
    $resumo->setReceitaEsp(0);
    $resumo->setImpacto(0);
    $resumo->setSituacao($status);
    return $resumo;
  }
}
