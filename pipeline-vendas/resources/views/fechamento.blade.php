@extends('index')

@section('css-props')
  <link rel="stylesheet" href="{{URL::asset('css/fechamento.css')}}" />
@endsection

@section('sidebar')
  @parent
@endsection

@section('content')

  @csrf

  @if($fechamento_aberto != null)

    @php
      $id = $fechamento_aberto[0]->ID_TAB_FECHAMENTO;
      $mes = $fechamento_aberto[0]->DT_REFERENCIA["mes"];
      $ano = $fechamento_aberto[0]->DT_REFERENCIA["ano"];
      $dt_atual = date('Y/m/d');
      list($ano_atual, $mes_atual, $dia_atual) = explode("/", $dt_atual);
    @endphp

    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Fechamento
      </div>
      <div class="card-body">
        <div class="card-view" id="card-fechamento">
          
          <div id="list-card-view">

            <input id="id-fechamento" type="hidden" value="{{$id}}">
            <input id="dt-fechamento" type="hidden" value="{{$mes}}/{{$ano}}">
            <ul>
              <li class="content-card-view"><b>Data:</b> {{$dia_atual}}/{{$mes_atual}}/{{$ano_atual}}</li>
              <li class="content-card-view"><b>Data de Referência:</b>{{$mes}}/{{$ano}}</li>
              <li class="content-card-view"><b>Período:</b> <?=$fechamento_aberto[0]->PERIODO?></li>
            </ul> 
          </div>

          <div class="btn-card-view">
            <button class="btn btn-primary btn-block bt-action"  id="btn-card-detalhes">Detalhes</button>
            <button class="btn btn-primary btn-block bt-action" id="btn-card-resumo">Resumo</button>
            @php                            
              $ano_referencia = $mes_atual == 1 ? $ano_atual -1 : $ano_atual;
              $mes_referencia = $mes_atual == 1 ? $mes_atual + 11 : $mes_atual - 1;              
            @endphp
            @if($mes == $mes_referencia && $ano == $ano_referencia)
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
            Você deseja confirmar o fechamento referente à {{$mes}}/{{$ano}}?
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
            @if($fechamentos != null)
            @foreach($fechamentos as $result)
              @php
                $mes_ref = $result->DT_REFERENCIA["mes"];                
                $ano_ref = $result->DT_REFERENCIA["ano"];
              @endphp   
              <tr class="tr-stripped">
                <input id="id-fechamento-hist-<?=$lin?>" type="hidden" value="<?=$result->ID_TAB_FECHAMENTO?>">
                <input id="dt-fechamento-hist-<?=$lin?>" type="hidden" value="{{$mes_ref}}/{{$ano_ref}}">
                <td><?=$result->DT_FECHAMENTO?></td>
                <td>{{$mes_ref}}/{{$ano_ref}}</td>
                <td><?=$result->ID_TAB_SITUACAO[0]->SITUACAO?></td>
                <td>
                  <button id="btn-tb-detalhes-<?=$lin?>">Detalhes</button> 
                  <button id="btn-tb-resumo-<?=$lin?>">Resumo</button>
                    @if ($result->ID_TAB_SITUACAO[0]->SITUACAO == "Cancelado")
                      <button id="btn-tb-confirmar-<?=$lin?>">Confirmar</button>
                    @else
                      @if ($mes_atual == 1)
                        @if ($mes_ref ==  $mes_atual + 11 && $ano_ref == $ano_atual - 1)
                          <button id="btn-tb-cancelar-<?=$lin?>">Resumo</button>
                        @endif
                      @else
                        @if ($mes_ref ==  $mes_atual - 1 && $ano_ref == $ano_atual)
                          <button id="btn-tb-cancelar-<?=$lin?>">Cancelar</button>
                        @endif
                      @endif                        
                    @endif
                </td>
              </tr>
              @php $lin++ @endphp
            @endforeach
            @endif
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