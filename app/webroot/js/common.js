// file: /app/webroot/js/common.js
$(document).ready(function(){
	// fadeout flash messages on click
	//$('.flash_failure').click(function(){
	//	$(this).parent().fadeOut();
	//return false;
	// fade out good flash messages after 3 seconds
	$('.flash_success').animate({opacity: 1.0}, 3000).fadeOut();
});