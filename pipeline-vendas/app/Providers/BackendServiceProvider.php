<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider {
  public function register() {
    $this->app->bind(
      'App\Repositories\PipelineFatoRepositoryInterface',
      'App\Repositories\PipelineFatoRepository',
    );

    $this->app->bind(
      'App\Repositories\PipelineRepositoryInterface',
      'App\Repositories\PipelineRepository',
    );

    $this->app->bind(
      'App\Repositories\SituacaoRepositoryInterface',
      'App\Repositories\SituacaoRepository',
    );
    $this->app->bind(
      'App\Repositories\FechamentoRepositoryInterface',
      'App\Repositories\FechamentoRepository',
    );
    $this->app->bind(
      'App\Repositories\TipoTotalRepositoryInterface',
      'App\Repositories\TipoTotalRepository',
    );
    $this->app->bind(
      'App\Repositories\FechamentoItensRepositoryInterface',
      'App\Repositories\FechamentoItensRepository',
    );

  }
}