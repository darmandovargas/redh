<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Estaciones</title>

        <style type="text/css">
            #content {/*width:620px; margin:0px auto;*/}
            #desc {/*margin:10px;*/margin:0px; float:left; font-family: Arial, sans-serif; font-size: 12px;}
            label{color: black;}
            h3{color: red;}
        </style>
        <!-- include tn3 plugin -->
        <script type="text/javascript" src="../lib/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/reporte.js"></script>
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script type="text/javascript" src="css/bootstrap/js/bootstrap.min.js"></script>
        <!--  initialize the TN3 when the DOM is ready -->
        
        <script type="text/javascript">
	
        </script>
    </head>
    <body onLoad="preseleccionar('<?php echo $_REQUEST['name']; ?>','<?php echo $_REQUEST['tipo'];?>')" style="background-color: #EAF5F3;height: 400px;">
        <?php $hasta = "2007"; ?>
        <div id="content">
                <fieldset>
                    <input type="hidden" value="<?php echo $_REQUEST['name']; ?>" id="name" name="name" />
                    <select class="input-xlarge" id="boletin">
                        <option value="">Seleccione tipo de boletin</option>
                        <option value="estaciones">Estaciones Hidroclimatológicas</option>
                        <option value="sensores">Sensores de Nivel por Presión </option>
                        <option value="pluviometros">Pluviometros con Data Logger</option>
                    </select>
                    <select class="input-large" id="estacion">
                    </select>
                    <br>
                    <select class="input-medium" id="fecha">
                        <?php 
                            $ano = date('Y');
                            for($i = $ano;$i >= $hasta;$i--){
                                echo "<option value='".$i."' >".$i."</option>";
                            }
                        ?>
                    </select>
                    <select class="input-medium" id="periocidad">
                        <option value="diarios">Diario</option>
                        <option value="mensual" selected="selected">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                    <select class="input-medium" id="mes">
                        <option value="enero">Enero</option>
                        <option value="febrero">Febrero</option>
                        <option value="marzo">Marzo</option>
                        <option value="abril">Abril</option>
                        <option value="mayo">Mayo</option>
                        <option value="junio">Junio</option>
                        <option value="julio">Julio</option>
                        <option value="agosto">Agosto</option>
                        <option value="septiembre">Septiembre</option>
                        <option value="octubre">Octubre</option>
                        <option value="noviembre">Noviembre</option>
                        <option value="diciembre">Diciembre</option>
                    </select>
                    <br>
                    <button class="btn btn-primary" id="buscar">Buscar</button>
                </fieldset>            
        </div>
        <div id="mensaje" style="margin-left: 100px;display: none"><img src="images/cargando.gif" alt="cargando" />Buscando archivos, por favor espere un momento.</div>
        <div id="archive" style="margin-top: 30px;"></div>
    </body>
</html>