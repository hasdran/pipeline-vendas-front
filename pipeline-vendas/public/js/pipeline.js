
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
  console.log(lin)

}
$(".inpt_prz").keyup(function () {
  let prazo = $(this).val();
  let qtde = $(this).val();
  let volume = $(this).val();
  $(".inpt_time").val(prazo);
  
  prazo = parseInt(prazo);

  if (prazo >= 12) {
    $(".inpt_dur").val("Longo prazo");
  }else if (prazo < 12) {
    $(".inpt_dur").val("Curto prazo");
  }


});

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