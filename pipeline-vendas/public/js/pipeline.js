
for (let lin = 0; lin < 10; lin++) {
  for (let col = 0; col < 12; col++) {    
    $( `#inpt_lin${lin}_col${col}`)
    .mouseover(function() {
      this.style.border = '0.1em solid #c1c0c0';
    })
    .mouseout(function() {
      this.style.border = '0em';
    });
  }


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