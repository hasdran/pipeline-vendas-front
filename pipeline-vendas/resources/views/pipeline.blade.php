@extends('index')

@section('sidebar')
    @parent
@endsection

@section('content')
<form action="" method="get">
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
      <tr>
        <th scope="row">a</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td>Larry</td>
        <td>the Bird</td>
        <td>@twitter</td>
      </tr>
    </tbody>      
  </table>
  <button type="submit">add</button>
</form>

@endsection

@section('script')
  <script type="text/javascript" src="{{ URL::asset('js/pipeline.js') }}"></script>
@endsection