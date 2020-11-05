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
      <strong>Holy guacamole!</strong> You should check in on some of those fields below.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @elseif($_GET["op"] == "error")
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Holy guacamole!</strong> You should check in on some of those fields below.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

@endif

  <table>
    <thead>
      <tr>
        <th scope="col">Cliente</th>
        <th scope="col">Projeto</th>
        <th scope="col">Valor/m³</th>
        <th scope="col">Volume(m³/mês)</th>
        <th scope="col">Data Abertura</th>
        <th scope="col">Data Início Operação</th>
        <th scope="col">Prazo do Contrato</th>    
        <th scope="col">Probabilidade</th>
        <th scope="col">Situação</th>
        <th scope="col">Data Encerramento</th>
        <th scope="col">Tempo 2020  (meses)</th> 
        <th scope="col">Receita Estimada</th>    
        <th scope="col">Receita Esperada</th>
        <th scope="col">Impacto 2020</th>
        <th scope="col">Duração</th>
        <th scope="col">Mudança Status</th>                          
      </tr>
    </thead>
    <tbody>
      @php
        $lin = 1;
      @endphp
      @foreach($pipeline as $result)
        <tr>
          <td><input id='inpt_lin<?= $lin?>_col1' class="input_lst" type="text" value="<?= $result->CLIENTE ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col2' class="input_lst" type="text" value="<?= $result->PROJETO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col3' class="input_lst" type="text" value="<?= $result->VALOR ?> "></td>
          <td><input id='inpt_lin<?= $lin?>_col4' class="input_lst" type="text" value="<?= $result->VOLUME ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col5' class="input_lst" type="text" value="<?= $result->DT_ABERTURA ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col6' class="input_lst" type="text" value="<?= $result->DT_INICIO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col7' class="input_lst" type="text" value="<?= $result->PRAZO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col8' class="input_lst" type="text" value="<?= $result->PROBAB ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col9' class="input_lst" type="text" value="<?= $result->ID_SITUACAO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col10' class="input_lst" type="text" value="<?= $result->DT_ENCERR ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col11' class="input_lst" type="text" value="<?= $result->REC_ESTIMADA ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col12' class="input_lst" type="text" value="<?= $result->TEMPO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col13' class="input_lst" type="text" value="<?= $result->REC_ESPERADA ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col14' class="input_lst" type="text" value="<?= $result->IMPACTO ?>"></td>
          <td><input id='inpt_lin<?= $lin?>_col15' class="input_lst" type="text" value="<?= $result->DT_ENCERR ?>"></td>
        </tr>     
        @php
          $lin++;
        @endphp   
      @endforeach

      @if(isset($op) and $op == "create")
        <form id="pipeline_form"action="{{action('PipelineController@create')}}" method="post">
        @csrf
          <tr>            
            <td><input class="input_add" type="text" name="cliente" ></td>
            <td><input class="input_add" type="text" name="projeto"></td>
            <td><input class="input_add" type="number" name="valor_m3"></td>
            <td><input class="input_add" type="number" name="volume_m3"></td>
            <td><input class="input_add" type="date" name="dt_abertura"></td>
            <td><input class="input_add" type="date" name="dt_inicio_op"></td>
            <td><input class="input_add" type="number" name="prazo_contrato"></td>
            <td><input class="input_add" type="number" name="probabilidade"></td>
            <td><input class="input_add" type="text" name="situacao"></td>
            <td><input class="input_add" type="date" name="dt_encerramento"></td>
            <td><input class="input_add" type="number" name="tempo"></td>
            <td><input class="input_add" type="text" name="receita_est"></td>
            <td><input class="input_add" type="text" name="receita_esp"></td>
            <td><input class="input_add" type="text" name="impacto"></td>
            <td><input class="input_add" type="date" name="duracao"></td>
          </tr>  
        </form>
      @endif
    </tbody>      
  </table>
  

  <button id="btn_add" type="submit">add</button>

  @if(isset($op) and $op == "create")
    <button id="btn_save" type="submit">save</button>
  @endif

@endsection

@section('script')
  <script type="text/javascript" src="{{ URL::asset('js/pipeline.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
@endsection