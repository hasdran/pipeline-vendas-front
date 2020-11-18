
function getQtdeLinhasTable() {
  return $("#qtdeLinhas").val();
}

for (let lin = 1; lin <= getQtdeLinhasTable(); lin++) {
  for (let col = 1; col <= 16; col++) {
    $(`#inpt_lin${lin}_col${col}`)
      .mouseover(function () {
        this.style.border = '0.1em solid #c1c0c0';
        $(`#inpt_lin${lin}_col${col}`).prepend("<h1>");
      })
      .mouseout(function () {
        this.style.border = '0em';
      });
  }
}

$("#inpt_prz").keyup(function () {
  let prazo = $(this).val();
  let valM3 = $("#inpt_val").val();
  let volume = $("#inpt_vol").val();

  const qtdeEst = calculaRecEst(prazo, valM3, volume);
  const qtdeEsp = calculaRecEsp(qtdeEst);
  insereRecEsp(qtdeEst);

  $("#inpt_rec_est").val(qtdeEst);
  $("#inpt_rec_esp").val(qtdeEsp);

  if (prazo >= 12) {
    $("#inpt_dur").val("Longo prazo");
  } else if (prazo < 12) {
    $("#inpt_dur").val("Curto prazo");
  }
});


$("#inpt_tempo").keyup(function () {
  let valM3 = $("#inpt_val").val();
  let volume = $("#inpt_vol").val();
  let probabilidade = $("#inpt_prob").val();
  let tempo = $(this).val();

  $("#inpt_impacto").val(calculaImpacto(valM3, volume, probabilidade, tempo).toFixed(2));
});

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

function insereRecEsp(qtdeEst) {
  $("#inpt_prob").change(function () {

    $("#inpt_rec_esp").val(calculaRecEsp(qtdeEst));
  });
}
$("#btn_cancel").click(function () {
  window.location.href = "/"
});

$("#btn_add").click(function () {
  window.location.href = "./create"
});

$("#btn_save").click(function () {
  $("#pipeline_form").submit();
});

function corrigeData(data) {
  if (data[0].length == 2) {
    return `${data[2]}-${data[1]}-${data[0]}`.toString();
  } else {
    return `${data[0]}-${data[1]}-${data[2]}`.toString();
  }
}

for (let index = 1; index <= getQtdeLinhasTable(); index++) {
  $(`#inpt_lin${index}_col5`).focusin(() => {
    var dateStr = $(`#inpt_lin${index}_col5`).val().split("-");
    $(`#inpt_lin${index}_col5`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col5`).prop("type", "date");
  });
  $(`#inpt_lin${index}_col5`).click(() => {
    var dateStr = $(`#inpt_lin${index}_col5`).val().split("-");
    $(`#inpt_lin${index}_col5`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col5`).prop("type", "date");
  });

  $(`#inpt_lin${index}_col6`).click(() => {
    var dateStr = $(`#inpt_lin${index}_col6`).val().split("-");
    $(`#inpt_lin${index}_col6`).val(`${dateStr[2]}-${dateStr[1]}-${dateStr[0]}`);
    $(`#inpt_lin${index}_col6`).prop("type", "date");
  });

  $(`#btn-remover-${index}`).click(() => {
    var token = $('[name="_token"]').val();
    var id = $(`#id_${index}`).val();
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
    var token = $('[name="_token"]').val();
    var id = $(`#id_${index}`).val();
    var cliente = $(`#inpt_lin${index}_col1`).val();
    var projeto = $(`#inpt_lin${index}_col2`).val();
    var valor_m3 = $(`#inpt_lin${index}_col3`).val()
    var volume_m3 = $(`#inpt_lin${index}_col4`).val();
    var dt_abertura = $(`#inpt_lin${index}_col5`).val().split("-");
    var dt_inicio_op = $(`#inpt_lin${index}_col6`).val().split("-");
    var prazo_contrato = $(`#inpt_lin${index}_col7`).val();
    var probabilidade = $(`#inpt_lin${index}_col8`).val();
    var situacao = $(`#situ_lin${index}`).val();
    var dt_encerramento = $(`#inpt_lin${index}_col10`).val().split("-");
    var tempo = $(`#inpt_lin${index}_col11`).val();
    // var dtopeinc = 
    // var id_fechamento = 
    // var mudanca_sts = 
    dt_abertura = corrigeData(dt_abertura);
    dt_inicio_op = corrigeData(dt_inicio_op);
    if (dt_encerramento != "") {
      dt_encerramento = corrigeData(dt_encerramento);
    }

    $.ajax({
      headers: { 'CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "POST",
      url: "/update",
      dataType: 'JSON',
      data: {
        "_token": token,
        "id": id,
        "cliente": cliente,
        "projeto": projeto,
        "valor_m3": valor_m3,
        "volume_m3": volume_m3,
        "dt_abertura": dt_abertura,
        "dt_inicio_op": dt_inicio_op.toString(),
        "prazo_contrato": prazo_contrato,
        "probabilidade": probabilidade,
        "situacao": situacao,
        "dt_encerramento": `${dt_encerramento[2]}-${dt_encerramento[1]}-${dt_encerramento[0]}`.toString(),
        "tempo": tempo,
        // "tempo": $(`#inpt_${index}_col11`).val(),
        "dtopeinc": "2020-12-01",
        "id_fechamento": "1",
        "mudanca_sts": "Ativa"
      },
      success: (result) => {
        window.location.href = "/?op=updated"
      },
      error: (err) => {
        console.log(err)
        window.location.href = "/?op=error"
      }
    });
  });
}














// var input = document.getElementById("inpt_1");
// // var btn = document.getElementById("btn");

// input.addEventListener("click", modifyProps, false); 

// var hitRegion = instanceOfMouseEvent.region
// function modifyProps(id) {    
//     input.style.border = '0.1em solid #c1c0c0'
// }