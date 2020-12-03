$(document).ready(function () {
    $('#pipeline-table').DataTable({
        "language": {
            "sEmptyTable": "Não foi encontrado nenhum registo",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sLengthMenu": "Mostrar _MENU_ registos",
            "sZeroRecords": "Nenhum resultado encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registos",
            "sInfoEmpty": "Mostrando de 0 até 0 de 0 registos",
            "sInfoFiltered": "(filtrado de _MAX_ registos no total)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Página anterior",
                "sNext": "Próxima página",
                "sLast": "Última Página"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    });
});

/*****
 * Calcula a quantidade de cards de fechamentos existentes
 * @parms
 */

$(`#btn-card-detalhes`).click(() => {
    let dt = $(`#dt-fechamento`).val();
    getDetalhesFechamento(dt);
});


/*****
 * Calcula a quantidade de linhas existentes na tabela Historico de fechamento
 * @parms
 */
function getQtdeLinhasTab() {
    return $("#tb-hist-linhas").val();
}

for (let lin = 1; lin < getQtdeLinhasTab(); lin++) {
    $(`#btn-tb-detalhes-${lin}`).click(() => {
        let dt = $(`#id-fechamento-hist-${lin}`).val();
        getDetalhesFato(dt);
    });
}
/*****
 * Carrega a tabela de historico detalhado para o card fechamento escolhido
 * @parms dtFechamento (data de referencia do fechamento selecionado)
 */
function getDetalhesFechamento(dtFechamento) {
    let token = $('[name="_token"]').val();
    let dt_fechamento = dtFechamento;
    $.ajax({
        headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: "/fechamento/detalhes",
        dataType: 'JSON',
        data: {
            "_token": token,
            "dt_fechamento": dt_fechamento,
        },
        success: (result) => {
            console.log(result)
            $("#tab-hist-fechamento").empty();

            $("#tab-hist-fechamento").prepend(`
            <div class="table table-responsive">
                <table id="pipeline-table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Projeto</th>
                    <th scope="col">Valor/m³</th>
                    <th scope="col">Volume (m³/mês)</th>
                    <th scope="col">Data Abertura</th>
                    <th scope="col">Data Início Operação</th>
                    <th scope="col">Prazo do Contrato</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Data Encerramento</th>
                    <th scope="col">Tempo(meses)</th>
                    <th scope="col">Receita Estimada</th>
                    <th scope="col">Receita Esperada</th>
                    <th scope="col">Impacto</th>
                    <th scope="col">Duração</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="body-fechamento">
        `)
            result.map(element => {
                $("#body-fechamento").prepend(`<tr id="tr-fechamento">`)
                $("#tr-fechamento").append(`
                <td>${element.CLIENTE}</td>
                <td>${element.PROJETO}</td>
                <td>R$${element.VALOR}</td>
                <td>${element.VOLUME}</td>
                <td>${element.DT_ABERTURA}</td>
                <td>${element.DT_INICIO}</td>
                <td>${element.PRAZO}</td>
                <td>${element.ID_SITUACAO[0].SITUACAO}</td>
                <td>${element.DT_ENCERRAMENTO}</td>
                <td>${element.TEMPO}</td>
                <td>${element.REC_ESTIMADA}</td>
                <td>${element.REC_ESPERADA}</td>
                <td>${element.IMPACTO}</td>
                <td>${element.DURACAO}</td>
                <td>${element.MUDANCA_STS}</td>
            `)
                $("#body-fechamento").append(`</tr>`)
            })
            $("#tab-hist-fechamento").append(`
                </tbody>
                </table>
            </div>        
        `);
        },
        error: (err) => {

        }
    });
}

/*****
 * Carrega a tabela de historico detalhado para a tabela de Historico de fechamentos
 * @parms dtFechamento (data de referencia do fechamento selecionado)
 */
function getDetalhesFato(dtFechamento) {
    let token = $('[name="_token"]').val();
    let dt_fechamento = dtFechamento;
    $.ajax({
        headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: "/fechamento/detalhes-fato",
        dataType: 'JSON',
        data: {
            "_token": token,
            "dt_fechamento": dt_fechamento,
        },
        success: (result) => {
            console.log(result)
            $("#tab-hist-fechamento").empty();

            $("#tab-hist-fechamento").prepend(`
            <div class="table table-responsive">
                <table id="pipeline-table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Projeto</th>
                    <th scope="col">Valor/m³</th>
                    <th scope="col">Volume (m³/mês)</th>
                    <th scope="col">Data Abertura</th>
                    <th scope="col">Data Início Operação</th>
                    <th scope="col">Prazo do Contrato</th>
                    <th scope="col">Situação</th>
                    <th scope="col">Data Encerramento</th>
                    <th scope="col">Tempo(meses)</th>
                    <th scope="col">Receita Estimada</th>
                    <th scope="col">Receita Esperada</th>
                    <th scope="col">Impacto</th>
                    <th scope="col">Duração</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="body-fechamento">
        `)
            result.map(element => {
                $("#body-fechamento").prepend(`<tr id="tr-fechamento">`)
                $("#tr-fechamento").append(`
                <td>${element.CLIENTE}</td>
                <td>${element.PROJETO}</td>
                <td>R$${element.VALOR}</td>
                <td>${element.VOLUME}</td>
                <td>${element.DT_ABERTURA}</td>
                <td>${element.DT_INICIO}</td>
                <td>${element.PRAZO}</td>
                <td>${element.ID_SITUACAO[0].SITUACAO}</td>
                <td>${element.DT_ENCERR}</td>
                <td>${element.TEMPO}</td>
                <td>${element.REC_ESTIMADA}</td>
                <td>${element.REC_ESPERADA}</td>
                <td>${element.IMPACTO}</td>
                <td>${element.DURACAO}</td>
                <td>${element.MUDANCA_STS}</td>
            `)
                $("#body-fechamento").append(`</tr>`)
            })
            $("#tab-hist-fechamento").append(`
                </tbody>
                </table>
            </div>        
        `);
        },
        error: (err) => {

        }
    });
}


    $("#btn-alert-confirmar").click(()=>{
        let dtFechamento = $(`#dt-fechamento`).val();
        $("#btn-alert-confirmar").prop('disabled', true);
        let token = $('[name="_token"]').val();
 
        $.ajax({
             headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
             type: "POST",
             url: "/fechamento/confirmar-fechamento/",
             dataType: 'JSON',
             data: {
                 "_token": token,
                 "dt_fechamento": dtFechamento
             },
             success: (result) => { 
                 console.log(result)
                 if(result.status == "sucess"){
                     window.location.reload();
                 }
             },
     
             error: (err) => { }
         });      
    }); 
