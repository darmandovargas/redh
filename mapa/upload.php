<?php // You need to add server side validation and better error handling here
 
$data = array();
$test = array();

/*
if ($handle = opendir('./uploads/')) {

    while (false !== ($file = readdir($handle))) { 
        $filelastmodified = filemtime($file);
		$test[] = array($file["name"]=>date("Y-m-d H:i:s", $filelastmodified));
		/*	
        if((time() - $filelastmodified) > 24*3600)
        {
           unlink($file);
        }
		*/
/*    }

    closedir($handle); 
}

var_dump($test);
exit();
 * 
 */
if(isset($_GET['files']))
{  
	$error = false;
	$files = array();
 
	$uploaddir = './uploads/';        
	foreach($_FILES as $file)
	{
                $nombre_archivo = $file["name"]; 
                $tipo_archivo = $file["type"]; 
                $tamano_archivo = $file["size"]; 
                $type = "";
                $type_error = "";
                switch($tipo_archivo) {
                                case "image/jpg":
                                $type = "jpg";    
                                case "image/jpeg":
                                $type = "jpeg";
                                break; 
                                case "image/gif":
                                $type = "gif";
                                break; 
                                case "image/png":
                                $type = "png";
                                break; 
                        }
                // Para añadir validacion kml $detected_kml = explode(".", $nombre_archivo);  && !($detected_kml[1] == 'kml')
                
                if (empty($type) || $tamano_archivo > 2000000) 
                {  
                        $error = true;
                        $type_error = "El archivo debe ser de tipo jpeg, jpg, gif, png y su tamaño no debe ser mayor a 2MB.";
                }else{
                    if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                    {
                            $files[] = $uploaddir .$file['name'];
                    }
                    else
                    {
                        $error = true;
                        $type_error = "Problemas al subir el archivo";
                    }                        
                }		
	}
	$data = ($error) ? array('error' => $type_error) : array('files' => $files);
}
else
{
	$data = array('success' => 'Form was submitted', 'formData' => $_POST);
}
 
echo json_encode($data);
 
?>