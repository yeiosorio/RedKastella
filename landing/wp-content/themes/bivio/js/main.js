/* global jQuery:false, window, Modernizr, Cufon */

(function($) {
    "use strict";

	$(window).load(function(){
		// Initialize after images are loaded
		var wrap              = $('body'),
			wt_loader         = wrap.hasClass('wt_loader');
		
		if (wt_loader) {
			$(".wt_loader_html").delay(200).fadeOut();
			$("#wt_loader").delay(500).fadeOut("slow");
		}			
		
		wt_functions_call_on_load();		
		
	});
	
	$(document).ready(function() {
		var win = $(window);
				
		wt_functions_call();
		
		// activates the nice scrolll function
		var $niceScroll = $('body').attr('data-nice-scrolling');
		if ( $niceScroll == 1 && win.width() > 690 ){ niceScrollInit(); }
		
		$("#wt_wrapper").fitVids();
				
		$('.no_link').click(function(){
			return false;		
		});
		
		$('.wt_search_form').each(function(){
			this.reset();
		});
									
		if ($.fn.elastic) { $('#commentform textarea, .wt_contact_form textarea').elastic(); }
				
		if ($.fn.mb_YTPlayer) { 
		 var onMobile = false;
		 if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) { onMobile = true; }
		 if( ( onMobile === false ) ) {
			$(".wt_youtube_player").mb_YTPlayer();
			$('.wt_bg_video_mobile').hide();
		  } else {
			/* hide player and video volume button */
			$(".wt_youtube_player").hide();
			$(".video-volume").hide();
		  }
			//$(".wt_youtube_player").mb_YTPlayer(); 
		}
																			
		html5_video();	
		wt_scroll_top();	
		
	});	
	
	/* ----- Scrolling Effect ----- */
	$(window).scroll(function(container) {
		if(typeof container === 'undefined') {
			container = 'body';
		}
		var wrapp = $(container);
		
		if($.fn.wt_scroll_effect)
		wrapp.wt_scroll_effect();
		
	});
		
	function wt_functions_call_on_load(container) {
		if(typeof container == 'undefined'){ container = 'body';}
		var wrapp = $(container);	
		
		// admin bar sticky header
		if($.fn.wt_wp_admin_bar)	
		wrapp.wt_wp_admin_bar();	
	}
	
	function wt_functions_call(container){
		if(typeof container == 'undefined'){ container = 'body';}
		var wrapp = $(container);
				
		// adding mobile class on smaller screens
		if($.fn.is_smallerScreen)
		wrapp.is_smallerScreen();	
		
		// Hiding your email addresses from spam harvesters
		if($.fn.mailto)
		$('.nospam').mailto();	
				
		// navigation
		if($.fn.wt_navigation)
		wrapp.wt_navigation(); // used for normal navigation
		
		if($.fn.wt_one_page_nav)
		wrapp.wt_one_page_nav(); // used for one page navigation			
		
		// responsive navigation
		if($.fn.wt_responsive_nav)
		wrapp.wt_responsive_nav();	
			
		// add 'firstItem' / 'lastItem' classes to menus
		if($.fn.wt_menu)
		$("#nav ul, .widget_nav_menu ul", container).wt_menu();
		
		// add markers and classes to parent lists
		if($.fn.wt_menu_markers)	
		$("#nav li:has(ul.sub-menu), .widget_subnav li:has(ul)", container).wt_menu_markers();		
		
		// fullscreen slider
		if($.fn.wt_fullscreen_slider)
		$(".wt_fullscreen_slider", container).wt_fullscreen_slider();
												
		// side navigation effect
		if($.fn.wt_side_nav)	
		$(".wt_side-nav li", container).wt_side_nav();		
		
		// parallax effect
		if($.fn.wt_parallax)
		wrapp.wt_parallax();
		
		// prettyPhoto lightbox
		if($.fn.wt_lightbox)	
		wrapp.wt_lightbox();		
		
		// google maps
		if($.fn.wt_sc_googleMaps)
		$('.wt_g_map', container).wt_sc_googleMaps();							
		
	}	
	
	/* ----- Check If Exists ----- */
	$.fn.exists = function () {
		return this.length !== 0;
	}; // Usage: $("#notAnElement").exists();
	
	/* Navigation
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_navigation = function() {
			var menu        = $('#nav.wt_nav_top > ul'),
				nav_side    = $('#nav.wt_nav_side > ul');
				//first_level_items = menu.eq(0).find('>li a');
				
			menu.nav({
				child: {
					beforeFirstRender: function() {
						if ($(this).find('.cufon').length > 0) {
							Cufon.replace($('> a', this));
						}
					}
				},
				root: {
					effect: 'fade',
					beforeHoverIn: function() {
						if ($(this).find('.cufon').length > 0) {
							Cufon.replace($('> a', this));
						}
					},
					beforeHoverOut: function() {
						if ($(this).find('.cufon').length > 0) {
							Cufon.replace($('> a', this));
						}
					},					
					afterHoverIn: function() {
						$(this).trigger('wt_menuopen');
					},					
					afterHoverOut: function() {
						$(this).trigger('wt_menuclose');
					},
				}
			});
			
			// Side Navigation
			nav_side.append('<a href="#" class="nav-button"><span>Menu</span></a><div class="nav-overlay"></div>');
			$('.nav-button').on('touchstart click', function(e){
				e.stopPropagation();
				e.preventDefault();
				if ( nav_side.hasClass('open') ) {
					nav_side.removeClass('open');
				} else {
					nav_side.addClass('open');
					$('.nav-overlay').on('touchstart click', function(){
						nav_side.removeClass('open');
					});
				}
			});
			// End Slide Navigation Menu
						
		};
	})(jQuery);
	
	/* One Page Navigation
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_one_page_nav = function() {
			var stickyH = $("#wt_wrapper").is('.wt_stickyHeader');
			/* --- One page navigation --- */
			$('#nav ul a[href*=#], .wt_scroll').click(function() {
				if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
					var $target = $(this.hash); // Check the section area id
					$target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
					if ($target.length) {
						var targetOffset = $target.offset().top; // the top of the section area
						if (stickyH) {
							$('html,body').animate({
								scrollTop : targetOffset - $('.wt_nav_top #wt_header').outerHeight() // adding sticky header height
							}, 1100, 'easeInOutExpo');
						} else {
							$('html,body').animate({
								scrollTop : targetOffset
							}, 1100, 'easeInOutExpo');
						}
						return false;
					}
				}
			});
			
			/* --- Highlight menu links when scrolling --- */
			$(document).scroll(function() {
				var pos = $(this).scrollTop();
				$(".wt_section_area").each(function() {
					var id_slide          = $(this).attr("id"),
						element_menu      = $('#nav ul li a[href$="#' + id_slide + '"]:first'),						
						resp_element_menu = $('#wt-responsive-nav li a[href$="#' + id_slide + '"]:first');
						
					if ($(this).offset().top <= pos + 60 && element_menu.length > 0) {
						$("#nav ul li").removeClass("current_page_item");
						element_menu.parent().addClass("current_page_item");
						
						$("#wt-responsive-nav li").removeClass("current_page_item");
						resp_element_menu.parent().addClass("current_page_item");
					}
				});
			});
		};
		
	})(jQuery);
	
	/* Responsive Navigation
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_responsive_nav = function() {
			var win = $(window), header = $('.responsive #wt_header');
	
			if(!header.length) {
				return;
			}
	
			var menu              = header.find('#nav ul:eq(0)'),
				first_level_items = menu.find('>li').length,
				switchWidth;
			
			if ( header.hasClass('wt_resp_nav_under_991') ) { 
				switchWidth = 975;
			} else if ( header.hasClass('wt_resp_nav_under_767') ) {
				switchWidth = 767;
			} else {
				switchWidth = 767;
			}
	
			if(first_level_items > 8) {
				switchWidth = 975;
			}
			// if there is no menu selected
			if(header.is('.drop_down_nav')) {
				menu.mobileMenu({
					switchWidth: switchWidth,
					topOptionText: $('#nav').data('select-name'), // first option text
					indentString: 'ontouchstart' in document.documentElement ? '- ' : "&nbsp;&nbsp;&nbsp;"  // string for indenting nested items
				});
			} else {
				var container           = $('#wt_wrapper'),
					header_wrapper      = container.find('#wt_headerWrapper'),
					responsive_nav_wrap	= $('<div id="wt_responsive_nav_wrap"><div class="container"><div class="row"></div></div></div>'),
					responsiveNavWrap	= responsive_nav_wrap.find('.row'),
					show_menu		    = $('<a id="responsive_nav_open" href="#" class=""><i class="entypo-menu"></i></a>'),
					show_menu_icon  	= show_menu.find('i'),
					
					stickyH             = container.is('.wt_stickyHeader'),
					no_stickyH_onSS     = container.is('.wt_noSticky_on_ss'),
					nav_side            = container.is('.wt_nav_side'),
					
					responsive_nav      = menu.clone().attr({id:"wt-responsive-nav", "class":""}),
					menu_added          = false;
									
					responsive_nav.find('ul').removeAttr("style");
					responsive_nav.find('.notMobile').remove();
					
					if (stickyH && !no_stickyH_onSS) {
						header.append(responsive_nav_wrap);
					} else {
						responsive_nav_wrap.insertAfter(header_wrapper);
					}	
						
					// hiding all sub-menus		
					responsive_nav.find('li').each(function(){
						var el = $(this);
						if(el.find('> ul').length > 0) {
                             el.find('> a').append('<i class="wt_has_child fa-angle-down"></i>');
						}
					});
	
					responsive_nav.find('li:has(">ul") > a > i.wt_has_child').click(function(){
						var el        = $(this),     // the right angle icon
							el_link   = el.parent(), // the link <a> which wrap the icon
							el_parent = el_link.parent().find('> ul');						
							
						el_link.toggleClass('active');
						el_parent.stop(true,true).slideToggle();
						
						if ( el_link.hasClass('active') ) {
							el.removeClass('fa-angle-down').addClass('fa-angle-up');
						} else {
							el.removeClass('fa-angle-up').addClass('fa-angle-down');							
						}
						
						return false;
					});
					// end hiding all sub-menus						
										
					show_menu.click(function(){		
						if(container.is('.show_responsive_nav')) {
							container.removeClass('show_responsive_nav');
							show_menu_icon.removeClass('entypo-cancel-circled').addClass('entypo-menu');
						} else {
							container.addClass('show_responsive_nav');
							show_menu_icon.removeClass('entypo-menu').addClass('entypo-cancel-circled');
						}
						
						responsive_nav_wrap.stop(true,true).slideToggle(500);
						return false;
					});					
					
					// start responsive one page navigation	
					/*
					// $('[class^="whatever-"], [class*=" whatever-"]')
					var $resp_menu_items = responsive_nav.find('a[class^="level-"]'),
						respNav;
					
					if( $resp_menu_items.length ) {
						respNav = function initRespNav( respMenuItems ) {
							respMenuItems.each(function() {
								var $this         = $(this),
									isOnePageItem = $this.attr('href').match("^#") ? true : false;
									
								// if responsive navigation is opened and the link from menu is not external
								if (isOnePageItem) {
									$this.click(function() {	
										if (container.is('.show_responsive_nav')) {
											// Hiding the responsive navigation
											container.removeClass('show_responsive_nav');
											show_menu_icon.removeClass('entypo-cancel-circled').addClass('entypo-menu');
											responsive_nav_wrap.stop(true,true).slideToggle(500);
											// End hiding the responsive navigation
											
											if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
												var $target = $(this.hash); // Check the section area id
												$target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
												if ($target.length) {
													
													var targetOffset = $target.offset().top; // the top of the section area
													
													if (nav_side && stickyH && !no_stickyH_onSS) {
														$('html,body').animate({ // adding sticky header height
															scrollTop : targetOffset - $('.wt_nav_side #wt_header').outerHeight() + responsive_nav_wrap.height()
														}, 1100, 'easeInOutExpo');
													} else if (stickyH && !no_stickyH_onSS) {
														$('html,body').animate({ // adding sticky header height
															scrollTop : targetOffset - $('.wt_nav_top #wt_header').outerHeight() + responsive_nav_wrap.height()
														}, 1100, 'easeInOutExpo');
													} else {
														$('html,body').animate({ // adding responsive menu relative height
															scrollTop : targetOffset - responsive_nav_wrap.height()
														}, 1100, 'easeInOutExpo');
													}
																								
													return false; // doesn't show the "#id_section_name" in the url
												}
											}		
																				
										}
									});	
								}
							});
						};
						respNav( $resp_menu_items );				
					}
					*/
					// end responsive one page navigation	
						
					var set_visibility = function() {
						if(win.width() >= switchWidth) {
							responsive_nav_wrap.css("display","none");
							show_menu.css("display","none");
							show_menu_icon.removeClass('entypo-cancel-circled').addClass('entypo-menu');							
							header.removeClass('small_device_active');
							container.removeClass('show_responsive_nav');
							if (stickyH && no_stickyH_onSS) {
								container.removeClass('wt_noSticky_on_ss');
							}
						} else {
							header.addClass('small_device_active');
							show_menu.css("display","block");
							if(!menu_added) {
								var before_menu = header.find('#nav');
								show_menu.insertBefore(before_menu);
								responsive_nav.prependTo(responsiveNavWrap);
								menu_added = true;							
								
								// init google maps after menu was cloned								
								if(menu_added && $('#wt-responsive-nav .wt_g_map').length && $.fn.wt_sc_googleMaps) {
									$('.wt_g_map', $('#wt-responsive-nav')).wt_sc_googleMaps();
									// console.log('responsive menu contains maps');
								}								
							}							
							if (stickyH && no_stickyH_onSS) {
								container.addClass('wt_noSticky_on_ss');
							}	
						}
					};
	
					win.on("smartresize", set_visibility);
					set_visibility();
			}	
		};
			 
	})(jQuery);
		
	/* Add 'firstItem' / 'lastItem' Classes To Menu Lists
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_menu = function() {
			$(this).each(function(){
				$(this).find('li:first-child').addClass('firstItem');
				$(this).find('li:last-child').addClass('lastItem');
				//$(this).find('.firstItem a').attr({href:"#wt_wrapper"})	
			});
			$(this).contents("a").removeAttr('title');	
		};
	})(jQuery);
		
	/* Add Markers & Classes To Parent Lists
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_menu_markers = function() {
			$(this).each(function(){
				var $isMegaMenu = $(this).parents('.wt_megamenu:first'),
					$isSideNav  = $(this).parents('.wt_side-nav:first');
				$(this).addClass("hasChild");
				if (!$isMegaMenu.length && !$isSideNav.length ) {
					$(this).find("> a").append('<span class="marker">+</span>');
				}
			});
			$(".widget_subnav li:has(ul)").find(">:eq(1)").prepend('<span class="marker">+</span>');
		};
	})(jQuery);	
		
	/* Side Navigation Effects For Menu Widget
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_side_nav = function() {
			$(this).each(function(){
				$(this).hoverIntent({
				//$('.wt_side-nav li').hoverIntent({
					over: function() {
						if($(this).find('> .children').length >= 1) {
							$(this).find('> .children').stop(true, true).slideDown('slow');
						}
					},
					out: function() {
						if(!$(this).find('.current_page_item').length) {
							$(this).find('.children').stop(true, true).slideUp('slow');
						}
					},
					timeout: 500
				});
			});	
		};
	})(jQuery);	
	
	/* Admin bar sticky header
	================================================== */	
	(function($) {
		$.fn.wt_wp_admin_bar = function() {
			
			var win           = $(window),
				isSticky      = $('.wt_stickyHeader'),
				header        = isSticky.find('#wt_header'),		
				sticky_header = function() {
					if (jQuery('#wpadminbar').length > 0 && win.width() >= 783) {
						header.css({'top' : 28 });
					}else if (jQuery('#wpadminbar').length > 0 && win.width() < 783) {
						header.css({'top' : 46 });
						jQuery('#wpadminbar').css({'position' : 'fixed' });
					}else {
						header.css({'top' : 0 });}
				};			
			
			if (isSticky.length) {
				isSticky.imagesLoaded(function() {			
					sticky_header();
					win.smartresize(function(){
						sticky_header();
					});
				});
				
			}
											
		};
	})(jQuery);

	
	/* Parallax
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_parallax = function() {	
			var testMobile,
				is_mobile = new IsMobile();
				
			$(window).bind('load', function () {
				parallaxInit();
			});
			//parallaxInit();	
			
			function parallaxInit() {
				testMobile = is_mobile.any();
				if (testMobile === null) {					
					$('.wt_parallax').each(function(){
						//var parallax = "#"+$(this).attr('id');			
						//$(parallax).parallax("50%", "0.5");		
						$(this).parallax("50%", "0.5");	
					});
				}
			}
		};
	})(jQuery);
		
	/* Scrolling Effect
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_scroll_effect = function() {
			var win              = $(window),
				win_height       = $(window).height(),
				wt_scroll        = win.scrollTop(),
				container        = $('#home'),
				container_height = container.outerHeight(),
				separator        = $('.wt_separator'),
				el               = separator.find('.quotes_box'),
				scroll_effect    = function() {			
					if (win.width() > 767 ) {
						container.find('.home_box').css({'opacity' : 1 - (wt_scroll / container_height)});
						el.each(function(){
							var $this           = $(this),		
								el_height       = $this.outerHeight(),
								el_offset       = $this.offset().top;
													
							$this.css({'opacity' : 1 - ( (wt_scroll+win_height-el_offset) / (el_offset+el_height) ) });
						});
					} else {
						container.find('.home_box').css({'opacity' : 1 });
						el.css({'opacity' : 1 });
					}
				};
				win.on("smartresize", scroll_effect);
				scroll_effect();
		};
	})(jQuery);
		
	/* Adding Mobile ( "is_smallScreen" ) Class
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.is_smallerScreen = function() {
			var win               = $(window),
				container         = $('html'),
				isResponsiveMode  = container.hasClass('responsive'),	
				check_screen      = function() {
					
					if( win.width() < 975 && isResponsiveMode ){
						container.addClass('is_smallScreen');
					} else {
						container.removeClass('is_smallScreen');
					}
				};
				win.on("smartresize", check_screen);
				check_screen();
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
		
	/* Hiding Your Email Addresses From Spam Harvesters
	   --------------------------------------------------------- */	
	   
	$.fn.mailto = function() {
		return this.each(function(){
			var email = $(this).html().replace(/\s*\(.+\)\s*/, "@");
			$(this).before('<a href="mailto:' + email + '" rel="nofollow" title="Email ' + email + '">' + email + '</a>').remove();
		});
	};
	
	/* Fade Custom Elements
	   --------------------------------------------------------- */	
	   	
	$.fn.fadeElement = function() {
		return this.each(function(){			
			$(this).animate({opacity:0.7},"fast");
			$(this).hover(function(){
				$(this).animate({opacity:1.0},"fast");
				},function(){
				$(this).animate({opacity:0.7},"fast");
			});			
		});
	};				
		
	/* PrettyPhoto Lightbox
	   --------------------------------------------------------- */	
	   
	(function($) {
		$.fn.wt_lightbox = function() {
			$('a[data-rel]').each(function() {                
				$(this).attr('rel', $(this).data('rel'));             
			});
			
			var win       = $(window),				
				lightbox  = function($attributes) {				
					$($attributes).prettyPhoto({
						"theme": 'pp_default' /* light_square / light_rounded / dark_square / dark_rounded / facebook */ ,
						"deeplinking": false,
						"social_tools": false																
					});
				};				
			
			lightbox("a[rel^='prettyPhoto'], a[rel^='lightbox'], a[rel^='wt_lightbox']");
			win.on( "smartresize", lightbox("a[rel^='prettyPhoto'], a[rel^='lightbox'], a[rel^='wt_lightbox']") );									
		};
	})(jQuery);	
	
	/* Scrolling To Top
	   --------------------------------------------------------- */	
	   
	function wt_scroll_top(){
			
		if ($('body').is('.wt-top')) {
			$('body').append('<a href="#top" id="wt-top"><i class="fa-angle-up"></i></a>');
		}
		
		var win           = $(window),
        	scroll_top    = $('#wt-top'),
			
        	set_status    = function() {
				var wt_st = win.scrollTop();
				
				if(wt_st < 200) {
					scroll_top.removeClass('wt_top_btn');
				} else if (!scroll_top.is('.wt_top_btn')) {
					scroll_top.addClass('wt_top_btn');
				}
			};
			
		win.scroll(set_status);
		set_status();
		
		// scrolling to top
		$('#wt-top, .wt_top').click(function() {
			var $delay = win.scrollTop();
			$('body,html').animate({
				scrollTop: 0
			}, 1000 * Math.atan($delay / 3000), 'easeInOutExpo');
			return false;
		});
	}	
	
	/* Full Screen Slider
	   --------------------------------------------------------- */	
	
	$.fn.wt_fullscreen_slider = function() {
		return this.each(function() {
			var container = $(this),
				i         = 0,
				data      = container.data('images'),
				slides    = [];
				
			if($().supersized) { // if supersized library is called	
			
				while( i < data.length ) {
					slides.push({image: data[i]});
					i++;
				}
				
				container.supersized({
					autoplay         : container.data('autoplay') != 'undefined' ? container.data('autoplay') : true,
					slide_interval   : container.data('slideinterval') != 'undefined' ? container.data('slideinterval') : 6000,		// Length between transitions
					transition       : container.data('transition') != 'undefined' ? container.data('transition') : 1, 		// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed : container.data('transitionspeed') != 'undefined' ? container.data('transitionspeed') : 1000,		// Speed of transition
			        slides           : slides,
					vertical_center         :   1,			// Vertically center background
					horizontal_center       :   1,			// Horizontally center background
					fit_always				:	0,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait         	:   1,			// Portrait images will not exceed browser height
					fit_landscape			:   0,			// Landscape images will not exceed browser width
				});
				
			}
		});
	};
	
	/* Google Maps
	   --------------------------------------------------------- */	
	   
	$.fn.wt_sc_googleMaps = function() {
		return this.each(function() {
			var container           = $(this),
				mapControls         = false,
				dataId              = container.data('id'),
				dataLocation        = container.data('location'),
				dataZoom            = container.data('zoom'),
				dataMapType         = container.data('map_type'),
				dataScrollWheel     = container.data('scrollwheel'),
				dataDraggable       = container.data('draggable'),
				dataDoubleClickZoom = container.data('doubleclickzoom'),
				dataControls        = container.data('controls'), 
				dataStyling         = container.data('styling'),
				dataFeatureType     = container.data('feature_type'), 
				dataElementType     = container.data('element_type'), 
				dataVisibility      = container.data('visibility'), 
				dataInvertLightness = container.data('invert_lightness'),  
				dataColor           = container.data('color'), 
				dataHue             = container.data('hue'), 
				dataSaturation      = container.data('saturation'), 
				dataLightness       = container.data('lightness'),
				dataGamma           = container.data('gamma'),
				dataMarkers         = container.data('markers');
						
			if (dataControls) {
				mapControls = window['mapControls_' + dataId];
				// console.log('controls = ' + true);
				// console.log(mapControls);
			} else {
				// console.log('controls = ' + false);
			}
				
			// console.log('styling         = ' + dataStyling);			
			// console.log('location        = ' + dataLocation);			
			// console.log('zoom            = ' + dataZoom);			
			// console.log('mapType         = ' + dataMapType);			
			// console.log('scrollWheel     = ' + dataScrollWheel);			
			// console.log('draggable       = ' + dataDraggable);			
			// console.log('doubleClickZoom = ' + dataDoubleClickZoom);
						
			if (dataMarkers) { // If there are any markers. It returns the undefined value when the data does not exists.
				var	dataCustomMarkers = container.data('custom_markers'),
					mapMarkers  = 'mapMarkers_' + dataId;
					
				// console.log(window[mapMarkers]);			
				// console.log('dataCustomMarkers = ' + dataCustomMarkers);
				
				if (dataStyling) {
					container.mapmarker({	
						center           : dataLocation,
						zoom             : dataZoom,
						controls         : mapControls,
						mapType          : dataMapType, // styling should be false to work
						scrollwheel      : dataScrollWheel,
						draggable        : dataDraggable,
						doubleclickzoom  : dataDoubleClickZoom,
						customMarkers    : dataCustomMarkers,
						markers          : window[mapMarkers],
						// if map is different styled
						styling          : 1,
						featureType      : dataFeatureType,
						elementType      : dataElementType,
						visibility       : dataVisibility,
                        invert_lightness : dataInvertLightness,
						color            : dataColor,
						hue              : dataHue,
						saturation       : dataSaturation,
						lightness        : dataLightness,
						gamma            : dataGamma
					});
				} else {
					container.mapmarker({
						center           : dataLocation,
						zoom             : dataZoom,
						controls         : mapControls,
						mapType          : dataMapType, // styling should be false to work
						scrollwheel      : dataScrollWheel,
						draggable        : dataDraggable,
						doubleclickzoom  : dataDoubleClickZoom,
						customMarkers    : dataCustomMarkers,
						markers          : window[mapMarkers]
					});
				}				
			} else { // If there are no markers
				if (dataStyling) {
					container.mapmarker({
						center           : dataLocation,	
						zoom             : dataZoom,
						controls         : mapControls,
						mapType          : dataMapType, // styling should be false to work
						scrollwheel      : dataScrollWheel,
						draggable        : dataDraggable,
						doubleclickzoom  : dataDoubleClickZoom,
						// if map is different styled
						styling          : 1,
						featureType      : dataFeatureType,
						elementType      : dataElementType,
						visibility       : dataVisibility,
                        invert_lightness : dataInvertLightness,
						color            : dataColor,
						hue              : dataHue,
						saturation       : dataSaturation,
						lightness        : dataLightness,
						gamma            : dataGamma
					});
				} else {
					container.mapmarker({
						center           : dataLocation,	
						zoom             : dataZoom,
						controls         : mapControls,
						mapType          : dataMapType, // styling should be false to work
						scrollwheel      : dataScrollWheel,
						draggable        : dataDraggable,
						doubleclickzoom  : dataDoubleClickZoom
					});
				}
			}
		});
	};
			
	/* Html5 Video Players
	   --------------------------------------------------------- */	
	   
	function html5_video(){
	
		var $video_player = $('.html5_video'),
			initVideo;
		
		if( $video_player.length ) {			
			initVideo = function initVideoPlayer( video_players ) {
							video_players.each(function() {
								var $this  = $(this);						
								$this.mediaelementplayer();			
							});
						};
			initVideo( $video_player );				
		}
	}
	
	/* Html5 Audio Players
	   --------------------------------------------------------- */	
	   		
	(function($) {

		var $audio_player = $('.html5_audio'),
			_initAudioPlayer;

		if( $audio_player.length ) {

			_initAudioPlayer = function initAudioPlayer( audio_players ) {
				audio_players.each(function() {
					var $this  = $(this);	
					var $audioLoop = $this.data('html5_audio_loop');			
					var toggle = $this.parents(".toggle");
					
					$this.bind("initMediaelement",function(){
						$this.mediaelementplayer({loop: $audioLoop});
						$this.data("mediaelementInited",true);
					}).data("mediaelementInited",false);

					if(toggle.size()!== 0){
						toggle.find(".toggle_title").click(function() {
							if($this.data("mediaelementInited")===false){
								$this.trigger("initMediaelement");
							}
						});
					}else{
						$this.trigger("initMediaelement");
					}
				});
			};
			_initAudioPlayer( $audio_player );		
		}
	})(jQuery);	
		
	/* Sliders & Carousels 
	   --------------------------------------------------------- */	
	
	/* ----- Flex Slider ----- */
	
	(function($) {
		var $slider = $('.flexslider_wrap'),
			_initSlider;

		if( $slider.length ) {
			_initSlider = function initSlider( sliders ) {
				sliders.each(function() {					
					var $this  = $(this);				
					var $thumbsCarousel = '#'+$this.data('flex_sync');
					var $thumbs = $this.data('flex_controlnavthumbs');
					var $thumbsSlider = $this.data('flex_controlnavthumbsslider');						
					var $use_css = Modernizr.touch ? true : false;
										
					var $effect = $this.data('flex_animation');
					if ($effect === "fade") {
						$effect = Modernizr.touch ? "slide" : "fade";
					}
					
					if ($thumbsSlider) {
						$($thumbsCarousel).flexslider({
							animation: "slide",
							controlNav: false,
							animationLoop: false,
							slideshow: false,
							itemWidth: $($thumbsCarousel).parents().is('#intro') ? 190 : ($($thumbsCarousel).parents().hasClass('fullWidth') ? 183 : 123),
							itemMargin: 5,
							asNavFor: $this
						});	
					}
					//$this.imagesLoaded(function() {					
						$this.flexslider({
							animation        : $effect,
							easing           : $effect==="slide" ? $this.data('flex_easing') : '',
							useCSS           : $use_css,
							direction        : $this.data('flex_direction'), 
							animationSpeed   : $this.data('flex_animationspeed'),
							slideshowSpeed   : $this.data('flex_slideshowspeed'),
							directionNav     : $this.data('flex_directionnav'), 
							controlNav       : $thumbs && !$thumbsSlider ? 'thumbnails' : $this.data('flex_controlnav'), 
							pauseOnAction    : $this.data('flex_pauseonaction'),
							pauseOnHover     : $this.data('flex_pauseonhover'),
							slideshow        : $this.data('flex_slideshow'),
							animationLoop    : $this.data('flex_animationloop'),
							sync             : $thumbsCarousel,
							before : function(){
								if ($effect==="slide") { 
									$this.find('.flex-caption').slideUp(400, 'easeOutExpo');
								} else { 
									return;
								}
							},
							after : function(){
								if ($effect==="slide") { 
									$this.find('.flex-caption').slideDown(100, 'easeInExpo');
								} else { 
									return;
								}
							}
						});
					//});
						
				});
			};
			_initSlider( $slider );		
		}
	})(jQuery);
		
	/* ----- Nivo Slider ----- */
	
	(function($) {

		var $slider = $('.nivoslider_wrap'),
			_initSlider;

		if( $slider.length ) {
			_initSlider = function initSlider( sliders ) {
				sliders.each(function() {
					var $this  = $(this);									
					var $effect = Modernizr.touch ? "slideInLeft" : $this.data('nivo_effect');
																
					$this.nivoSlider({
						effect           : $effect,
						slices           : $this.data('nivo_slices'), 
						boxCols          : $this.data('nivo_boxcols'), 
						boxRows          : $this.data('nivo_boxrows'), 
						animSpeed        : $this.data('nivo_animspeed'),
						pauseTime        : $this.data('nivo_pausetime'),
						randomStart      : $this.data('nivo_randomstart'),
						directionNav     : $this.data('nivo_directionnav'), 
						controlNav       : $this.data('nivo_controlnav'), 
						controlNavThumbs : $this.data('nivo_controlnavthumbs'), 
						pauseOnHover     : $this.data('nivo_pauseonhover'),
						manualAdvance    : $this.data('nivo_manualadvance'),
						lastSlide        : function(){
							if($this.data('nivo_stopatend')){
								$this.data('nivoslider').stop();
							}
						}
					});
					if( Modernizr.touch ) {							
						$this.bind( 'swipeleft', function( e ) {
							$('a.nivo-nextNav').trigger('click');
							e.stopImmediatePropagation();
							return false;
						});  
					
						$this.bind( 'swiperight', function( e ) {
							$('a.nivo-prevNav').trigger('click');
							e.stopImmediatePropagation();
							return false;
						}); 
					}
				});
			};
			_initSlider( $slider );		
		}		
	})(jQuery);
		
	/* ----- Tweets Cycle ----- */
			
	(function($) {
		
		var $twitter = $('.cycle_tweets'),
			_initTwitter;

		if( $twitter.length ) {
			_initTwitter = function initTwitter( tweets ) {
				tweets.each(function() {
					var win = $(window);
					var container = $(this);
					var cycle_nav = container.find('.cycle_nav');
					var $this  = $(this).find('ul');									
					var $prev = cycle_nav.children(".cycle_prev");		
					var $next = cycle_nav.children(".cycle_next");
															
					function twitter() {									
						$this.cycle({
							slides        : '> li',
							autoHeight    : 'container',
							fx            : Modernizr.touch ? 'scrollHorz' : 'fade',
							timeout       : 5000,
							pauseOnHover  : true,
							prev          : $prev,
							next          : $next
						});
					}
					
					twitter();
					win.bind('smartresize', twitter);
				});
			};
			_initTwitter ( $twitter );		
		}		
	})(jQuery);
				
	/* ----- Owl Rotator ----- */
		
	if($().owlCarousel) {
		(function($) {
	
			var $owl = $('.wt_owl_rotator'),
				_initOwl;
	
			if( $owl.length ) {
				_initOwl = function initOwl( owls ) {
					owls.each(function() {
						var $this  = $(this);
						
						function owl() {	
							$this.owlCarousel({
								autoPlay          : $this.data('owl-autoplay'), 
								stopOnHover       : $this.data('owl-stoponhover'),
								navigation        : $this.data('owl-navigation'),
								pagination        : $this.data('owl-pagination'), 
								singleItem        : true,
								autoHeight        : $this.data('owl-autoheight'),
								goToFirstSpeed    : 2000,
								navigationText    : false,
								transitionStyle   :"fade"
							});
						}
						owl();
					});
				};
				_initOwl( $owl );		
			}		
		})(jQuery);
	}
			
	/* ----- Nice Scroll ----- */	
	function niceScrollInit(){
		//$("html").niceScroll({styler:"fb",cursorcolor:"#000"});
		$("body").niceScroll({
			cursorcolor        : "#1A1E23",
			cursorwidth        : 10,
			cursorborder       : 0,
			cursorborderradius : 0,
			zindex             : 2000,
			horizrailenabled   :false
		});
	}




/**
 * Capitalize first letter
 */
String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}




// var kastellaUrlLanding = "http://192.168.1.50/kastella/landing";
 
// var kastellaUrl = "http://192.168.1.50/kastella/";

var kastellaUrlLanding = "http://redkastella.com/landing";
 
var kastellaUrl = "http://redkastella.com/";




/**
 * Customs Scripts for kastella landing page
 */

$(".login-form").submit(function(e){

    var form = $(this);

	if (localStorage.newPublicationTitle != undefined){
		
		if($('.newPublicationTitle').length > 0){

			$('.newPublicationTitle').val(localStorage.newPublicationTitle);
			
			$('.newPublicationContent').val(localStorage.newPublicationContent);

			$('.newPublicationPrivacies').val(localStorage.newPublicationPrivacies);



		}else{

		    form.prepend("<input type='hidden' name='newPublicationTitle' class='newPublicationTitle' value='" + localStorage.newPublicationTitle + "' />");

		    form.prepend("<input type='hidden' name='newPublicationContent' class='newPublicationContent' value='" + localStorage.newPublicationContent + "' />");

		    form.prepend("<input type='hidden' name='newPublicationPrivacies' class='newPublicationPrivacies' value='" + localStorage.newPublicationPrivacies + "' />");
		}

	}


     $.ajax({
     		url: kastellaUrl + 'Users/loginRest',
     		type: 'post',
     		dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data) {

    			if(data.success == false){

     				$('.error-l').html(data.message);

     				$('.error-l').removeClass('display-none');

     			}else if(data.success == true){

     				if(localStorage.newPublicationTitle != undefined){

     					localStorage.removeItem("newPublicationTitle");

     					localStorage.removeItem("newPublicationContent");	

     					localStorage.removeItem("newPublicationPrivacies");	

     					

     				}

					$('.error-l').html("");

     				$('.error-l').addClass('display-none');


     				window.location.href = kastellaUrl + "Publications/allPublications";

     			}
     		}
     	});

    return false;
   	e.preventDefault();


});
	


//variable usada para almacernar los departamentos y usarla si es necesario en otros casos
var Departments;

    
/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/  
function getDepartments(){

    $.ajax({
        type:'GET',
        dataType: "json",
        url: kastellaUrl + "Departments/getAllDepartments/",
        success: function(response) {

            //asignamos los departamentos
            Departments = response;
            // console.log(Departments);     

            var options = $("#selectDepartment");
            options.append(new Option('Seleccionar','0'));
            $.each(Departments, function(index) {

                options.append(new Option(Departments[index]['Departments'].name, Departments[index]['Departments'].id));

            });

            $("#selectDepartment").trigger('change');


        },
        error: function(response) {

        }

    });

}

//llamamos la función
getDepartments();




//variable usada para almacernar las ciudades y usarlas si es necesario en otros casos
var municipalities;

/**
* Función usada para obtener las ciudades y agregarlas a un dropdown especificado
*
*/  
function getMunicipalites(){

    $.ajax({
        type:'POST',
        data:{departmentId: $("#selectDepartment").val()},
        dataType: "json",
        url: kastellaUrl + "Municipalities/getCitiesByDepartmentId/",
        success: function(response) {

            //asignamos los departamentos
            municipalities = response;
            // console.log(municipalities);     

            var options = $("#selectMunicipality");
            options.empty();

            options.append(new Option('Seleccionar','0'));
            $.each(municipalities, function(index) {

                options.append(new Option(municipalities[index]['Municipality'].municipality, municipalities[index]['Municipality'].id));

            });

        },
        error: function(response) {

        }

    });

}

//detectamos el cambio en la selección de departamentos
$("#selectDepartment").on('change',function(){

    //llamamos la función
    getMunicipalites();


});








$('.addUserForm').submit(function(e){


	console.log("asdasd");

	var form = $(this);



	if (localStorage.newPublicationTitle != undefined){
		
		if($('.newPublicationTitle').length > 0){

			$('.newPublicationTitle').val(localStorage.newPublicationTitle);
			
			$('.newPublicationContent').val(localStorage.newPublicationContent);

			$('.newPublicationPrivacies').val(localStorage.newPublicationPrivacies);



		}else{

		    form.prepend("<input type='hidden' name='newPublicationTitle' class='newPublicationTitle' value='" + localStorage.newPublicationTitle + "' />");

		    form.prepend("<input type='hidden' name='newPublicationContent' class='newPublicationContent' value='" + localStorage.newPublicationContent + "' />");

		    form.prepend("<input type='hidden' name='newPublicationPrivacies' class='newPublicationPrivacies' value='" + localStorage.newPublicationPrivacies + "' />");
		}

	}

    if ($('#selectMunicipality').val() == 0) {
     
        $('.error-select-city').removeClass('display-none');
    
    }else{

    	$('.error-select-city').addClass('display-none');



    	$.ajax({
    			url: kastellaUrl + 'Users/registerRest',
    			type: 'post',
	            data: new FormData(this),
	            dataType: 'json',
	            processData: false,
	            contentType: false,
    			success: function (response) {

    				if(response.success == true){

						$.ajax({
								url: kastellaUrl + 'Users/loginRest',
								type: 'post',
					            data: new FormData(form[0]),
					            dataType: 'json',
					            processData: false,
					            contentType: false,
								success: function (response) {

									if(response.success == true){

										if(localStorage.newPublicationTitle != undefined){

											localStorage.removeItem("newPublicationTitle");

											localStorage.removeItem("newPublicationContent");	

											localStorage.removeItem("newPublicationPrivacies");	

										}

									$('#modal-register-success').modal('show');
				     				
									$('.error-list').html("");   	

									form[0].reset();

									}

							}
	   					});

    				}else{    						

    					$('.error-list').html("");   	

    					$('.error-list').html(response.message + "<br />");   
    					
    					$.each(response.errors, function(k, v) {
					       
					     	$('.error-list').append( v + "<br />");   
					    });
    			
    				}

    			}
    		});


    }

    e.preventDefault();


});


	
/**
 * Check if user is already logged
 */

$.ajax({
		url: kastellaUrl + 'Users/alreadyLoggedUser',
		type: 'post',
		dataType: 'json',
		success: function (data) {



			if (data.success == true) {

				
				$('.login-kast-form').remove();

			}else{

				$('.link-go-to-kastella').remove();

			}


			$('.login-kast-cont').removeClass('display-none');

			
		
		}
	});




$('.goto-to-kast-btn').click(function(){

	window.location.href = kastellaUrl+'Publications/allPublications';

});

$('.logout-kast-btn').click(function(){

	window.location.href = kastellaUrl+'Users/logout';

});




// var online = navigator.onLine;

// console.log(online);





/**
 * Restore password funcionality
 */


function forgotPasswordAjax(data){

    return $.ajax({
        type:'POST',
        dataType: "json",
        data: data,
        url: kastellaUrl+"Users/forgotPasswordAjax"
    });

}





var responseRp = $('.response-rp');

$('.btn-send-fp').click(function(){

	var form = $('.form-rp');

	var valid = form[0].checkValidity();
		
	if(!valid){	

		/**
		 * Click en el boton den envio escondido
		 */
		$('.send-fp').click();

		}else{

		var email = $('.u-email');

		forgotPasswordAjax({email: email.val()}).done(function(response) {

				if(response.success == true){

					responseRp.css("color",'#428BCA');

					responseRp.css("margin-top",'5px');
					
					responseRp.html(response.message);

					email.val('');				

				}else{

					responseRp.css("margin-top",'5px');
	
					responseRp.css("color",'#E02E2E');
					
					responseRp.html(response.message);
				}

		}).fail(function(x) {

		 	console.log(x);

		});	



	}


});







	
})(jQuery); // End









