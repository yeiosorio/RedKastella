jQuery( document ).ready(function( $ ) {
		
		jQuery('.megamenu').hover(function(){
			var position = $(this).position();
			jQuery('.megamenu > ul').css({
				'width': $(this).parent().width(),
				'left' : -position.left
			});
		})
		
	})
