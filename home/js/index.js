var isMapOutOfDate = true;

/**
 * Close the link
 *
 */
function closePage() {
	$('.close').click();
}

/**
 * This function will logout and will hide the icon smoothly, if the logout is
 * successful, then the lookForSession function will start looking for a new session
 */
function logout() {
	showLogout(false);
	isSession = true;
	$.ajax({
		type : "POST",
		url : "content/login/logout.php"
	}).done(function(msg) {
		session = false;
		initialize();
		isMapOutOfDate = true;
	});
}

/**
 * Show or Hide logout icon
 */
function showLogout(showIcon) {
	if (showIcon) {
		$("#logout").fadeIn("slow"); 
	} else {
		$("#logout").fadeOut("slow");
	}
}

function showTime() {
	$("#estado_tiempo").trigger("click");
}

function showNotice() {
	$("#notice_boton").trigger("click");
}

function showRecursos() {
	$("#recursos_boton").trigger("click");
}