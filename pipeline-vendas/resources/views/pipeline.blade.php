@extends('index')

@section('css-props')
  <link rel="stylesheet" href="{{URL::asset('css/pipeline.css')}}" />
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"> -->
@endsection

@section('sidebar')
    @parent
@endsection

@section('content')
@if(isset($_GET["op"]))
  @if($_GET["op"] == "sucess")
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
      <strong>Registro inserido!</strong> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @elseif($_GET["op"] == "error")
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Erro ao tentar inserir registro!</strong> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @elseif($_GET["op"] == "updated")
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Alteração Realizada!</strong> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

@endif

 <table id="pipeline-table"class="table-responsive">
    <thead>
      <tr>
        <th scope="col">Cliente</th>
        <th scope="col">Projeto</th>
        <th scope="col">Valor/m³</th>
        <th scope="col">Volume (m³/mês)</th>
        <th scope="col">Data Abertura</th>
        <th scope="col">Data Início Operação</th>
        <th scope="col">Prazo do Contrato</th>    
        <th scope="col">Probabilidade</th>
        <th scope="col">Situação</th>
        <th scope="col">Data Encerramento</th>
        <th scope="col">Tempo 2020 (meses)</th> 
        <th scope="col">Receita Estimada</th>    
        <th scope="col">Receita Esperada</th>
        <th scope="col">Impacto 2020</th>
        <th scope="col">Duração</th>
        <th scope="col">Mudança Status</th>
        @if(!isset($op) ||   $op != "update") 
        <th scope="col"></th>  
        <th scope="col"></th>
        @endif                            
      </tr>
    </thead>
    <tbody>

      @php
        $lin = 1;
      @endphp
      @foreach($pipeline as $result)
         <tr>     
          
          @csrf  
          <td>
          <!-- <form id="form-"action="{{action('PipelineController@delete')}}" method="post">  -->
            <input id="id_<?= $lin?>" value="<?= $result->ID_PIPELINE ?>" type="hidden">             
            <input id='inpt_lin<?= $lin?>_col1' class="input_lst input-sm" type="text" value="<?= $result->CLIENTE ?>">
          </td>
          <td><input id='inpt_lin<?= $lin?>_col2' class="input_lst input-lg" type="text" value="<?= $result->PROJETO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col3' class="val input_lst input-sm" type="text" value="<?= $result->VALOR ?> "></td>
          <td><input id='inpt_lin<?= $lin?>_col4' class="vol input_lst input-sm" type="text" value="<?= $result->VOLUME ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col5' class="dt_ab input_lst input-lg" type="text" value="<?= $result->DT_ABERTURA ?>" maxlength="10"></td>
          <td><input id='inpt_lin<?= $lin?>_col6' class="dt_ini input_lst input-lg" type="text" value="<?= $result->DT_INICIO ?>" maxlength="10"></td>
          <td><input id='inpt_lin<?= $lin?>_col7' class="prz input_lst input-sm" type="text" value="<?= $result->PRAZO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col8' class="prob input_lst input-md" type="text" value="<?= $result->PROBAB ?>"></td>
          <td>
            <input id='situ_lin<?= $lin?>'type="hidden" value="<?= $result->ID_SITUACAO[0]->ID_SITUACAO?>">
            <input id='inpt_lin<?= $lin?>_col9' class="sit input_lst input-sm" type="text" value="<?= $result->ID_SITUACAO[0]->SITUACAO ?>">
          </td>
          <td><input id='inpt_lin<?= $lin?>_col10' class="dt_enc input_lst input-lg" type="text" value="<?= $result->DT_ENCERR ?>" maxlength="10"></td>          
          <td><input id='inpt_lin<?= $lin?>_col11' class="tmpo input_lst input-sm" type="text" value="<?= $result->TEMPO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col12' class="rec_est input_lst input-md" type="text" value="<?= $result->REC_ESTIMADA ?>" disabled></td>          
          <td><input id='inpt_lin<?= $lin?>_col13' class="rec_esp input_lst input-md" type="text" value="<?= $result->REC_ESPERADA ?>"disabled></td>
          <td><input id='inpt_lin<?= $lin?>_col14' class="impcto input_lst input-md" type="text" value="<?= $result->IMPACTO ?>" disabled></td>
          <td><input id='inpt_lin<?= $lin?>_col15' class="dur input_lst input-sm  " type="text" value="<?= $result->DURACAO ?>" disabled></td> 
          <td><input id='inpt_lin<?= $lin?>_col16' class="mud input_lst input-sm" type="text" value="<?= $result->MUDANCA_STS ?>" disabled></td> 
                    
          <td title="Editar">
              <button id="btn-editar-<?= $lin?>" class="btn btn-default"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
              </svg></button>
          </td>
          <td title="Excluir">
              <button id="btn-remover-<?= $lin?>" class="btn btn-default" style="color: red"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
              </svg></button>
          </td>
          
        </tr>      
        @php
          $lin++;
        @endphp   
      @endforeach
          <input type="hidden" value="<?= $lin-1 ?>" id="qtdeLinhas">
      @if(isset($op) and $op == "create")
        <form id="pipeline_form"action="{{action('PipelineController@create')}}" method="post">
        @csrf
          <tr>            
            <td><input class="form-add input-md" type="text" name="cliente"></td>
            <td><input class="form-add input-lg" type="text" name="projeto"></td>
            <td><input id="inpt_val"class="form-add input-sm" type="text" name="valor_m3"></td>          
            <td><input id="inpt_vol"class="form-add input-sm" type="text" name="volume_m3"></td>            
            <td><input class="form-add input-lg" type="date" name="dt_abertura" ></td>
            <td><input class="form-add input-lg" type="date" name="dt_inicio_op" ></td>
            <td><input id="inpt_prz"class="form-add input-sm" type="text" name="prazo_contrato"></td>
            <td>
              <select id="inpt_prob" class="form-add input-md" name="probabilidade">
                <option value="10">10%</option>
                <option value="30">30%</option>
                <option value="50">50%</option>
                <option value="80">80%</option>
                <option value="100">100%</option>
              </select>
            </td>
            <td>
              <!-- <input class="form-control" type="text" name="situacao"> -->
              <select class="form-add input-sm" name="situacao">                
                @foreach($situacoes_lst as $result)
                  <option value="<?= $result->ID_SITUACAO ?>"><?= $result->SITUACAO ?></option>
                @endforeach
              </select>              
            </td>
            <td><input class="form-add input-lg" type="date" name="dt_encerramento"></td>
            <td><input id="inpt_tempo" class="form-add input-sm" type="text" name="tempo"></td>
            <td><input id="inpt_rec_est" class="form-add input-lg" type="text" name="receita_est" disabled></td>
            <td><input id="inpt_rec_esp" class="form-add input-lg" type="text" name="receita_esp" disabled></td>
            <td><input id="inpt_impacto" class="form-add input-lg" type="text" name="impacto" disabled></td>
            <td><input id="inpt_dur" class="form-add input-sm" type="text" name="duracao" disabled></td>
            <td><input class="form-add input-sm" type="text" name="mudanca_sts" disabled></td>
          </tr>  
        </form>
      @endif
    </tbody>      
  </table>

  <div>
    @if(isset($op) and $op == "create")
      <div class="row">
        <div class="col-sm-2"><button id="btn_cancel" class="btn btn-warning btn-block bt-action" type="button"><a href="." class="text-decoration-none">Cancelar</a></button></div>
        <div class="col-sm-2"><button id="btn_save" class="btn btn-success btn-block bt-action"type="button">Salvar</button></div>        
      </div>
    @elseif(isset($op) and $op == "update")  
      <div class="row">
        <div class="col-sm-2"><button id="btn_cancel" class="btn btn-warning btn-block bt-action" type="button"><a href="." class="text-decoration-none">Cancelar</a></button></div>
        <div class="col-sm-2"><button id="btn_update" class="btn btn-success btn-block bt-action"type="button">Salvar</button></div>        
      </div>
    @else
      <button id="btn_add" class="btn btn-success bt-action" type="button">Inserir</button>    
    @endif
  </div>  
@endsection

@section('script')
  <script type="text/javascript" src="{{ URL::asset('js/pipeline.js') }}"></script>
 
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
  <!-- <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
  

@endsection