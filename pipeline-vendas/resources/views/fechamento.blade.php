@extends('index')

@section('css-props')
<link rel="stylesheet" href="{{URL::asset('css/fechamento.css')}}" />
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" /> -->
@endsection

@section('sidebar')
@parent
@endsection

@section('content')

@csrf
@if($fechamento_aberto != null)
<div class="card mb-4">
  <div class="card-header">
    <i class="fas fa-table mr-1"></i>
    Fechamento
  </div>
  <div class="card-body">    
    <div class="card-view" id="card-fechamento">
      <input 
        id="dt-fechamento" 
        type="hidden" 
        value="{{ $fechamento_aberto[0]->DT_REFERENCIA['mes'] }}/{{ $fechamento_aberto[0]->DT_REFERENCIA['ano'] }}">
      <div class="content-card-view"><b>Data:</b> <?=$fechamento_aberto[0]->DT_FECHAMENTO?></div>
      <div class="content-card-view">
        <b>Data de Referência:</b>
        <?=$fechamento_aberto[0]->DT_REFERENCIA["mes"]?>/<?=$fechamento_aberto[0]->DT_REFERENCIA["ano"]?>
        </div>
      <div class="content-card-view"><b>Período:</b> <?=$fechamento_aberto[0]->PERIODO?></div>
      <div class="btn-card-view">
        <button class="btn btn-primary btn-block bt-action" id="btn-card-detalhes">Detalhes</button>
        @php 
          $dt_atual = date('Y/m/d');
          list($ano_atual, $mes_atual, $dia_atual) = explode("/", $dt_atual);
        @endphp
        @if($fechamento_aberto[0]->DT_REFERENCIA["mes"] == $mes_atual)
          <button class="btn btn-success btn-block bt-action" id="btn-card-confirmar" data-toggle="modal" data-target="#staticBackdrop">Confirmar</button>
        @endif        
      </div>
    </div>     
  </div>
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Alerta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Você deseja confirmar o fechamento referente à <?=$fechamento_aberto[0]->DT_REFERENCIA["mes"]?>/<?=$fechamento_aberto[0]->DT_REFERENCIA["ano"]?>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="btn-alert-confirmar" >Confirmar</button>
      </div>
    </div>
  </div>
</div>
</div>
@endif
<div class="card mb-4">
  <div class="card-header">
    <i class="fas fa-table mr-1"></i>
    Histórico de Fechamentos
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="pipeline-table" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Data</th>
            <th scope="col">Referência</th>
            <th scope="col">Situação</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @php $lin=1 @endphp
          @foreach($fechamentos as $result)
          <tr>
            <input id="id-fechamento-hist-<?=$lin?>" type="hidden" value="<?=$result->DT_REFERENCIA?>">
            <td><?=$result->DT_FECHAMENTO?></td>
            <td><?=$result->DT_REFERENCIA?></td>
            <td><?=$result->ID_SITUACAO[0]->SITUACAO?></td>
            <td><button id="btn-tb-detalhes-<?=$lin?>">Detalhes</button> <button>Resumo</button></td>
          </tr>
          @php $lin++ @endphp
          @endforeach
          <input type="hidden" id="tb-hist-linhas" value="<?=$lin?>">
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="card mb-4">
  <div class="card-header">
    <i class="fas fa-table mr-1"></i>
    Resumo de Fechamento
  </div>
  <div class="card-body" id="tab-hist-fechamento">
  </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/fechamento.js') }}"></script>
@endsection