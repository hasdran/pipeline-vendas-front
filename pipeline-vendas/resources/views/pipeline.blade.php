@extends('layouts.index')

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
      <strong>Registro inserido com sucesso!</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @elseif($_GET["op"] == "error")
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Falha na tentativa de inserir registro!</strong>
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
<div class="form-row">
  <div class="col">
    <input id="myInput" type="text" placeholder="Buscar.." class="form-control form-control-sm">
  </div>
  <div class="col">
    <select id="show-results" class="form-control form-control-sm" onchange="myFunction()">
      <option>10</option>
      <option>20</option>
      <option>50</option>
      <option>100</option>
    </select>
  </div>
</div>

<div class="table-title text-center">
  <span>Planilha de Dados</span>
  <hr>
</div>
<table id="pipeline-table" class="table-responsive">
  <thead class="thead-orange">
    <tr class="head-table">
      <th scope="col"></th>
      <th scope="col"></th>
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
    </tr>
  </thead>
  <tbody id="form-add-pipeline">
    <tr>
      <td colspan="18"> <button id="btn_add" class="btn btn-default" type="button"><span> Adicionar</span> </button> </td>        
    </tr>
    @php
    $lin = 1;
    @endphp
    @foreach($pipeline as $result)
    <tr>
      @csrf
      <td title="Excluir" class="td-action-sm">
        <button id="btn-remover-<?=$lin?>" class="btn btn-default btn-action-sm">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
          </svg>
      </button>
      </td>
      <td title="Editar" class="td-action-sm">
        <button id="btn-editar-<?=$lin?>" class="btn btn-default">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg>
        </button>
      </td>
      <td>
        <input 
          id="id_<?= $lin?>" 
          value="<?= $result->ID_PIPELINE ?>" type="hidden">
        <input 
          id='inpt_lin<?= $lin?>_col1' 
          class="input_lst input-sm" 
          type="text" 
          value="<?= $result->CLIENTE ?>">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col2' 
          class="input_lst input-lg" 
          type="text" 
          value="<?= $result->PROJETO ?>">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col3' 
          class="val input_lst input-sm" 
          type="text" 
          value="<?= $result->VALOR ?> ">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col4' 
          class="vol input_lst input-sm" 
          type="text" 
          value="<?= $result->VOLUME ?>">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col5' 
          class="dt_ab input_lst input-lg" 
          type="text"
          value="<?= $result->DT_ABERTURA ?>" 
          maxlength="10">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col6' 
          class="dt_ini input_lst input-lg" 
          type="text"
          value="<?= $result->DT_INICIO ?>" 
          maxlength="10"></td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col7' 
          class="prz input_lst input-sm" 
          type="text" 
          value="<?= $result->PRAZO ?>">
      </td>
      <td>
        <input 
          id='inpt_lin<?= $lin?>_col8' 
          class="prob input_lst input-md" 
          type="text"
          value="<?= $result->PROBAB ?>">
      </td>
      <td>
        <input 
          id='situ_lin<?= $lin?>' 
          type="hidden" 
          value="<?= $result->ID_TAB_SITUACAO[0]->ID_TAB_SITUACAO?>">
        <input id='inpt_lin<?= $lin?>_col9' 
          class="sit input_lst input-sm" 
          type="text"
          value="<?= $result->ID_TAB_SITUACAO[0]->SITUACAO ?>">
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col10' 
        class="dt_enc input_lst input-lg" 
        type="text"
        value="<?= $result->DT_ENCERR ?>" 
        maxlength="10">
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col11' 
        class="tmpo input_lst input-sm" 
        type="text"
        value="<?= $result->TEMPO ?>">
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col12' 
          class="rec_est input_lst input-md" 
          type="text"
          value="<?= $result->REC_ESTIMADA ?>" 
          disabled>
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col13' 
          class="rec_esp input_lst input-md" 
          type="text"
          value="<?= $result->REC_ESPERADA ?>" 
          disabled>
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col14' 
          class="impcto input_lst input-md" 
          type="text"
          value="<?= $result->IMPACTO ?>" 
          disabled>
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col15' 
          class="dur input_lst input-sm  " 
          type="text"
          value="<?= $result->DURACAO ?>" 
          disabled>
      </td>
      <td>
        <input id='inpt_lin<?= $lin?>_col16' 
          class="mud input_lst input-sm" 
          type="text"
          value="<?= $result->MUDANCA_STS ?>" 
          disabled>
      </td>
    </tr>
    @php
    $lin++;
    @endphp
    @endforeach
    <input type="hidden" value="<?= $lin-1 ?>" id="qtdeLinhas">

  </tbody>
</table>
<nav aria-label="...">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item active" aria-current="page">
      <span class="page-link">
        2
        <span class="sr-only">(current)</span>
      </span>
    </li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
  </ul>
</nav>
@endsection

@section('script')

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/pipeline.js') }}"></script>

@endsection