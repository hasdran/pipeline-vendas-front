<?php

namespace App\Services;
use App\Repositories\FechamentoItensRepositoryInterface;
use App\Repositories\FechamentoRepositoryInterface;
use App\Repositories\PipelineFatoRepositoryInterface;
use App\Repositories\PipelineRepositoryInterface;
use App\Repositories\SituacaoRepositoryInterface;
use App\Repositories\TipoTotalRepositoryInterface;

class FechamentoService {

  protected $pipelineFatoRepository;
  protected $pipelineRepository;
  protected $situacaoRepository;
  protected $fechamentoRepository;
  protected $tipoTotalRepository;
  protected $fechamentoItensRepository;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(
    PipelineFatoRepositoryInterface $pipelineFatoRepository,
    SituacaoRepositoryInterface $situacaoRepository,
    PipelineRepositoryInterface $pipelineRepository,
    FechamentoRepositoryInterface $fechamentoRepository,
    TipoTotalRepositoryInterface $tipoTotalRepository,
    FechamentoItensRepositoryInterface $fechamentoItensRepository
  ) {

    $this->pipelineFatoRepository = $pipelineFatoRepository;
    $this->pipelineRepository = $pipelineRepository;
    $this->situacaoRepository = $situacaoRepository;
    $this->fechamentoRepository = $fechamentoRepository;
    $this->tipoTotalRepository = $tipoTotalRepository;
    $this->fechamentoItensRepository = $fechamentoItensRepository;
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  private static function removeTokenDaRequest($requestDados) {
    unset($requestDados["_token"]);

    return $requestDados;
  }

  public function somaTotalReceitasFechamento($listaDePipelinePorStatus, $diferencaEntreReceitas) {
    $totalReceitasFechamento = array(
      "ATIVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "ANTIGA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "CANCELADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "DECLINADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "FECHADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "NOVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "PROPOSTA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
    );

    foreach ($totalReceitasFechamento as $status => $receitas) {

      if ($status == "DECLINADA" || $status == "FECHADA") {
        $totalReceitasFechamento[$status]["REC_ESTIMADA"] =
          (-$listaDePipelinePorStatus[$status]["REC_ESTIMADA"]) + $diferencaEntreReceitas[$status]["REC_ESTIMADA"];
        $totalReceitasFechamento[$status]["REC_ESPERADA"] =
          (-$listaDePipelinePorStatus[$status]["REC_ESPERADA"]) + $diferencaEntreReceitas[$status]["REC_ESPERADA"];
        $totalReceitasFechamento[$status]["IMPACTO"] =
          (-$listaDePipelinePorStatus[$status]["IMPACTO"]) + $diferencaEntreReceitas[$status]["IMPACTO"];
      } elseif ($status == "ATIVA" || $status == "NOVA") {
        $totalReceitasFechamento[$status]["REC_ESTIMADA"] = $diferencaEntreReceitas[$status]["REC_ESTIMADA"];
        $totalReceitasFechamento[$status]["REC_ESPERADA"] = $diferencaEntreReceitas[$status]["REC_ESPERADA"];
        $totalReceitasFechamento[$status]["IMPACTO"] = $diferencaEntreReceitas[$status]["IMPACTO"];
      }

    }
    return $totalReceitasFechamento;
  }

  public function somaTotalPipelineAtual($listaDePipelinePorStatus) {
    $totalReceitasPipelineAtual = array(
      "ATIVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "ANTIGA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "CANCELADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "DECLINADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "FECHADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "NOVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "PROPOSTA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
    );

    foreach ($listaDePipelinePorStatus as $status => $pipelineLista) {
      foreach ($pipelineLista as $indice => $pipeline) {
        $totalReceitasPipelineAtual[$status]["REC_ESTIMADA"] += $pipeline["REC_ESTIMADA"];
        $totalReceitasPipelineAtual[$status]["REC_ESPERADA"] += $pipeline["REC_ESPERADA"];
        $totalReceitasPipelineAtual[$status]["IMPACTO"] += $pipeline["IMPACTO"];
      }
    }

    return $totalReceitasPipelineAtual;
  }

  private static function copiaPipelineParaFato($pipelineAtualLista) {
    $pipelineFatoLista = array();
    foreach ($pipelineAtualLista as $indice => $valor) {
      $pipelineFato = array();

      $pipelineFato["CLIENTE"] = $pipelineAtualLista[$indice]->CLIENTE;
      $pipelineFato["PROJETO"] = $pipelineAtualLista[$indice]->PROJETO;
      $pipelineFato["VALOR"] = $pipelineAtualLista[$indice]->VALOR;
      $pipelineFato["VOLUME"] = $pipelineAtualLista[$indice]->VOLUME;
      $pipelineFato["DT_ABERTURA"] = $pipelineAtualLista[$indice]->DT_ABERTURA;
      $pipelineFato["DT_INICIO"] = $pipelineAtualLista[$indice]->DT_INICIO;
      $pipelineFato["DT_ENCERR"] = $pipelineAtualLista[$indice]->DT_ENCERR;
      $pipelineFato["PRAZO"] = $pipelineAtualLista[$indice]->PRAZO;
      $pipelineFato["PROBAB"] = $pipelineAtualLista[$indice]->PROBAB;
      $pipelineFato["ID_TAB_SITUACAO"] = $pipelineAtualLista[$indice]->ID_TAB_SITUACAO;
      $pipelineFato["TEMPO"] = $pipelineAtualLista[$indice]->TEMPO;
      $pipelineFato["REC_ESTIMADA"] = $pipelineAtualLista[$indice]->REC_ESTIMADA;
      $pipelineFato["REC_ESPERADA"] = $pipelineAtualLista[$indice]->REC_ESPERADA;
      $pipelineFato["IMPACTO"] = $pipelineAtualLista[$indice]->IMPACTO;
      $pipelineFato["DURACAO"] = $pipelineAtualLista[$indice]->DURACAO;
      $pipelineFato["MUDANCA_STS"] = $pipelineAtualLista[$indice]->MUDANCA_STS;
      $pipelineFato["DTOPEINC"] = $pipelineAtualLista[$indice]->DTOPEINC;
      $pipelineFato["DTOPEALT"] = $pipelineAtualLista[$indice]->DTOPEALT;
      $pipelineFato["DTOPEEXC"] = $pipelineAtualLista[$indice]->DTOPEEXC;
      $pipelineFato["USUULTALT"] = $pipelineAtualLista[$indice]->USUULTALT;
      $pipelineFato["SITUACAO"] = $pipelineAtualLista[$indice]->SITUACAO;
      $pipelineFato["ID_TAB_PIPELINE_REF"] = $pipelineAtualLista[$indice]->ID_PIPELINE;

      array_push($pipelineFatoLista, $pipelineFato);
    }
    return $pipelineFatoLista;
  }

  public function filtraPipelineFatoPorId($pipelineFatoLista, $id) {

    foreach ($pipelineFatoLista as $indice => $pipelineFato) {

      if ($pipelineFatoLista[$indice]["ID_TAB_PIPELINE_REF"] == $id) {

        return $pipelineFatoLista[$indice];
      }
    }
    return "";
  }

  public static function filtraPipelinePorStatus($pipelineLista) {
    $listaResumida = array(
      "ATIVA" => [],
      "ANTIGA" => [],
      "CANCELADA" => [],
      "DECLINADA" => [],
      "FECHADA" => [],
      "NOVA" => [],
      "PROPOSTA" => [],
    );

    foreach ($pipelineLista as $key => $value) {

      switch ($pipelineLista[$key]->MUDANCA_STS) {
      case "ATIVA":
        array_push($listaResumida["ATIVA"], $pipelineLista[$key]);
        break;
      case "ANTIGA":
        array_push($listaResumida["ANTIGA"], $pipelineLista[$key]);
        break;
      case "CANCELADA":
        array_push($listaResumida["CANCELADA"], $pipelineLista[$key]);
        break;
      case "DECLINADA":
        array_push($listaResumida["DECLINADA"], $pipelineLista[$key]);
        break;
      case "FECHADA":
        array_push($listaResumida["FECHADA"], $pipelineLista[$key]);
        break;
      case "NOVA":
        array_push($listaResumida["NOVA"], $pipelineLista[$key]);
        break;
      case "PROPOSTA":
        array_push($listaResumida["PROPOSTA"], $pipelineLista[$key]);
        break;
      }
    }
    return $listaResumida;

  }

  public function geraFechamento($dataReferencia, $totalPipelineAtual, $diferencaEntreReceitas) {

    $totalFechamento = $this::somaTotalReceitasFechamento($totalPipelineAtual, $diferencaEntreReceitas);

    $situacao = $this->situacaoRepository->buscaPorSituacao("PROCESSADO");

    $fechamento = array(
      "DT_FECHAMENTO" => "2021/04/01",
      "DT_REFERENCIA" => $dataReferencia,
      "ID_TAB_SITUACAO" => $situacao[0]["ID_TAB_SITUACAO"],
    );

    // $result= $this->fechamentoRepository->salvar($fechamento);

    $fechamentoInserido = $this->fechamentoRepository->buscaUltimoFechamento();
    
    foreach ($totalPipelineAtual as $status => $receitas) {
      
      $situacaoPipeline = $this->situacaoRepository->buscaPorSituacao($status);         

      $tipoTotalLista = $this->tipoTotalRepository->buscaPorIdSituacao($situacaoPipeline[0]["ID_TAB_SITUACAO"]);
     
          
      $fechamentoItens["ID_TAB_FECHAMENTO"] = $fechamentoInserido[0]["ID_TAB_FECHAMENTO"];

      foreach ($tipoTotalLista as $indice => $tipoTotal) {
        $fechamentoItens["ID_TAB_TIPO_TOT"] = $tipoTotal["ID_TAB_TIPO_TOT"];
        if ($tipoTotal["ABREV"] == "TOT_REC_EST") {
          $fechamentoItens["TOTAL"] = $totalFechamento[$status]["REC_ESTIMADA"];
          $this->fechamentoItensRepository->salvar($fechamentoItens);
        }
        else if ($tipoTotal["ABREV"] == "TOT_REC_ESP") {
          $fechamentoItens["TOTAL"] = $totalFechamento[$status]["REC_ESPERADA"];
          $this->fechamentoItensRepository->salvar($fechamentoItens);
        }
        else if ($tipoTotal["ABREV"] == "TOT_REC_IMP") {
          $fechamentoItens["TOTAL"] = $totalFechamento[$status]["IMPACTO"];
          $this->fechamentoItensRepository->salvar($fechamentoItens);          
        }
      }
    }


    return $totalFechamento;

    // $fechamentoItens = $this->fechamentoItensRepository->salvar();

  }

  public function calculaDiferencaAtualAnterior($pipelineAtualListaResumida, $pipelineFatoLista) {
    $totaisReceitaPorStatus = array(
      "ATIVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "ANTIGA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "CANCELADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "DECLINADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "FECHADA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "NOVA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
      "PROPOSTA" => array("REC_ESTIMADA" => 0.00, "REC_ESPERADA" => 0, "IMPACTO" => 0),
    );
    foreach ($pipelineAtualListaResumida as $status => $pipelineAtualLista) {
      foreach ($pipelineAtualLista as $posicaoPipelineAtual => $pipelineAtual) {
        $idPipelineAtual = $pipelineAtual["ID_PIPELINE"];

        $pipelineReferenciaMesAnterior = $this::filtraPipelineFatoPorId($pipelineFatoLista, $idPipelineAtual);

        if ($pipelineReferenciaMesAnterior != "") {
          $totaisReceitaPorStatus[$status]["REC_ESTIMADA"] += $pipelineAtual["REC_ESTIMADA"] - $pipelineReferenciaMesAnterior["REC_ESTIMADA"];
        } else {
          $totaisReceitaPorStatus[$status]["REC_ESTIMADA"] += $pipelineAtual["REC_ESTIMADA"];
        }
      }
    }
    return $totaisReceitaPorStatus;
  }
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function mostrarTodos() {

  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function salvar($requestDados) {
    $pipelineListaTodos = $this->pipelineRepository->mostrarTodos();
    $pipelineFatoListaReferenciaAnterior = $this->pipelineFatoRepository->buscarPorData("2021/04/01");

    $teste = $this::copiaPipelineParaFato($pipelineListaTodos);

    // $result = $this->pipelineFatoRepository->salvar($teste);

    $listaDePipelinePorStatus = $this::filtraPipelinePorStatus($pipelineListaTodos);

    $diferencaEntreReceitas = $this::calculaDiferencaAtualAnterior($listaDePipelinePorStatus, $pipelineFatoListaReferenciaAnterior);

    $totalPipelineAtual = $this::somaTotalPipelineAtual($listaDePipelinePorStatus);

    $totalFechamento = $this::geraFechamento("2021/04/01", $totalPipelineAtual, $diferencaEntreReceitas);
    // $tipoTotal = $this->tipoTotalRepository->buscaPorIdSituacao($situacao[0]["ID_TAB_SITUACAO"]);
    // $totalFechamento
    // var_dump($resultado["ATIVA"]);
    // return response($pipelineFatoListaReferenciaAnterior, 200);
    return response($totalFechamento, 200);
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function alterar($requestDados) {

  }
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function remover($requestDados) {

  }
}