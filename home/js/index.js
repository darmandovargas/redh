var session = false;
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
		$("#logout").removeClass("waitLogout").addClass("logout").hide().html("<span id='logoutImage'><a href='#' onclick='logout();'><img src='/home/img/logout_blue.png' width='6%' title='Cerrar Sesión'></a></span>").fadeIn("slow");
	} else {
		$("#logout").removeClass("logout").addClass("waitLogout").fadeOut("slow").html("<img src='/home/img/wait_logout.gif' width='70%'>");
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