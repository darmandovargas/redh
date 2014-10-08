<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'content_models.php';

$contenido = search_content($oMySQL,'noticias');
?>
<link rel="stylesheet" href="css/style.css" type="text/css">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="ckeditor/ckeditor.js"></script>
<span style="font-family: Helvetica, Arial, Sans-Serif; font-weight: 100"> 
    <?php session_start(); ?>
    <?php $edit = ""; if(isset($_REQUEST['edit'])){ $edit="display:none"; } ?>
    <div id="edit" <?php echo ($_SESSION['sessid']== session_id() && $_SESSION['type'] == 'admin')?'style="margin-left: 98%;margin-top:0px;float:right;'.$edit.'"':'style="display: none;margin-left: 98%;"' ?>>
        <a href="noticias.php?edit=edit" title="Editar" style="text-decoration: none;"><img src="../img/file_edit.png" width="20" height="20" /></a>
    </div>                    
    <div id="notify" style="margin-top: 10px;width: 500px;">
        <?php if(isset($_REQUEST['msj'])) {
            echo $_REQUEST['msj']; ?>
            <script>$("#notify").delay(5000).hide(600);</script>
        <?php } ?>
    </div>
    
    <?php if(isset($_REQUEST['edit'])){ ?>
    <link rel="stylesheet" href="../../home/css/themes/default/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../../home/css/themes/default/bootstrap-responsive.css" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/default/bootstrap.css" title="default" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/default/bootstrap-responsive.css" title="default" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/blue/bootstrap.css" title="blue" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/blue/bootstrap-responsive.css" title="blue" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/coffee/bootstrap.css" title="coffee" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/coffee/bootstrap-responsive.css" title="coffee" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/dark/bootstrap.css" title="dark" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/dark/bootstrap-responsive.css" title="dark" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/eco/bootstrap.css" title="eco" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/eco/bootstrap-responsive.css" title="eco" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/red/bootstrap.css" title="red" type="text/css">
    <link rel="alternate stylesheet" href="../../home/css/themes/red/bootstrap-responsive.css" title="red" type="text/css">
    <div id="noticia_edit" style="height: 95%;"><textarea name="editor1" id="editor1" rows="1000" cols="1000">
            <?php echo utf8_encode($contenido); ?>
        </textarea>
        <div id="msg" style="margin: 0px auto;display: none">
            <img src="../img/cargando.gif" alt="cargando" /> <span class="text-info">Guardando por favor espere un momento...</span>
        </div>
        <div id="button" style="margin-right: 0px;margin-top: 10px;">
                    <a class="btn btn-primary" href="javascript:void(0)" onclick="guardar()">Guardar</a>
                    <a class="btn" href="javascript:parent.showNotice();">Cancelar</a>
                </div>
        
    </div><script>
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace( 'editor1' );
            function guardar(){
                
                var texto = CKEDITOR.instances['editor1'].getData();
                $("#notify").html("");
                console.log("texto: "+texto);
                $("#msg").show();
                $.ajax({					
                    cache: false,
                    type: "POST",
                    dataType: "json",
                    url: "content_models.php",
                    data: {texto : texto,actionID : 'insert',page : 'noticias'},
                    success: function(response){
                            // Validar mensaje de error
                            if(response.respuesta == true){ 
                                   window.location.href = "noticias.php?msj=<div class='exito'>Noticias y eventos almacenados correctamente</div>";
                                   $("#msg").hide();
                            }
                    },
                    error:function(xhr,ajaxOptions,thrownError){
                            window.location.href = "noticias.php?msj=<div class='error'>Problemas al conectar con el servidor, vuelva a intentar</div>";
                            return false;
                    }
                });
            }            
        </script>
    <?php }else{ ?>
    <div id="noticia_view" style="text-align: left;">
        <?php echo utf8_encode($contenido); ?>
    </div>
    <?php }?>
        </span>
