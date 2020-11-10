@extends('index')

@section('css-props')
  <link rel="stylesheet" href="{{URL::asset('css/pipeline.css')}}" />
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
  @endif

@endif

 <table class="table-responsive">
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
        <th scope="col"></th>  
        <th scope="col"></th>                            
      </tr>
    </thead>
    <tbody>

      @php
        $lin = 1;
      @endphp
      @foreach($pipeline as $result)

         <tr>
          <td><input id='inpt_lin<?= $lin?>_col1' class="input_lst input-sm" type="text" value="<?= $result->CLIENTE ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col2' class="input_lst input-lg" type="text" value="<?= $result->PROJETO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col3' class="val input_lst input-sm" type="text" value="<?= $result->VALOR ?> "></td>
          <td><input id='inpt_lin<?= $lin?>_col4' class="vol input_lst input-sm" type="text" value="<?= $result->VOLUME ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col5' class="dt_ab input_lst input-lg" type="text" value="<?= $result->DT_ABERTURA ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col6' class="dt_ini input_lst input-lg" type="text" value="<?= $result->DT_INICIO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col7' class="prz input_lst input-sm" type="text" value="<?= $result->PRAZO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col8' class="prob input_lst input-md" type="text" value="<?= $result->PROBAB ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col9' class="sit input_lst input-sm" type="text" value="<?= $result->ID_SITUACAO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col10' class="dt_enc input_lst input-lg" type="text" value="<?= $result->DT_ENCERR ?>"></td>          
          <td><input id='inpt_lin<?= $lin?>_col12' class="tmpo input_lst input-sm" type="text" value="<?= $result->TEMPO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col11' class="rec_est input_lst input-md" type="text" value="<?= $result->REC_ESTIMADA ?>"></td>          
          <td><input id='inpt_lin<?= $lin?>_col13' class="rec_esp input_lst input-md" type="text" value="<?= $result->REC_ESPERADA ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col14' class="impcto input_lst input-md" type="text" value="<?= $result->IMPACTO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col15' class="dur input_lst input-sm  " type="text" value="Curto prazo"></td> 
          <td><input id='inpt_lin<?= $lin?>_col16' class="mud input_lst input-sm" type="text" value="<?= $result->MUDANCA_STS ?>"></td> 
          <td><a href="">a</a></td>
          <td><a href="">a</a></td>
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
            <td><input class="form-add input-lg" type="date" data-inputmask="'alias': 'date'" name="dt_abertura"></td>
            <td><input class="form-add input-lg" type="date" name="dt_inicio_op"></td>
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
            <td><input id="inpt_dur" class="form-add input-sm" type="text" name="duracao"></td>
            <td><input class="form-add input-sm" type="text" name="mudanca_sts"></td>
          </tr>  
        </form>
      @endif
    </tbody>      
  </table>

  <div>
    @if(isset($op) and $op == "create")
      <a href="."><button id="btn_cancel" class="btn_secondary"type="submit">Cancelar</button></a>
      <button id="btn_save" class="btn_primary" type="submit">Salvar</button>
    @else
      <button id="btn_add" class="btn_primary" type="submit">Inserir</button>
    @endif
  </div>  
@endsection

@section('script')
  <script type="text/javascript" src="{{ URL::asset('js/pipeline.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
@endsection