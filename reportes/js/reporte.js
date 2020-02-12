//Script para manejar los dropdown de Reportes, realizar la busqueda y listar los archivos de la carpeta
$(function(){
    $( "#mes" ).fadeOut( "slow" );
    $( "#buscar" ).click(function(){ 
        $('#archive').html("");
        $("#mensaje").show();
        $("#modal_wait").show();
        var boletin = $("#boletin").val();
        var estacion = $("#estacion").val();
        var periocidad = $("#periocidad").val();
        var fecha = $("#fecha").val();
        var mes = $("#mes").val();
        var path = "";
        if(boletin == "" || estacion == "" || periocidad == "" || fecha == ""){
            $('#archive').html("<div class='alert alert-info'>Seleccione cada uno de los parametros para realizar la busqueda<div>");
            $("#mensaje").hide();
            return false;
        }
        if(periocidad == 'diarios'){
            path = "&mes=" + mes;
        }
        var data = "boletin="+boletin+"&estacion="+estacion+"&periocidad="+periocidad+"&fecha="+fecha+path;
        
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
                                $("#modal_wait").hide();
                        }else{
                                $('#archive').html(response.salida);
                                $("#mensaje").hide();
                                $("#modal_wait").hide();
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error general del sistema, intente más tarde');
                        $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                        return false;
                }
        });
    });
    
    $( "#boletin" ).change(function() {
           var valor = $("#boletin").val();
           var nombre = $("#name").val();
           if(valor != ''){
                $.ajax({					
                     cache: false,
                     type: "POST",
                     dataType: "json",
                     url: "load_directory.php",
                     data: {path : valor,actionID : 'chargue_dp',name : nombre},
                     success: function(response){
                             // Validar mensaje de error
                             if(response.respuesta == false){
                                     $("#estacion").html("");
                                     $('#archive').html("<div class='alert alert-info'>La estación no cuenta con boletines para ser visualizados</div>");                                
                             }else{
                                     $("#estacion").html("");
                                     $('#estacion').html(response.salida);
                             }
                     },
                     error:function(xhr,ajaxOptions,thrownError){
     //						alert('Error general del sistema, intente más tarde');
                             $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                             return false;
                     }
                 });
           }
    });
    
    $( "#estacion" ).change(function() {
           var boletin = $("#boletin").val();
           var estacion = $("#estacion").val();
           var path = boletin + '/' + estacion;
           $.ajax({					
                cache: false,
                type: "POST",
                dataType: "json",
                url: "load_directory.php",
                data: {path : path,actionID : 'chargue_dp'},
                success: function(response){
                        // Validar mensaje de error
                        if(response.respuesta == false){
                                $("#fecha").fadeOut('slow').html("");
                                $("#periocidad").fadeOut('slow');
                                $("#mes").fadeOut('slow');  
                                                              
                                $('#archive').html("<div class='alert alert-info'>La estación no cuenta con boletines para ser visualizados</div>");                                
                        }else{
                                $("#fecha").fadeIn('slow').html(response.salida);
                                $("#periocidad").fadeIn('slow');
                                $("#mes").fadeIn('slow');
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error general del sistema, intente más tarde');
                        $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                        return false;
                }
            });
    });
    
    $( "#fecha" ).change(function() {
           var boletin = $("#boletin").val();
           var estacion = $("#estacion").val();
           var fecha = $("#fecha").val();
           var path = boletin + '/' + estacion + '/' + fecha;
           $.ajax({					
                cache: false,
                type: "POST",
                dataType: "json",
                url: "load_directory.php",
                data: {path : path,actionID : 'chargue_dp'},
                success: function(response){
                        // Validar mensaje de error
                        if(response.respuesta == false){
                                $("#periocidad").fadeOut('slow');
                                $('#archive').html("<div class='alert alert-info'>La estación no cuenta con boletines para ser visualizados</div>");                                
                        }else{
                                $("#periocidad").fadeIn('slow').html(response.salida);
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error general del sistema, intente más tarde');
                        $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                        return false;
                }
            });
    });
    
    $( "#periocidad" ).change(function() {
        var periocidad = $( "#periocidad" ).val();
        if(periocidad != "diarios"){
            $( "#mes" ).fadeOut( "slow" );
        }else{
            $( "#mes" ).fadeIn( "slow" );
        }   
    });

});

function preseleccionar(name,tipoestacion,carpeta){
           var estacion = name;
           var tipo = tipoestacion;
           var folder = carpeta;
           //console.log("estacion: "+estacion+" tipo: "+tipo+" folder: "+folder);
           
           $.ajax({					
                cache: false,
                type: "POST",
                dataType: "json",
                url: "load_directory.php",
                data: {name : estacion,actionID : 'search_directory',folder : folder,tipo : tipo},
                success: function(response){
                        // Validar mensaje de error
                        if(response.respuesta == false){
                                if(estacion == "" && tipo == "PDNT"){
                                    $("#boletin option[value=pluviometros]").attr("selected",true);
                                    $( "#boletin" ).trigger( "change" );
                                }else if(estacion == "" && (tipo == "SN" || tipo == "SNNT")){
                                    $("#boletin option[value=sensores]").attr("selected",true);
                                    $( "#boletin" ).trigger( "change" );
                                }else if(estacion == "" && (tipo == "ECT" || tipo == "EHT" || tipo == "ENT" || tipo == "EQT" || tipo == "EC")){
                                    $("#boletin option[value=estaciones]").attr("selected",true);
                                    $( "#boletin" ).trigger( "change" );
                                }else{
                                    $("#boletin option[value=pluviometros]").attr("selected",true);
                                    $( "#boletin" ).trigger( "change" );
                                }
                                $("#archive").fadeOut('slow').html("<div class='alert alert-info'>La estación no cuenta con boletines para ser visualizados</div>");
                        }else{
                                $("#archive").fadeIn('slow').html(response.salida);
                                $("#boletin option[value="+ response.salida +"]").attr("selected",true);
                                $( "#boletin" ).trigger( "change" );
                                setTimeout(function(){
                                	$('#buscar').click();
                                }, 1000);
                                
                                search_preselect();
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error General del Sistema, Intente Mas Tarde');
                        $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                        return false;
                }
            }); 
            
}

function search_preselect(){
        $('#archive').html("");
        $("#mensaje").show();
        $("#modal_wait").show();
        var boletin = $("#boletin").val();
        var estacion = $("#name").val();
        var periocidad = $("#periocidad").val();
        var fecha = $("#fecha").val();
        var mes = $("#mes").val();
        var path = "";
        if(boletin == "" || estacion == "" || periocidad == "" || fecha == ""){
            $('#archive').html("<div class='alert alert-info'>Seleccione cada uno de los parametros para realizar la busqueda<div>");
            $("#mensaje").hide();
            return false;
        }
        if(periocidad == 'diarios'){
            path = "&mes=" + mes;
        }
        var data = "boletin="+boletin+"&estacion="+estacion+"&periocidad="+periocidad+"&fecha="+fecha+path;
        
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
                                $("#modal_wait").hide();
                        }else{
                                $('#archive').html(response.salida);
                                $("#mensaje").hide();
                                $("#modal_wait").hide();
                        }
                },
                error:function(xhr,ajaxOptions,thrownError){
//						alert('Error general del sistema, intente más tarde');
                        $('#archive').html("<div class='alert alert-error'>Error al conectar con la base de datos, vuelva a intentar</div>");
                        return false;
                }
        });
        
        return true;
}
