jQuery(document).ready(function() {
	var slider = jQuery('#flexSlider');
	var thumbsCarousel = jQuery('#thumbsCarousel');
	var thumbs = slideShow['controlNavThumbs'];
	var thumbsSlider = slideShow['controlNavThumbsSlider'];
	var use_css = Modernizr.touch ? true : false;
	var effect = slideShow['animation'];
	if (effect == "fade") {
		effect = Modernizr.touch ? "slide" : "fade";
	}
		
	if (thumbsSlider) {
		thumbsCarousel.imagesLoaded(function() {
			thumbsCarousel.flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 190,
				itemMargin: 5,
				asNavFor: slider
			});	
		}); 
	}
	slider.imagesLoaded(function() {
		slider.flexslider({
			animation:effect,
			easing:effect=="slide" ? slideShow['easing'] : '',
			useCSS: use_css,
			direction:slideShow['direction'],
			animationSpeed:slideShow['animationSpeed'],
			slideshowSpeed:slideShow['slideshowSpeed'],
			directionNav:slideShow['directionNav'],
			controlNav:thumbs && !thumbsSlider ? 'thumbnails' : slideShow['controlNav'],
			pauseOnAction:slideShow['pauseOnAction'],
			pauseOnHover:slideShow['pauseOnHover'],
			slideshow:slideShow['slideshow'],
			animationLoop:slideShow['animationLoop'],
			sync: "#thumbsCarousel",
			start: function(slideshow) {
				slider.removeClass('flex-preload');
				if (slideshow.hasClass('wt_slider')) {
					var currentS = slideshow.slides.eq(slideshow.currentSlide),
						flexCaption = currentS.find('.flex-caption');
					currentS.addClass('animated');
					
					flexCaption.css('margin-top', function() {
						var captionH = parseInt(flexCaption.height() / -2);
						return captionH;
					});
				}
			},
			before : function(slideshow){
				if (slideshow.hasClass('wt_slider')) {
					var currentS = slideshow.slides.eq(slideshow.currentSlide),
						elem = currentS.find('.flex-caption h2, .flex-caption h3, .flex-caption a.wt_button').length;
						currentS.removeClass('animated');
				  
					currentS.delay((elem * 450) + 500);
				}
			},
			after : function(slideshow){
				if (slideshow.hasClass('wt_slider')) {
					var currentS = slideshow.slides.eq(slideshow.currentSlide),
						flexCaption = currentS.find('.flex-caption'),
						elem = currentS.find('.flex-caption h2, .flex-caption h3, .flex-caption a.wt_button').length;
							
					slideshow.slides.eq(slideshow.currentSlide).addClass('animated');
					flexCaption.css('margin-top', function() {
						var captionH = parseInt(flexCaption.height() / -2);
						return captionH;
					});
					
					currentS.delay((elem * 450) + 500);
				}
			}
		});
	});			
});