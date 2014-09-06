//Script para manejar los dropdown de Reportes, realizar la busqueda y listar los archivos de la carpeta
$(function(){
    $( "#buscar" ).click(function(){ 
        $('#archive').html("");
        $("#mensaje").show();
        var boletin = $("#boletin").val();
        var estacion = $("#estacion").val();
        var periocidad = $("#periocidad").val();
        var fecha = $("#fecha").val();
        if(boletin == "" || estacion == "" || periocidad == "" || fecha == ""){
            $('#archive').html("<div class='alert alert-info'>Seleccione cada uno de los parametros para realizar la busqueda<div>");
            $("#mensaje").hide();
            return false;
        }
        var data = "boletin="+boletin+"&estacion="+estacion+"&periocidad="+periocidad+"&fecha="+fecha;
        $.ajax({					
                cache: false,
                type: "POST",
                dataType: "json",
                url: "cargue_archivos.php",
                data: data,
                success: function(response){
                        // Validar mensaje de error
                        if(response.respuesta == false){
                                $('#archive').html("<div class='alert alert-info'>No se encontraron resultados en la busqueda</div>");
                                $("#mensaje").hide();
                        }else{
                                $('#archive').html(response.salida);
                                $("#mensaje").hide();
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error General del Sistema, Intente Mas Tarde');
                        alert(xhr.responseText);
                        alert(thrownError);
                        return false;
                }
        });
    });
    
    $( "#boletin" ).change(function() {
            var valor = $("#boletin").val();
            var estacion = ["San Jose", "El Lago", "Cortaderal", "El Cedral", "San Juan", "El Nudo", "Quinchia Seafield", "Acuaseo", "Mundo Nuevo", "UTP"];
            var sensores = ["Rio barbo", "Quebrada el oso", "Quebrada san eustaquio", "Quebrada negra", "Quebrada el manzano", "Humedal lisbran", "Quebrada volcanes", "Quebrada dali"];
            var pluviometros = ["CAI Villaverde", "Pluv_UTP", "Boc acueducto acuasat tinajas", "Pluv_02_finca_lisbran", "Tanque Villasantana", "Boc acueducto la honda", "Pluv_01_finca_lisbran", "Boc acueducto la bella", "Boc acueducto perez alto"
                , "Parque Industrial","Pluv_Bosques de Cuba","CAI Poblado","CAI Consota","Bomberos ormaza","Cruz Roja","Pluv_Colegio Saint George","CAI Galan","Boc acueducto la florida"];
            var lista = "";
            var i;
            if(valor == "estaciones"){
                lista = "<option value=''>Seleccione estacion</option>";
                cant = estacion.length;
                for(i=0;i<cant;i++){
                    lista = lista + "<option value='" +estacion[i].toLowerCase()+ "'>" +estacion[i]+"</option>"
                }
            }else if(valor == "sensores"){
                lista = "<option value=''>Seleccione lugar</option>";
                cant = sensores.length;
                for(i=0;i<cant;i++){
                    lista = lista + "<option value='" +sensores[i].toLowerCase()+ "'>" +sensores[i]+"</option>"
                }
            }else if(valor == "pluviometros"){
                lista = "<option value=''>Seleccione lugar</option>";
                cant = pluviometros.length;
                for(i=0;i<cant;i++){
                    lista = lista + "<option value='" +pluviometros[i].toLowerCase()+ "'>" +pluviometros[i]+"</option>"
                }
            }else if(valor == ""){
                $("#estacion").html("");    
                $("#fecha").html("");
                $("#periocidad").html("");
            }
            $("#fecha").html("");
            $("#periocidad").html("");
            $("#estacion").html("");
            $("#estacion").append(lista);
    });
    
    
    
    $( "#estacion" ).change(function() { 
            var boletin = $("#boletin").val();
            var estacion = $("#estacion").val();
            var fechas = ["2014", "2013", "2012", "2011", "2010"];
            var lista = "";
            var i;
            if(boletin != "" && estacion != ""){
                lista = "<option value=''>Seleccione año</option>";
                cant = fechas.length;
                for(i=0;i<cant;i++){
                    lista = lista + "<option value='" +fechas[i]+ "'>" +fechas[i]+"</option>"
                }
            }else if(estacion == ""){
                $("#fecha").html("");
                $("#periocidad").html("");
            }
            $("#fecha").html("");
            $("#periocidad").html("");
            $("#fecha").append(lista);
    }); 
    
    $( "#fecha" ).change(function() {
            var boletin = $("#boletin").val();
            var estacion = $("#estacion").val();
            var fecha = $("#fecha").val();
            var periodos = ["Diarios", "Mensual", "Anual"];
            var pluviometros = ["Mensuales", "Anuales"];
            var i;
            var lista = "";
            if(boletin != "" && estacion != "" && fecha != ""){
                if(boletin == "estaciones" || boletin == "sensores"){
                    lista = "<option value=''>Seleccione periodo</option>";
                    cant = periodos.length;
                    for(i=0;i<cant;i++){
                        lista = lista + "<option value='" +periodos[i].toLowerCase()+ "'>" +periodos[i]+"</option>"
                    }
                }else if(boletin == "pluviometros"){
                    lista = "<option value=''>Seleccione periodo</option>";
                    cant = pluviometros.length;
                    for(i=0;i<cant;i++){
                        lista = lista + "<option value='" +pluviometros[i].toLowerCase()+ "'>" +pluviometros[i]+"</option>"
                    }
                }
            }
            $("#periocidad").html("");
            $("#periocidad").append(lista);
    });
    
});


function preseleccionar(name){
     var estacion = ["San Jose", "El Lago", "Cortaderal", "El Cedral", "San Juan", "El Nudo", "Quinchia Seafield", "Acuaseo", "Mundo Nuevo", "UTP"];
     var sensores = ["Rio barbo", "Quebrada el oso", "Quebrada san eustaquio", "Quebrada negra", "Quebrada el manzano", "Humedal lisbran", "Quebrada volcanes", "Quebrada dali"];
     var pluviometros = ["CAI Villaverde", "Pluv_UTP", "Boc acueducto acuasat tinajas", "Pluv_02_finca_lisbran", "Tanque Villasantana", "Boc acueducto la honda", "Pluv_01_finca_lisbran", "Boc acueducto la bella", "Boc acueducto perez alto"
                , "Parque Industrial","Pluv_Bosques de Cuba","CAI Poblado","CAI Consota","Bomberos ormaza","Cruz Roja","Pluv_Colegio Saint George","CAI Galan","Boc acueducto la florida"];
     var cant = 0;
     var lista = "";
     var i;
     var fecha = new Date();
     var ano = fecha.getFullYear();
     if(name != ""){
         var index = estacion.indexOf(name);
         if(index != -1){
             lista = "<option value=''>Seleccione estacion</option>";
             $("#boletin option[value=estaciones]").attr("selected",true);
             cant = estacion.length;
             for(i=0;i<cant;i++){
                    selected = "";
                    if(name.toLowerCase() == estacion[i].toLowerCase())
                        selected = "selected";
                    lista = lista + "<option value='" +estacion[i].toLowerCase()+ "' "+selected+">" +estacion[i]+"</option>"
             }             
             $("#estacion").html("");
             $("#estacion").append(lista);
             lista = "";
             lista = "<option value=''>Seleccione fecha</option>";
             for(i=ano;i>=2007;i--){
                    selected = "";
                    if(i == ano)
                        selected = "selected";
                    lista = lista + "<option value='" +i+ "' "+selected+">" +i+"</option>"
             }
             $("#fecha").html("");
             $("#fecha").html(lista);
             $("#periocidad").html("<option value=''>Seleccione periodo</option><option value='diarios' selected>Diarios</option><option value='mensual'>Mensual</option><option value='anual'>Anual</option>");
             return false;
             
         }
         index = sensores.indexOf(name);
         if(index != -1){
             $("#boletin option[value=sensores]").attr("selected",true);
             cant = sensores.length;
             for(i=0;i<cant;i++){
                    selected = "";
                    if(name.toLowerCase() == sensores[i].toLowerCase())
                        selected = "selected";
                    lista = lista + "<option value='" +sensores[i].toLowerCase()+ "' "+selected+">" +sensores[i]+"</option>"
             }
             $("#estacion").html("");
             $("#estacion").append(lista);
             lista = "";
             lista = "<option value=''>Seleccione fecha</option>";
             for(i=ano;i>=2007;i--){
                    selected = "";
                    if(i == ano)
                        selected = "selected";
                    lista = lista + "<option value='" +i+ "' "+selected+">" +i+"</option>"
             }
             $("#fecha").html("");
             $("#fecha").html(lista);
             $("#periocidad").html("<option value=''>Seleccione periodo</option><option value='diarios' selected>Diarios</option><option value='mensual'>Mensual</option><option value='anual'>Anual</option>");
             return false;
         }
         index = pluviometros.indexOf(name);
         if(index != -1){
             $("#boletin option[value=pluviometros]").attr("selected",true);
             cant = pluviometros.length;
             for(i=0;i<cant;i++){
                    selected = "";
                    if(name.toLowerCase() == pluviometros[i].toLowerCase())
                        selected = "selected";
                    lista = lista + "<option value='" +pluviometros[i].toLowerCase()+ "' "+selected+">" +pluviometros[i]+"</option>"
             }
             $("#estacion").html("");
             $("#estacion").append(lista);
             lista = "";
             lista = "<option value=''>Seleccione fecha</option>";
             for(i=ano;i>=2007;i--){
                    selected = "";
                    if(i == ano)
                        selected = "selected";
                    lista = lista + "<option value='" +i+ "' "+selected+">" +i+"</option>"
             }
             $("#fecha").html("");
             $("#fecha").html(lista);
             $("#periocidad").html("<option value=''>Seleccione periodo</option><option value='diarios' selected>Diarios</option><option value='mensuales'>Mensuales</option><option value='anuales'>Anuales</option>");
             return false;
         }         
     }            
     return true;           
}
