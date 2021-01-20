function getQtdeLinhasTable() {
  return $("#qtdeLinhas").val();
}

for (let lin = 1; lin <= getQtdeLinhasTable(); lin++) {
  for (let col = 1; col <= 16; col++) {
    $(`#inpt_lin${lin}_col${col}`)
      .mouseover(function () {
        // $(`#inpt_lin${lin}_col${col}`).attr('disabled', '');
      })
      .mouseout(function () {
        // this.style.border = '0em';
      });
  }
}
/*****
 * Calcula os valores das receitas e exibe na tabela pipeline
 */
function simulaReceitas() {
  let prazo = $("#inpt_prz").val();
  let valM3 = $("#inpt_val").val();
  let volume = $("#inpt_vol").val();
  let probabilidade = $("#inpt_prob").val();
  let tempo = $("#inpt_tempo").val();

  const qtdeEst = calculaRecEst(prazo, valM3, volume);
  const qtdeEsp = calculaRecEsp(qtdeEst);
  if (!isNaN(qtdeEst) && !isNaN(qtdeEsp)) {
    $("#inpt_rec_est").val(qtdeEst);
    $("#inpt_rec_esp").val(qtdeEsp);
  }

  if (prazo >= 12) {
    $("#inpt_dur").val("Longo prazo");
  } else if (prazo < 12) {
    $("#inpt_dur").val("Curto prazo");
  }

  if (tempo != "" && !isNaN(qtdeEst) && !isNaN(qtdeEsp)) {
    console.log(volume)
    $("#inpt_impacto").val(calculaImpacto(valM3, volume, probabilidade, tempo).toFixed(2));
  }
}

function calculaRecEst(prazo, valM3, volume) {
  const val = parseFloat(valM3);
  const prz = parseInt(prazo);
  const vol = parseFloat(volume);
  const qtdeEst = val * vol * prz;

  return qtdeEst;
}

function calculaRecEsp(qtdeEst) {

  let probabilidade = $("#inpt_prob").val();
  probabilidade = parseInt(probabilidade);

  return qtdeEst * (probabilidade / 100);
}

function calculaImpacto(valorM3, volume, probabilidade, tempo) {
  const val = parseFloat(valorM3);
  const vol = parseFloat(volume);
  const prob = parseInt(probabilidade);
  const tmp = parseInt(tempo);

  return val * vol * (prob / 100) * tmp;
}

function corrigeData(data) {
  if (data[0].length == 2) {
    return `${data[2]}-${data[1]}-${data[0]}`.toString();
  } else {
    return `${data[0]}-${data[1]}-${data[2]}`.toString();
  }
}

for (let index = 1; index <= getQtdeLinhasTable(); index++) {
  $(`#inpt_lin${index}_col5`).focusin(() => {
    let dateStr = $(`#inpt_lin${index}_col5`).val().split("-");
    $(`#inpt_lin${index}_col5`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col5`).prop("type", "date");
  });

  $(`#inpt_lin${index}_col5`).click(() => {
    let dateStr = $(`#inpt_lin${index}_col5`).val().split("-");
    $(`#inpt_lin${index}_col5`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col5`).prop("type", "date");
  });

  $(`#inpt_lin${index}_col6`).click(() => {
    let dateStr = $(`#inpt_lin${index}_col6`).val().split("-");
    $(`#inpt_lin${index}_col6`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col6`).prop("type", "date");
  });

  $(`#btn-remover-${index}`).click(() => {
    const token = $('[name="_token"]').val();
    let id = $(`#id_${index}`).val();
    $.ajax({
      headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "POST",
      url: "/pipeline/delete",
      dataType: 'JSON',
      data: {
        "_token": token,
        "id": id,
      },
      success: (result) => {
        window.location.href = "/"
      },
      error: (err) => {
        window.location.href = "/?op=error"
      }
    });
  });

  $(`#btn-editar-${index}`).click(() => {
    const token = $('[name="_token"]').val();
    let id = $(`#id_${index}`).val();
    let cliente = $(`#inpt_lin${index}_col1`).val();
    let projeto = $(`#inpt_lin${index}_col2`).val();
    let valorM3 = $(`#inpt_lin${index}_col3`).val()
    let volumeM3 = $(`#inpt_lin${index}_col4`).val();
    let dtAbertura = $(`#inpt_lin${index}_col5`).val().split("-");
    let dtInicioOp = $(`#inpt_lin${index}_col6`).val().split("-");
    let prazo_contrato = $(`#inpt_lin${index}_col7`).val();
    let probabilidade = $(`#inpt_lin${index}_col8`).val();
    let idTabSituacao = $(`#situ_lin${index}`).val();
    let dtEncerramento = $(`#inpt_lin${index}_col10`).val().split("-");
    let tempo = $(`#inpt_lin${index}_col11`).val();
    // let dtopeinc = 
    // let id_fechamento = 
    // let mudanca_sts = 
    dtAbertura = corrigeData(dtAbertura);
    dtInicioOp = corrigeData(dtInicioOp);
    if (dtEncerramento != "") {
      dtEncerramento = corrigeData(dtEncerramento);
    }

    // $.ajax({

    //   headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //   type: "POST",
    //   url: "/update",
    //   dataType: 'JSON',
    //   data: {
    //     "_token": token,
    //     "id": id,
    //     "cliente": cliente,
    //     "projeto": projeto,
    //     "valor_m3": valorM3,
    //     "volume_m3": volumeM3,
    //     "dt_abertura": dtAbertura,
    //     "dt_inicio_op": dtInicioOp.toString(),
    //     "prazo_contrato": prazo_contrato,
    //     "probabilidade": probabilidade,
    //     "id_tab_situacao": idTabSituacao,
    //     "dt_encerramento": `${dtEncerramento[2]}-${dtEncerramento[1]}-${dtEncerramento[0]}`.toString(),
    //     "tempo": tempo,
    //     "dtopeinc": "2020-12-01",
    //     "mudanca_sts": "Ativa",
    //     "mudanca_sts": 0
    //   },
    //   success: (result) => {
    //     window.location.href = "/?op=updated"
    //     console.log(result)
    //   },
    //   error: (err) => {
    //     window.location.href = "/?op=error"
    //     console.log(err)
    //   }
    // });
  });
}

$("#btn_add").click(() => loadFormAdd());
$("#btn_save").click(() => savePipeline());
/*****
 * Carrega dentro da tabela pipeline, o formulário de inserção
 */
function loadFormAdd() {
  const token = $('[name="_token"]').val();
  let situacoes = [];

  $.ajax({
    headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: "/situacoes",
    dataType: 'JSON',
    data: {
      "_token": token,
    },
    success: (result) => {
      result.map((elemento) => {
        situacoes += `<option value="${elemento.ID_TAB_SITUACAO}">${elemento.SITUACAO}</option>`;
      });
      $("#row-add-pipeline").remove();

      $("#form-add-pipeline").prepend(
        `
        <tr id="row-add-pipeline">
          <td>        
            <button id="btn_cancel" class="btn btn-default btn-remover" type="button" title="Limpar Dados" onclick="handleCancAdd()">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6.603 2h7.08a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-7.08a1 1 0 0 1-.76-.35L1 8l4.844-5.65A1 1 0 0 1 6.603 2zm7.08-1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zM5.829 5.146a.5.5 0 0 0 0 .708L7.976 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"/>
              </svg>
            </button>
          </td>
          <td>
            <button id="btn_save" class="btn btn-default"
          type="submit" title="Gravar">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
              </svg>
            </button>
          </td>
          
          <td>
            <input id="inpt_cliente" class="form-add input-md is-invalid" type="text" name="cliente" required>            
          </td>
          <td><input id="inpt_projeto" class="form-add input-lg" type="text" name="projeto"></td>
          <td><input id="inpt_val" class="form-add input-sm" type="text" name="valor_m3" onkeyup="simulaReceitas()"></td>
          <td><input id="inpt_vol" class="form-add input-sm" type="text" name="volume_m3" onkeyup="simulaReceitas()"></td>
          <td><input id="dt_abertura" class="form-add input-lg" type="date" name="dt_abertura"></td>
          <td><input id="dt_inicio" class="form-add input-lg" type="date" name="dt_inicio_op"></td>
          <td><input id="inpt_prz" class="form-add input-sm" type="text" name="prazo_contrato" onkeyup="simulaReceitas()"></td>
          <td>
            <select id="inpt_prob" class="form-add input-md" name="probabilidade" onchange="simulaReceitas()">
              <option value="10">10%</option>
              <option value="30">30%</option>
              <option value="50">50%</option>
              <option value="80">80%</option>
              <option value="100">100%</option>
            </select>
          </td>
          <td>
            <select id="inpt_id_situacao" class="form-add input-sm" name="situacao">${situacoes}</select>
          </td>
          <td><input class="form-add input-lg" type="date" name="dt_encerramento"></td>
          <td><input id="inpt_tempo" class="form-add input-sm" type="text" name="tempo" onkeyup="simulaReceitas()"></td>
          <td><input id="inpt_rec_est" class="form-add input-lg" type="text" name="receita_est" disabled></td>
          <td><input id="inpt_rec_esp" class="form-add input-lg" type="text" name="receita_esp" disabled></td>
          <td><input id="inpt_impacto" class="form-add input-lg" type="text" name="impacto" disabled></td>
          <td><input id="inpt_dur" class="form-add input-sm" type="text" name="duracao" disabled></td>
          <td><input class="form-add input-sm" type="text" name="mudanca_sts" disabled></td>
        </tr>        
        `
      );
      $("#btn_add").remove();
    },
    error: (err) => {
      console.log(err)
    }
  });
}

/*****
 * Função que cancela a inserção no pipeline 
 */
function handleCancAdd() {
  $("#row-add-pipeline").remove();
  $("#form-add-pipeline").prepend(`
    <tr>
      <td colspan="18"> <button id="btn_add" class="btn btn-default" type="button" onclick="loadFormAdd()"><span> Adicionar</span> </button> </td>        
    </tr>  
    
  `);
}

$('#form-add-pipeline').on('click', '#btn_save', function () {
  savePipeline();
});


function savePipeline() {
  let isValidForm = true;
  const token = $('[name="_token"]').val();
  const cliente = $("#inpt_cliente").val();
  const projeto = $("#inpt_projeto").val();
  const valor = $("#inpt_val").val();
  const volume = $("#inpt_vol").val();
  const dtAbertura = $("#dt_abertura").val();
  const dtEncerramento = $("#dt_encerramento").val();
  const dtInicio = $("#dt_inicio").val();
  const prazo = $("#inpt_prz").val();
  const probab = $("#inpt_prob").val();
  const idSituacao = $("#inpt_id_situacao").val();
  const tempo = $("#inpt_tempo").val();

  if (cliente == "") {
    isValidForm = false;
    $("#inpt_cliente").css("border", "#f75d5d 1px solid");
  }
  if (projeto == "") {
    isValidForm = false;
    $("#inpt_projeto").css("border", "#f75d5d 1px solid");
  }
  if (valor == "") {
    isValidForm = false;
    $("#inpt_val").css("border", "#f75d5d 1px solid");
  }
  if (volume == "") {
    isValidForm = false;
    $("#inpt_vol").css("border", "#f75d5d 1px solid");
  }
  if (dtAbertura == "") {
    isValidForm = false;
    $("#dt_abertura").css("border", "#f75d5d 1px solid");
  }
  if (dtInicio == "") {
    isValidForm = false;
    $("#dt_inicio").css("border", "#f75d5d 1px solid");
  }
  if (prazo == "") {
    isValidForm = false;
    $("#inpt_prz").css("border", "#f75d5d 1px solid");
  }
  if (tempo == "") {
    isValidForm = false;
    $("#inpt_tempo").css("border", "#f75d5d 1px solid");
  }

  if (isValidForm) {
    console.log("valido");
    $.ajax({
      headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "POST",
      url: "/create",
      dataType: 'JSON',
      data: {
        "_token": token,
        "cliente": cliente,
        "projeto": projeto,
        "valor_m3": valor,
        "volume_m3": volume,
        "dt_abertura": dtAbertura,
        "dt_encerramento": dtEncerramento,
        "dt_inicio_op": dtInicio,
        "prazo_contrato": prazo,
        "probabilidade": probab,
        "id_tab_situacao": idSituacao,
        "tempo": tempo

      },
      success: (result) => {
        window.location.href = "/?op=sucess";
      },
      error: (err) => {
        console.log(err);
        window.location.href = "/?op=error";
      }
    });
  }
}



$(function () {
  $("#myInput").on("keyup", function () {
    var value = $(this).val().toLowerCase();

    $("#form-add-pipeline tr").filter(function () {
      let strf = ""
      $(this).find("input").each(function () {
        strf += " " + $(this).val()
      });
      $(this).toggle(strf.toLowerCase().indexOf(value) > -1)
    });
  });
}
)

function myFunction() {
  $(function () {
    let contador = $("#show-results").val();
    console.log(`results ${contador}`)
    // let i = 0;
    // $("#myTable tr").filter(function () {
    //   $(this).toggle(i < contador)
    //   i++;
    // });
  });
}
