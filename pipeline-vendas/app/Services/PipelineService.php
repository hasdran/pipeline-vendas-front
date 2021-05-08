<?php

namespace App\Services;
use App\Repositories\PipelineRepositoryInterface;

use App\Services\PipelineConverter;
use App\Pipeline;

class PipelineService {

  protected $pipelineRepository;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(PipelineRepositoryInterface $pipelineRepository) {

    $this->pipelineRepository = $pipelineRepository;
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

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function mostrarTodos() {    
    echo csrf_token();
    $pipelineListaTodos = $this->pipelineRepository->mostrarTodos();
    return response($pipelineListaTodos, 200);
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function salvar($requestDados) {
    $formularioEntrada = $this::removeTokenDaRequest($requestDados->all());

    $validacao = Pipeline::validaPipelineEntradas($formularioEntrada);

    if ($validacao->fails()) {
      return response($validacao->messages(), 400);
    }

    $formularioEntrada["DURACAO"] = Pipeline::calculaPrazo($formularioEntrada["PRAZO"]);

    $this->pipelineRepository->salvar($formularioEntrada);

    return response(["Cadastro efetuado com sucesso!"], 200);
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function alterar($requestDados) {

    $idPipeline = $requestDados->id;

    $campos = $this::removeTokenDaRequest($requestDados->all());

    $validacao = Pipeline::validaPipelineEntradas($campos, $idPipeline);

    if ($validacao->fails()) {
      return response($validacao->messages(), 400);
    }

    $campos["DURACAO"] = Pipeline::calculaPrazo($campos["PRAZO"]);

    $this->pipelineRepository->alterar($idPipeline, $campos);

    return response($campos, 200);
  }
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function remover($requestDados) {
    $idPipeline = $requestDados->id;

    $pipeline = new Pipeline();

    $validacao = $pipeline->validaEntradaId($idPipeline);

    if ($validacao->fails()) {
      return response($validacao->messages(), 400);
    }

    $removido= $this->pipelineRepository->remover($idPipeline);

    if ($removido == 0) {
      return response("", 204);
    }else {
      return response(["Registro removido com sucesso."], 200);
    }   
  }
}