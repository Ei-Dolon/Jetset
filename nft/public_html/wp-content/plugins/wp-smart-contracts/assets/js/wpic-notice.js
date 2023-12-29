
jQuery(document).ready(function($) {
	$("#wpic-no-show").click(function() {
		document.cookie = "notice_hide_2_0_9=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
		$("wpic-notification").hide();
		$( "#wpic-notification" ).fadeOut( "slow", function() {});
	});
});
