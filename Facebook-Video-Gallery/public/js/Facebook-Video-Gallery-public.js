(function( $ ) {
	'use strict';

	console.log("test");
	$(document).ready(function(){

		window.videomodal = document.getElementById('videoModal');
		window.videoclose = document.getElementsByClassName("close")[0];
		window.onclick = function(event) {
			if (event.target == window.videomodal) {
				window.videomodal.style.display = "none";
			}
		}

		$(".video-div").click(function() {
			window.videomodal.style.display = "block";
			$("#videoModal .modal-content").html("<iframe src=\"https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com"+ $(this).data("permalink")+ "\" style=\"border:none;overflow:hidden\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\" allowFullScreen=\"true\"></iframe>");
			window.videoclose.onclick = function() {
				window.videomodal.style.display = "none";
				
			}
		});
	});
	
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );