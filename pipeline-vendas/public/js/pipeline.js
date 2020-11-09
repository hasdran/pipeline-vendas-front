
var qtdeLinhas = document.getElementById("qtdeLinhas").value;

for (let lin = 1; lin <= qtdeLinhas; lin++) {
  for (let col = 1; col <= 16; col++) {    
    $( `#inpt_lin${lin}_col${col}`)
    .mouseover(function() {
      this.style.border = '0.1em solid #c1c0c0';
    })
    .mouseout(function() {
      this.style.border = '0em';
    });
  }
}

$(".inpt_prz").keyup(function () {
  let prazo = $(this).val();
  let valM3 = $(".inpt_val").val();
  let volume = $(".inpt_vol").val();
  
  const qtdeEst = calculaRecEst(prazo, valM3, volume);
  const qtdeEsp = calculaRecEsp(qtdeEst);
  insereRecEsp(qtdeEst);

  $(".inpt_rec_est").val(qtdeEst);
  $(".inpt_rec_esp").val(qtdeEsp);

  if (prazo >= 12) {
    $(".inpt_dur").val("Longo prazo");
  }else if (prazo < 12) {
    $(".inpt_dur").val("Curto prazo");
  }
});


$(".inpt_time").keyup(function () {
  let valM3 = $(".inpt_val").val();
  let volume = $(".inpt_vol").val();
  let probabilidade = $(".inpt_prob").val();
  let tempo = $(this).val();

  $(".inpt_impacto").val(calculaImpacto(valM3, volume, probabilidade, tempo));
});

function calculaRecEst(prazo, valM3, volume) {
  const val = parseFloat(valM3);
  const prz = parseInt(prazo);
  const vol = parseFloat(volume);
  const qtdeEst = val * vol * prz;

  return qtdeEst;
}

function calculaRecEsp(qtdeEst) {

    let probabilidade = $(".inpt_prob").val();
    probabilidade = parseInt(probabilidade);

    return qtdeEst * (probabilidade/100); 
}

function calculaImpacto(valorM3, volume, probabilidade, tempo ) {
  const val = parseFloat(valorM3);
  const vol = parseFloat(volume);
  const prob = parseInt(probabilidade);
  const tmp = parseInt(tempo);

  return val * vol * (prob/100) * tempo;
}

function insereRecEsp(qtdeEst) {
      $(".inpt_prob").change(function () {
  
      $(".inpt_rec_esp").val(calculaRecEsp(qtdeEst));
    });
}

$("#btn_add").click(function () {
  window.location.href = "./create"
});

$("#btn_save").click(function () {
  $("#pipeline_form").submit();
});












// var input = document.getElementById("inpt_1");
// // var btn = document.getElementById("btn");

// input.addEventListener("click", modifyProps, false); 

// var hitRegion = instanceOfMouseEvent.region
// function modifyProps(id) {    
//     input.style.border = '0.1em solid #c1c0c0'
// }