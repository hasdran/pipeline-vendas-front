let dtFechamentoCard = $(`#dt-fechamento`).val();
let idFechamentoCard = $(`#id-fechamento`).val();

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
        }, "order": [[1, "desc"]]
    });
});

/*****
 * Calcula a quantidade de cards de fechamentos existentes
 * @parms
 */
$(`#btn-card-detalhes`).click(() => {
    getDetalhesFechamento(dtFechamentoCard);
});

$('#btn-card-resumo').click(function () {
    const url = "/fechamento/resumo";
    getResumoFechamento(idFechamentoCard, url);
});

$("#btn-alert-confirmar").click(() => {
    $("#btn-alert-confirmar").prop("disabled", true);
    confirmaFechamento(idFechamentoCard);
});

for (let lin = 1; lin < getQtdeLinhasTab(); lin++) {
    const idFechamento = $(`#id-fechamento-hist-${lin}`).val();
    $(`#btn-tb-resumo-${lin}`).click(() => {
        const url = "/fechamento/resumo-fato";
        getResumoFechamento(idFechamento, url);
    });

    $(`#btn-tb-detalhes-${lin}`).click(() => {
        let dt = $(`#dt-fechamento-hist-${lin}`).val();
        getDetalhesFato(dt);
    });

    $(`#btn-tb-cancelar-${lin}`).click(() => {
        cancelaFechamento(idFechamento);
    });

    $(`#btn-tb-confirmar-${lin}`).click(() => {
        confirmaFechamento(idFechamento);
    });
}

/*****
 * Calcula a quantidade de linhas existentes na tabela Historico de fechamento
 * @parms
 */
function getQtdeLinhasTab() {
    return $("#tb-hist-linhas").val();
}

/*****
 * Carrega a tabela de historico detalhado para o card fechamento escolhido
 * @parms dtFechamento (data de referencia do fechamento selecionado)
 */
function getDetalhesFechamento(dtFechamento) {

    const token = $('[name="_token"]').val();
    const dt_fechamento = dtFechamento;
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

            $("#tab-hist-fechamento").empty();
            if (result.length > 0) {
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
                    element.DT_ENCERRAMENTO == null ? element.DT_ENCERRAMENTO = "" : ""

                    $("#body-fechamento").prepend(`<tr id="tr-fechamento">`)
                    $("#tr-fechamento").append(`
                        <td>${element.CLIENTE}</td>
                        <td>${element.PROJETO}</td>
                        <td>R$${element.VALOR}</td>
                        <td>${element.VOLUME}</td>
                        <td>${element.DT_ABERTURA}</td>
                        <td>${element.DT_INICIO}</td>
                        <td>${element.PRAZO}</td>
                        <td>${element.ID_TAB_SITUACAO[0].SITUACAO}</td>
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
                window.location.href = '#body-fechamento';
            } else {
                $("#tab-hist-fechamento").prepend(`<div> Não há itens para exibir </div>`);
                window.location.href = '#tab-hist-fechamento';
            }
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
    const token = $('[name="_token"]').val();
    const dt_fechamento = dtFechamento;
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
            $("#tab-hist-fechamento").empty();
            if (result.length > 0) {
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
                    element.DT_ENCERR == null ? element.DT_ENCERR = "" : ""

                    $("#body-fechamento").prepend(`<tr id="tr-fechamento">`)
                    $("#tr-fechamento").append(`
                        <td>${element.CLIENTE}</td>
                        <td>${element.PROJETO}</td>
                        <td>R$${element.VALOR}</td>
                        <td>${element.VOLUME}</td>
                        <td>${element.DT_ABERTURA}</td>
                        <td>${element.DT_INICIO}</td>
                        <td>${element.PRAZO}</td>
                        <td>${element.ID_TAB_SITUACAO[0].SITUACAO}</td>
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
                window.location.href = '#body-fechamento';
            } else {
                $("#tab-hist-fechamento").prepend(`<div> Não há itens para exibir </div>`);
                window.location.href = '#tab-hist-fechamento';
            }
        },
        error: (err) => {

        }
    });
}

function getResumoFechamento(idFechamento, url) {
    const token = $('[name="_token"]').val();

    $.ajax({
        headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: url,
        dataType: 'JSON',
        data: {
            "_token": token,
            "id": idFechamento,
        },
        success: (result) => {

            $("#tab-hist-fechamento").empty();

            $("#tab-hist-fechamento").prepend(`
                        <div class="table table-responsive">
                            <table id="pipeline-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Data Fechamento</th>
                                        <th scope="col">Referência</th>
             
                                        <th scope="col">Receita Estimada</th>
                                        <th scope="col">Receita Esperada</th>
                                        <th scope="col">Impacto</th>
                                    </tr>
                                </thead>
                                <tbody id="body-fechamento">
                                    <tr id="tr-fechamento">
                                        <th scope="row">Declinada</th>
                                        <td>${result["fechamento"].DT_FECHAMENTO}</td>
                                        <td>${result["fechamento"].DT_REFERENCIA}</td>
                                  
                                        <td>R$${result["fechamento"].TOT_REC_EST}</td>
                                        <td>R$${result["fechamento"].TOT_REC_ESP}</td>
                                        <td>R$${result["fechamento"].TOT_IMPACTO}</td> 
                                    <tr/>  
                                    <tr id="tr-fechamento">
                                        <th scope="row">Fechada</th>
                                        <td>${result["fechamento"].DT_FECHAMENTO}</td>
                                        <td>${result["fechamento"].DT_REFERENCIA}</td>
                                        <td>${result["fechamento"].PERIODO}</td>
                                        <td>R$${result["fechamento"].TOT_REC_EST}</td>
                                        <td>R$${result["fechamento"].TOT_REC_ESP}</td>
                                        <td>R$${result["fechamento"].TOT_IMPACTO}</td> 
                                    <tr/>  
                                    <tr id="tr-fechamento">
                                        <th scope="row">Mudança</th>
                                        <td>${result["fechamento"].DT_FECHAMENTO}</td>
                                        <td>${result["fechamento"].DT_REFERENCIA}</td>
                                        <td>${result["fechamento"].PERIODO}</td>
                                        <td>R$${result["fechamento"].TOT_REC_EST}</td>
                                        <td>R$${result["fechamento"].TOT_REC_ESP}</td>
                                        <td>R$${result["fechamento"].TOT_IMPACTO}</td> 
                                    <tr/>
                                    <tr id="tr-fechamento">
                                        <th scope="row">Nova</th>
                                        <td>${result["fechamento"].DT_FECHAMENTO}</td>
                                        <td>${result["fechamento"].DT_REFERENCIA}</td>
                                        <td>${result["fechamento"].PERIODO}</td>
                                        <td>R$${result["fechamento"].TOT_REC_EST}</td>
                                        <td>R$${result["fechamento"].TOT_REC_ESP}</td>
                                        <td>R$${result["fechamento"].TOT_IMPACTO}</td> 
                                    <tr/>   
                                    <tr id="tr-fechamento">
                                        <th scope="row">Nova</th>
                                        <td>${result["fechamento"].DT_FECHAMENTO}</td>
                                        <td>${result["fechamento"].DT_REFERENCIA}</td>
                                        <td>${result["fechamento"].PERIODO}</td>
                                        <td>R$${result["fechamento"].TOT_REC_EST}</td>
                                        <td>R$${result["fechamento"].TOT_REC_ESP}</td>
                                        <td>R$${result["fechamento"].TOT_IMPACTO}</td> 
                                    <tr/>                                                                                                                                                    
                                </tbody>
                            </table>
                        </div>
                    `)

            window.location.href = '#body-fechamento';

        },
        error: (err) => {

        }
    });
}

function cancelaFechamento(idFechamento) {
    const token = $('[name="_token"]').val();

    $.ajax({
        headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: "/fechamento/cancelar/",
        dataType: 'JSON',
        data: {
            "_token": token,
            "id_fechamento": idFechamento
        },
        success: (result) => {
            console.log(result)
            if (result.status == "sucess") {
                window.location.reload();
            }
        },

        error: (err) => { }
    });
}

function confirmaFechamento(idFechamento) {
    const token = $('[name="_token"]').val();

    $.ajax({
        headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: "/fechamento/confirmar-fechamento/",
        dataType: 'JSON',
        data: {
            "_token": token,
            "id_fechamento": idFechamento
        },
        success: (result) => {
            console.log(result)
            if (result.status == "sucess") {
                window.location.reload();
            }
        },

        error: (err) => { }
    });
}

$(() => {
    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#form-add-pipeline tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
