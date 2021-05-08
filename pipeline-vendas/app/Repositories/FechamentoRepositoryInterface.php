<?php

namespace App\Repositories;

interface FechamentoRepositoryInterface {

  public function buscaUltimoFechamento();

  public function salvar($fechamento);
}