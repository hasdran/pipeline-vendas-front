<div class="table-responsive">
  <table id="pipeline-table">
    <thead>
      <tr>
        <th scope="col">Situação</th>
        <th scope="col">Receita Estimada</th>
        <th scope="col">Receita Esperada</th>
        <th scope="col">Impacto</th>
      </tr>
    </thead>
    <tbody>
      @foreach($fechamento as $result)
      <tr>
        <td><?=$result->ID_SITUACAO ?></td>
        <td>R$<?= $result->TOT_REC_EST?></td>
        <td>R$<?= $result->TOT_REC_ESP?></td>
        <td>R$<?=$result->TOT_IMPACTO?></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>