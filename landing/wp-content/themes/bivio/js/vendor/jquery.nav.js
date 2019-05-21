/*! Nav - v0.2.0 - 2012-10-29
* https://github.com/KaptinLin/nav
* Copyright (c) 2012 KaptinLin; Licensed GPL */

(function (document, $, undefined) {
  "use strict";

  var namespace = 'nav';

  var Nav = $.nav = function (nav, settings) {
    this.nav = nav;
    this.$nav = $(nav);

    this.settings = $.extend(true, {}, Nav.defaults, settings);

    var _builted = false;
    var self = this;

    /**
     * Private methods
     */

    function init() {
      Nav.windowDimensions = self.getWindowDimensions();

      build();
      $(window).bind('resize', self.resize);
    }

    function build() {
      _builted = true;
      var roots = self.$nav.find('> li');
      bindHover(roots, self.settings.root);
      bindHover(roots.find('li'), self.settings.child);
    }

    function bindHover($element, settings) {
      self.hover($element, settings.hoverIntent, settings.delay, function () {
        var li = this,
            $dropdown = $(this).find('> ul');
        settings.beforeHoverIn.call(li);
        if ($dropdown.size() > 0) {
          //only reposition dropdown after window resize
          var _check = $(li).data('check');
          if (typeof _check === 'undefined') {
            settings.beforeFirstRender.call(li);
          }
          if (typeof _check === 'undefined' || _check !== Nav.windowDimensions) {
            settings.position.call(li, $dropdown, self);
            $(li).data('check', Nav.windowDimensions);
          }

          if (settings.effect === 'fade') {
            $dropdown.hide().css("visibility", "visible").fadeIn(settings.inDuration, function () {
              settings.afterHoverIn.call(li);
            });
          } else {
            $dropdown.hide().css("visibility", "visible").slideDown(settings.inDuration, function () {
              settings.afterHoverIn.call(li);
            });
          }
        } else {
          settings.afterHoverIn.call(li);
        }
      }, function () {
        var li = this,
            $dropdown = $(this).find('> ul');
        settings.beforeHoverOut.call(li);
        if ($dropdown.size() > 0) {
          if (settings.effect === 'fade') {
            $dropdown.fadeOut(settings.outDuration, function () {
              $dropdown.css('visibility', 'hidden');
              settings.afterHoverOut.call(li);
            });
          } else {
            $dropdown.slideUp(settings.outDuration, function () {
              $dropdown.css('visibility', 'hidden');
              settings.afterHoverOut.call(li);
            });
          }
        } else {
          settings.afterHoverOut.call(li);
        }
      });
    }

    /**
     * Prototype methods
     */
    $.extend(self, {
      getNav: function () {
        return this.$nav;
      },
      getCurrent: function () {
        var $current = this.$nav.find(this.settings.currentSelector);
        if ($current.size() === 0) {
          $current = this.$nav.find('li:first');
        }
        return $current.find('> a');
      },
      getWindowDimensions: function () {
        return {
          w: $(window).width(),
          h: $(window).height()
        };
      },
      // screen window resize event callback
      resize: function () {
        Nav.windowDimensions = self.getWindowDimensions();
      },
      isBuilted: function () {
        return _builted;
      },
      hover: function (element, hoverIntent, delay, fn1, fn2) {
        // check if jquery.hoverIntent.js exists
        if (typeof $.fn.hoverIntent !== 'undefined') {
          $(element).hoverIntent({
            sensitivity: 30,
            interval: hoverIntent,
            timeout: delay,
            over: fn1,
            out: fn2
          });
        } else {
          $(element).hover(fn1, fn2);
        }
      }
    });

    init();
  };

  Nav.defaults = {
    root: {
      effect: 'slide',
      //fade,slide
      delay: 100,
      hoverIntent: 100,
      inDuration: 200,
      outDuration: 200,
      beforeHoverIn: function () {},
      afterHoverIn: function () {
        $(this).addClass('is-open');
      },
      beforeHoverOut: function () {},
      afterHoverOut: function () {
        $(this).removeClass('is-open');
      },
      beforeFirstRender: function () {},
      position: function ($dropdown, api) {
        var li = this,
            $li = $(this);
        var offsets = {
          left: $li.offset().left,
          top: $li.offset().top
        };
        var dropdownDimensions = {
          w: $dropdown.outerWidth(),
          h: $dropdown.outerHeight()
        };
        if (offsets.left + dropdownDimensions.w < Nav.windowDimensions.w) {
          $dropdown.css({
            left: 0
          });
        } else if (offsets.left + li.offsetWidth < dropdownDimensions.w) {
          if (offsets.left < Nav.windowDimensions.w / 2) {
            $dropdown.css({
              left: -offsets.left + api.$nav.offset().left
            });
          } else {
            // api.$nav.offset().left is equal to api.$nav.offset().right
            $dropdown.css({
              left: Nav.windowDimensions.w - offsets.left - dropdownDimensions.w - api.$nav.offset().left
            });
          }
        } else {
          $dropdown.css({
            left: 'auto',
            right: 0
          });
        }
      }
    },
    child: {
      effect: 'fade',
      //fade,slide
      delay: 150,
      hoverIntent: 0,
      inDuration: 200,
      outDuration: 200,
      beforeHoverIn: function () {},
      afterHoverIn: function () {
        $(this).addClass('is-open');
      },
      beforeHoverOut: function () {},
      afterHoverOut: function () {
        $(this).removeClass('is-open');
      },
      beforeFirstRender: function () {},
      position: function ($dropdown, api) {
        var li = this,
            $li = $(li);
        var offsets = {
          left: $li.offset().left,
          top: $li.offset().top
        };
        var dropdownDimensions = {
          w: $dropdown.outerWidth(),
          h: $dropdown.outerHeight()
        };
        if (offsets.left + li.offsetWidth + dropdownDimensions.w < Nav.windowDimensions.w) {
          $dropdown.css({
            left: li.offsetWidth
          });
        } else {
          $dropdown.css({
            left: 0 - li.offsetWidth
          });
        }
      }
    }
    // },
    // classNamePrefix: 'nav',
    // currentSelector: '.current-menu-item, .current_page_item'
  };

  // Collection method.
  $.fn.nav = function (settings) {
    return this.each(function () {
      if (!$.data(this, namespace)) {
        $.data(this, namespace, new Nav(this, settings));
      }
    });
  };
}(document, jQuery));