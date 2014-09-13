<!doctype html>

<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Login</title>

	<!-- CSS -->
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
	
	<style>
	.form-error{
		border: 1px solid #dd4b39 !important;
	}
	.error-msg {
		margin: 25px 20px;
		display: block;
		color: #dd4b39;
		line-height: 17px;
	}

		
	</style>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://malsup.github.com/jquery.form.js"></script> 
	<script> 
        // wait for the DOM to be loaded 
/*        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            $('#login').ajaxForm(function() { 
                alert("Thank you for your comment!"); 
                $(this).getU
            }); 
        }); 
*/      
        
        // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var options = { 
        target:        '#output',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 
 
        // other available options: 
        url:       'login.php',         // override for form's 'action' attribute 
        type:      'post',        // 'get' or 'post', override for form's 'method' attribute 
        //data:       [username:'eepereira', password:'eepereira'],  // post-submit callback 
        //dataType:  'text',        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#login').ajaxForm(options); 
}); 

function validate(formData, jqForm, options) { 
    // formData is an array of objects representing the name and value of each field 
    // that will be sent to the server;  it takes the following form: 
    // 
    // [ 
    //     { name:  username, value: valueOfUsernameInput }, 
    //     { name:  password, value: valueOfPasswordInput } 
    // ] 
    // 
    // To validate, we can examine the contents of this array to see if the 
    // username and password fields have values.  If either value evaluates 
    // to false then we return false from this method. 
 
    for (var i=0; i < formData.length; i++) { 
        if (!formData[i].value) { 
            //alert('Por favor ingrese un valor en el usuario y el password'); 
            return false; 
        } 
    } 
    //alert('Both fields contain values.'); 
}
 
// pre-submit callback 
function showRequest(formData, jqForm, options) {
	
	updateFields('all');
	
	if($("#username").val()=="")
		return false;
		
	if($("#password").val()=="")
		return false;	
	
	// Validate
	validate(formData, jqForm, options);
	
	 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
    //console.log(formData);
    
    /*console.log(jqForm);
    console.log(options);
 */
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
    //alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server
    //alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
    if(responseText == "success"){
    	$("#container").hide();    	
    	$("#welcome").fadeIn("slow");
    	
    	//$("#logout").html("<span id='logoutImage'><a href='#' onclick='logout();'><img src='/home/img/logout.png' width='22%'></a></span>").fadeIn("slow");
    	/*setTimeout(function (){
    		
    	},2000);
    	*/
    }else{
    	if($("#username").val()==""){
    		$("#username").addClass('form-error');
    	}
    	
    	if($("#password").val()==""){
    		$("#password").addClass('form-error');
    	}
    	
    	$("#output").html('<span class="error-msg">Error de usuario o password</span>').fadeIn("slow");
    	
    }
} 

 function updateFields(fieldName){ 
 		
 		if(fieldName=="username" || fieldName=="all"){
 			if($("#username").val()=="" ){
	    		$("#username").addClass('form-error');
	    		$("#output").html('<span class="error-msg">Debe ingresar un usuario</span>').fadeIn("slow");	
	    	}else{	    		
	    		$("#username").removeClass('form-error');
	    		$("#output").html('').fadeIn("slow");    		
	    	}	
 		} 
 		
 		if(fieldName=="password" || fieldName=="all"){
 			if($("#password").val()==""){
	    		$("#password").addClass('form-error');
	    		$("#output").html('<span class="error-msg">Debe Ingresar un password</span>').fadeIn("slow");
	    	}else{
	    		$("#output").html('').fadeIn("slow");
	    		$("#password").removeClass('form-error');	    		
	    	}
 		}
 		
 		if($("#username").val()=="" && $("#password").val()==""){
 			$("#output").html('<span class="error-msg">Debe Ingresar un usuario y un password</span>').fadeIn("slow");
 		}
    }
</script> 

</head>

	<!-- Main HTML -->
	
<body>
	<?php session_start(); ?>
	<div id="welcome" <?php echo ($_SESSION['sessid']== session_id())?'':'style="display: none"' ?> > 
		<img src='/home/img/banner_bienvenido.jpg'>
	</div>
		
	<!-- Begin Page Content -->
	
	<div id="container" <?php echo ($_SESSION['sessid']== session_id())?'style="display: none"':'' ?>>
		
		<form id="login" action="login.php" method="post">
		
		<label for="name">Usuario:</label>
		
		<input name="username" id="username" type="name" onkeypress ="updateFields('username');" onblur ="updateFields('username');">
 		
		<label for="username">Contraseña:</label>
		<!--
		<p><a href="#">Olvidó su contraseña?</a>
		-->
		<input name="password" id="password" type="password" onkeypress="updateFields('password');" onblur ="updateFields('password');">
		
		<div id="lower">
		<!--
		<input type="checkbox"><label class="check" for="checkbox">Mantenerme logueado</label>
		-->
		<input type="submit" value="Login">
		<div id="output"></div>		
		</div>
		
		</form>
		
	</div>
	
	
	<!-- End Page Content -->
	
</body>

</html>
	
	
	
	
	
		
	