$(document).ready(function() {
	//$('#website').val(window.parent.document.location);
	
	$('#contact-form').jqTransform();
	
	$("button").click(function(){

		$(".formError").hide();

	});
	
	var use_ajax = true;
	$.validationEngine.settings = {};

	$("#contact-form").validationEngine({
		inlineValidation : true,
		promptPosition : "centerRight",
		success : function() {
			use_ajax = true;
		},
		failure : function() {
			use_ajax = false;
		}
	});
	
	//this variable will prevent the 
	hasSubmited = false;

	$("#contact-form").submit(function(e) {
		loadingObj = $('.loader');//$('#loading_black'); 
		speed = 'slow';		
		//Show loading
		loadingObj.fadeIn(speed);

		//Avoid an infinitive loop
		if (hasSubmited)
			return false;	
		responseField = $("input#recaptcha_response_field").val();
		
		if (responseField != "" && use_ajax) {// if there is a captcha text and the other fields are fine then			
			challengeField = $("input#recaptcha_challenge_field").val();			
			//validates the captcha text
			$.ajax({
				async : 'false',
				url : "captchaValidation.php",
				type : "POST",
				data : {
					"response" : responseField,
					"challenge" : challengeField
				},
				success : function(data) {
					console.log("RESPONSE CAPTCHA");
					console.log(data);
					//response of the captcha validation (was much easier this way than through the library)
					response = jQuery.parseJSON(data);					
					if (!Boolean(Number(response.status))) {
						//reload captcha and empty field
						Recaptcha.reload();						
						//set the validation tooltip manually (was much easier this way than through the library)
						errorMsg = '<div id="captchaValidation" class="formError recaptcha_response_fieldformError" style="top: 319px; left: 407px; opacity: 0.87;"><div class="formErrorContent">* Wrong Captcha Text<br></div><div class="formErrorArrow"></div></div>';
						$('body').append(errorMsg);	
						//Hide loading
						loadingObj.fadeOut(speed);					
					} else {
						//submit the form setting the hasSubmited as true to prevent the infinitve loop
						hasSubmited = true;
						//Send information via email					
						$.post('submit.php',
							{ 
								name: $("#name").val(),							 
							  	email: $("#email").val(),
							  	subject: $("#subject").val(),
							  	message: $("#message").val()						  	
							},							 				
							function(data){
								$("#contact-form").hide('slow').after('<div align="center"><h1>Su mensaje ha sido enviado con éxito, en breve le responderemos !</h1></div>');
								//Hide loading
								loadingObj.fadeOut(speed);
								//document.forms["contact-form"].submit();								
						});
					}
				}
			});
		}else{
			//Hide loading
			loadingObj.fadeOut(speed);
		}		
		e.preventDefault();
	})
}); 