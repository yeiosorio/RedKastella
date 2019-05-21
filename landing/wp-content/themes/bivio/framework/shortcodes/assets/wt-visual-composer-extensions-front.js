/* global jQuery:false, window */

/**
 * WhoaThemes Extend WPBakery Visual Composer Shortcodes
 *
 */
 
(function($) {
    "use strict";

    $(document).ready(function() {
		if($.fn.tooltip) {
			$('.wt_social_networks_sc').tooltip({ selector: "a[data-toggle=tooltip]"});
		}
        wt_extended_vc_shortcode_scripts();
    });

	$(window).load(function() {
		// Initialize after images are loaded
        wt_extended_vc_shortcode_scripts_on_load();
	});
	
	function wt_extended_vc_shortcode_scripts_on_load(container) {			
		if(typeof container == 'undefined'){ container = 'body';}
				
		if($.fn.wt_sc_portfolio)
		$('.wt_portfolio_wrapper', container).wt_sc_portfolio();
	}

	function wt_extended_vc_shortcode_scripts(container) {
		if(typeof container == 'undefined'){ container = 'body';}
		var win = $(window);
		
		// activates css animations once the elements appear into viewport but only if animations are enabled
		if($().waypoint && $.fn.wt_waypoints) {
			$('.wt_animate_if_visible', container).wt_waypoints();
			$('.wt_animate_if_almost_visible', container).wt_waypoints({ offset: '75%'});
		}
		// prevents hidden elements if switch from small screen to large screen
		win.smartresize(function(){
			if($().waypoint && $.fn.wt_waypoints) {
				$('.wt_animate_if_visible', container).wt_waypoints();
				$('.wt_animate_if_almost_visible', container).wt_waypoints({ offset: '75%'});
			}
		});		
		
		// Css Animations			
		if($.fn.wt_sc_animations)
		$('.wt_animations .wt_animate', container).wt_sc_animations();
		
		// Contact Forms Validation
		if($.fn.wt_forms_validate)
		$(".wt_contact_form_sc", container).wt_forms_validate();		
		
		// Progress Bars animations			
		if($.fn.wt_sc_progressBars)
		$('.wt_progress_bars_sc', container).wt_sc_progressBars();	
		
		// Bx Slider			
		if($.fn.wt_sc_bx_slider)
		$('.wt_bxslider', container).wt_sc_bx_slider();	
		
		// Counters			
		if($.fn.wt_sc_counters)
		$('.wt_counter_sc', container).wt_sc_counters();
		
		// Owl Carousel			
		if($.fn.wt_sc_owl_carousel)
		$('.wt_owl_carousel', container).wt_sc_owl_carousel();
		
		// Parallax			
		if($.fn.wt_sc_parallax)
		$('.wt-background-parallax', container).wt_sc_parallax();
		
		// Tooltip		
		if ($.fn.tooltip)
		$("[data-toggle='tooltip']").tooltip({ animation: true }); // This works with twitter bootstrap tooltip scipt
		
		if ($.fn.mb_YTPlayer) 
		$(".wt_youtube_player").mb_YTPlayer();
	}
	
	/* WhoaThemes Portfolio
	   --------------------------------------------------------- */	
	   
	$.fn.wt_sc_portfolio = function() {
		return this.each(function() {
			var container       = $(this),
				win             = $(window),
				
				wt_portf_height = function() {
					// Settting Same Height -> comented for isotope issues			
					if($.fn.equalHeights) { // if equalHeights function exists
						container.children().css('min-height','0');
						container.equalHeights();
					}	
				},		
				
				// Isotope Portfolio
				wt_portfolio    = function() {
					
					if ( $.isFunction($.fn.isotope) && container.hasClass('wt_isotope') ) { // if isotope scripts are called and portfolio is isotope type
					
						var wt_portf = function() { 
							container.isotope({
								itemSelector            : '.wt_portofolio_item',
								layoutMode              : 'fitRows',
								transitionDuration      : '0.8s'
								//animationEngine         : 'css',
								//itemPositionDataEnabled : true
							});
						}
						
						win.on("smartresize", wt_portf);
						wt_portf();
						
						/* ---- Filtering ----- */
						$('.sortableLinks a').click(function(){		
							var $this = $(this);			
							if ($this.hasClass('selected')) {
								return false;
							} else {				
								$('.sortableLinks .selected').removeClass('selected');				
								var selector = $this.attr('data-filter');
								$this.parent().next().isotope({ filter: selector });
								$this.addClass('selected');
								return false;			
							}
						});	
					}
				};					
			
			win.on("smartresize", wt_portf_height);
			wt_portf_height();
			
			wt_portfolio();
		});
	};
	
	/* Css Animations
	   --------------------------------------------------------- */	
	
	$.fn.wt_sc_animations = function() {
		return this.each(function() {
			var win            = $(window),
				container      = $(this),
				animation      = container.data('animation'),
				animationDelay = container.data('animation-delay'),
				
				wt_animations  = function() {
					var isSmallScreen  = $('html').hasClass('is_smallScreen');	
									
					if (!isSmallScreen && $().waypoint) {
						
						container.on('wt_start_animation', function() {
							if (animationDelay) {
								setTimeout(function(){
									container.addClass( animation + ' animated').css('visibility', 'visible'); 
								}, animationDelay);
							} else {
								container.addClass( animation + ' animated').css('visibility', 'visible'); 
							}
						});
					}
				};				
			
			win.on("smartresize", wt_animations);
			wt_animations();
		});
	};
	
	/* Contact Forms Validation
	   --------------------------------------------------------- */	
	   
	$.fn.wt_forms_validate = function() {
		return this.each(function(){
			var form = $(this),
				validator = form.validate();
			form.submit(function(e) {
				if (!e.isDefaultPrevented()) {
					var $id = form.find('input[name="contact_widget_id"]').val(),
						success = form.siblings('.success');
					$.post(this.action,{
						'to'      : $('input[name="contact_to_'+$id+'"]').val().replace("(at)", "@"),
						'name'    : $('input[name="contact_name_'+$id+'"]').val(),
						'email'   : $('input[name="contact_email_'+$id+'"]').val(),
						'subject' : $('input[name="contact_subject_'+$id+'"]').val(),
						'phone'   : $('input[name="contact_phone_'+$id+'"]').val(),
						'website' : $('input[name="contact_website_'+$id+'"]').val(),
						'country' : $('input[name="contact_country_'+$id+'"]').val(),
						'city'    : $('input[name="contact_city_'+$id+'"]').val(),
						'company' : $('input[name="contact_company_'+$id+'"]').val(),
						'sitename' : $('input[name="contact_sitename_'+$id+'"]').val(),
						'siteurl' : $('input[name="contact_siteurl_'+$id+'"]').val(),
						'content' : $('textarea[name="contact_content_'+$id+'"]').val()
					},function(){
						form.fadeOut('fast', function() {
							success.show();
						});
						success.children('button').on('click', function() {
							success.clone().insertBefore(form).hide();
							form.find('input[type="text"], input[type="email"], input[type="tel"], input[type="url"], textarea').val('');
							setTimeout(function(){
								form.fadeIn('fast');
							}, 800);	
						});
					});
					e.preventDefault();
				}
			});
			form.find( '.reset-form' ).on( 'click' ,function(e) {
				e.preventDefault();		
				form.find('input[type="text"], input[type="email"], input[type="tel"], input[type="url"], textarea').val('');
				validator.resetForm();
			});
		});
	};
		
	/* Progress Bars Animations
	   --------------------------------------------------------- */	
	   	
	$.fn.wt_sc_progressBars = function() {
		return this.each(function() {
			var container = $(this), 
				elements = container.find('.wt_progress_bar'),
				
				wt_progress_bars = function() {				
					elements.each(function(i) {
						var element      = $(this),
							progress_bar = element.children('.wt_progress_bar_content'),
							percentage   = progress_bar.data('percentage');
							
						progress_bar.css({'width':'0%', 'visibility':'visible'});
						setTimeout(function(){ 
							progress_bar.animate({
								width: percentage+'%'
							}, 'slow');
						}, (i * 200));
						
					});
				};
				
			if($().waypoint) { // if aniamtions are enabled	
				container.on('wt_start_animation', function() { wt_progress_bars(); });
			} else {
				wt_progress_bars();
			}
		});
	};
	
	/* Bx Slider
	   --------------------------------------------------------- */	
		
	$.fn.wt_sc_bx_slider = function() {
		return this.each(function() {
			var $bxslider = $(this),
				_initBxslider;
		
			if( $bxslider.length ) {
				_initBxslider = function initBxslider( bxsliders ) {
					bxsliders.each(function() {
						var $this  = $(this),
							$customNav = $this.data('bx-customnav');
						function bxslider() {									
							$this.bxSlider({
								pagerCustom : $customNav ? "#"+$this.data('bxcustomnav') : null,
								auto        : $this.data('bx-autoplay'),
								autoHover   : true,
								pager       : $this.data('bx-pagernav'),
								pause       : $this.data('bx-pause'),
								mode        : $this.data('bx-mode'),
								controls    : $this.data('bx-controlnav'),
								speed       : $this.data('bx-speed'),
								easing      : $this.data('bx-easing')
							});
						}
						
						bxslider();
					});
				};
				_initBxslider( $bxslider );		
			}		
		});
	};
	
	/* Counters
	   --------------------------------------------------------- */	
	   
	$.fn.wt_sc_counters = function() {
		return this.each(function() {
			var container = $(this),
				percent   = container.data('percent'),
				target    = container.find('.stat-count'),			
				
				wt_counters = function() {
					if ( $().countTo ) {
						target.delay(6000).countTo({
							from: 0,
							to: percent,
							speed: 3000,
							refreshInterval: 50
						});
					}
				};
				
			if($().waypoint) { // if aniamtions are enabled	
				container.on('wt_start_animation', function() { wt_counters(); });
			} else {
				wt_counters();
			}
		});
	};
	
	/* Owl Carousel
	   --------------------------------------------------------- */	
	   
	$.fn.wt_sc_owl_carousel = function() {
		return this.each(function() {
			var $carousel = $(this),
				_initCarousel;
	
			if( $carousel.length ) {
				_initCarousel = function initCarousel( carousels ) {
					carousels.each(function() {
						var $this  = $(this);
						
						function carousel() {									
							$this.owlCarousel({
								slideSpeed        : $this.data('owl-speed'), 
								paginationSpeed   : $this.data('owl-pagspeed'), 
								autoPlay          : $this.data('owl-autoplay'),
								stopOnHover       : $this.data('owl-stoponhover'),
								items             : $this.data('owl-items'),
								itemsDesktop      : [1170,$this.data('owl-itemsdesktop')],
								itemsDesktopSmall : [960,$this.data('owl-itemssmalldesktop')],
								itemsTablet       : [768,$this.data('owl-itemstablet')],
								itemsMobile       : [480,$this.data('owl-itemsmobile')],
								itemsMobileSmall  : [360,$this.data('owl-itemsmobilesmall')],
								navigation        : $this.data('owl-navigation'),
								pagination        : $this.data('owl-pagination'),
								navigationText    : false
							});
							
							// Carousel Custom Navigation
							$(".wt_owl_prev").click(function(){
								$this.trigger('owl.next');
							})
							
							$(".wt_owl_next").click(function(){
								$this.trigger('owl.next');
							})
						}
						
						carousel();
					});
				};
				_initCarousel( $carousel );		
			}	
		});
	};
	
	/* Parallax
	   --------------------------------------------------------- */	
	   
	$.fn.wt_sc_parallax = function() {
		return this.each(function() {
			var $parallax = $(this),
				is_mobile = new IsMobile(),
				testMobile;
				
			$(window).bind('load', function () {
				testMobile = is_mobile.any();
				if (testMobile === null) {		
					$parallax.parallax("50%", "0.5");
				}
			});
			
		});
	};
	
	// -------------------------------------------------------------------------------------------
	// HELPER FUNCTIONS
	// -------------------------------------------------------------------------------------------
	
			
	/* Equal Height Functions
	   --------------------------------------------------------- */	
	   	
	(function($) {
		$.fn.equalHeight = function() {
			var tallest = 0;
			$(this).each(function(){
				var thisHeight = $(this).height();
				if (thisHeight > tallest) { tallest = thisHeight; }
			});
			$(this).height(tallest);
		};
	})(jQuery);
	
	
	(function($) {
		$.fn.equalHeights = function() {
			$(this).each(function(){
				var currentTallest = 0;
				$(this).children().each(function(){
					if ($(this).outerHeight() > currentTallest) { currentTallest = $(this).outerHeight(); }
				});
				$(this).children().css({'min-height': currentTallest}); 
			});
			return this;
		};
	})(jQuery);
	
	/* Detects Mobiles
	   --------------------------------------------------------- */	
	   
	function IsMobile(options) {
		var isMobile = {
			Android: function() {
				return navigator.userAgent.match(/Android/i);
			},
			BlackBerry: function() {
				return navigator.userAgent.match(/BlackBerry/i);
			},
			iOS: function() {
				return navigator.userAgent.match(/iPhone|iPad|iPod/i);
			},
			Opera: function() {
				return navigator.userAgent.match(/Opera Mini/i);
			},
			Windows: function() {
				return navigator.userAgent.match(/IEMobile/i);
			},
			any: function() {
				return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
			}
		};
		options = $.extend(isMobile, options);
		return options;
	}
	
	/* Waipoint script when the elements appear into viewport
		( place this function after others animation functions )
	   --------------------------------------------------------- */	
	   
	$.fn.wt_waypoints = function(opts)	{		
		
		if(!$('html').is('.csstransforms')) return;
		
		var defaults = { offset: 'bottom-in-view' , triggerOnce: true},
			options  = $.extend({}, defaults, opts);
		
		return this.each(function() {
			var element = $(this);
		
			setTimeout(function() {
				element.waypoint(function() {
					$(this).addClass('wt_was_animated').trigger('wt_start_animation');		
				}, options );
		
			},100);
			
		});
	};

})(jQuery); // End