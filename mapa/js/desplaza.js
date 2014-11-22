$(document).ready(function(){
    $(".trigger").click(function(){
        $("#coordx").val("");
        $("#coordy").val("");
        $(".panel1").toggle("fast");
        $(this).toggleClass("active");
        return false;
    });
});

function validateDec(field) {
    var valid = "0123456789.°''";//°
    var temp;
    for (var i=0; i<field.value.length; i++) {
        temp = "" + field.value.substring(i, i+1);
        if (valid.indexOf(temp) == "-1") {
            field.value=(field.value.substring(0,i)+(field.value.substring(i+1,field.value.length)));
            i--
        }
    }
}

function validateCharacter(e) {
    var tecla= document.all ? tecla = e.keyCode : tecla = e.which;
    return ((tecla > 47 && tecla < 58) || tecla == 34 || tecla == 46 || tecla == 176 || tecla == 39);
}