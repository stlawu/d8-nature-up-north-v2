(function ($, Drupal) {
    $(document).ready(function() {
        // site CSS is 'relative' which messes with the curtain.
        $('body').css('position','initial');
        $('#edit-field-encounter-tags-tid option:first-of-type').replaceWith('<option value="All" selected="selected">Show only these encounters</option>');

        $('a.announcement-visibility').click(function(e) {
          e.preventDefault();
          $('.path-frontpage .view-announcements .views-field-field-thumbnail, .path-frontpage .view-announcements .views-field-field-announcement-details').slideToggle('medium');

          //changing from visible to hidden, swap icons
          if($(this).find('i').hasClass('fa-eye-slash')) {
            $(this).find('i').addClass('fa-eye');
            $(this).find('i').removeClass('fa-eye-slash');
            $('.path-frontpage .view-announcements .views-field-title').addClass('smaller');
          }
          //changing from hidden to visible, swap icons
          else {
            $(this).find('i').removeClass('fa-eye');
            $(this).find('i').addClass('fa-eye-slash');
            $('.path-frontpage .view-announcements .views-field-title').removeClass('smaller');
          }
        });

        function putButtonInRightPlace() {
          //want to set the  div's height based on the height of the map background image
          var background_height = $('.map-bg-img').height();
          var background_width = $('.map-bg-img').width();
          //set the div's height to the height we calculated
          $('.map-bg').css('height', background_height);

          //get buttons sizes, using outerHeight here to include the padding and border
          var button_height = $('.go-explore').outerHeight();
          var button_width = $('.go-explore').outerWidth();

          var left = (background_width / 2 ) - (button_width / 2);
          $('.go-explore').css('left', left);

          var top = (background_height / 2) - (button_height / 2);
          $('.go-explore').css('top', top);

          $('.go-explore').css('display', 'inline-block');
        }

        //these are for initial sizing of Go Explore button over middle of map, and adjustments on resizing
        $(window).on("load",putButtonInRightPlace);
        $(window).on("resize",putButtonInRightPlace);

        //total height of the current window
        //the height elements naturally use based on auto height
        /*(var windowHeight = window.innerHeight;
        var naturalHeight = $('#peel-away').height();
        var heightDifference = 0;
        var startingSpacerHeight = $('.peel-away-spacer').height();
        var calculatedSpacerHeight = 0;*/

        //resizeElements();

        //this keeps the width and heights set to their view pane should they resize the window
        $(window).resize(resizeElements);

        function resizeElements() {
          //set defaults before testing to see if we need adjustments below
          $('#peel-away').css('height', 'auto');
          $('#peel-away .arrow').removeClass('absolute');
          //this is the default for mobile, anything other than this will be adjusted
          $('.peel-away-spacer').css('height', '111px');

          var windowHeight = window.innerHeight;
          var windowWidth = window.innerWidth;
          var naturalHeight = $('#peel-away').height();

          if(windowHeight > naturalHeight && windowWidth >= 768) {
            $('#peel-away').css('height', windowHeight+'px');
            //$('.peel-away-spacer').css('height', windowHeight+'px');
            //add class to absolutely position arrow at bottom of page
            $('#peel-away .arrow').addClass('absolute');
          }
        }

      function Curtain($curtain) {

        var self = this,
            $el = $curtain,
            $body = $("body"),
            $wrapper = $(".home-wrapper");

        // Tracking curtain movement and status
        self.height = 0;
        self.buffer = 20;

        self.init = function() {
          window.scrollTo(0, 1);

          $(document).bind("respond", function(e) {
            if(e.size == "tiny" || e.size == "small" || e.size == "medium") {
              self.reset();
            }
            else if(e.size == "large") {
              if ($body.is('.force-curtain-fallback')) {
                self.reset();
              }
              else {
                self.setup();
              }
            }
          });

          $('.down-arrow').on('click', self.unfurl);
        }

        self.setup = function() {
          $body.css({"min-height": $body.height()});

          self.setCurtainFocus(false);
          self.bind();
        }

        self.reset = function() {
          $body.removeAttr("style");
          $wrapper.removeClass("on").removeAttr("style");

          self.unbind();
        }

        self.bind = function() {
          $(window).on("scroll", self.handleScroll);
        }

        self.unbind = function() {
          $(window).off("scroll");
        }

        self.handleScroll = function(e) {
          var scrollFactor = document.documentElement.scrollTop || $(document).scrollTop();

          // If we're scrolled down past the curtain, let the page scroll
          if(scrollFactor > self.getTotalHeight()) {
            self.setCurtainFocus(true);
          }
          // And if we're not, make sure the curtain is all that scrolls
          else {
            self.setCurtainFocus(false);
          }
        }

        self.setCurtainFocus = function(state) {
          self.updateHeight();

          if(state) {
            $wrapper.attr("style", "margin-top: " + self.getTotalHeight() + "px");
            $wrapper.removeClass("fixed");
          }
          else {
            $wrapper.attr("style", "margin-bottom: " + self.getTotalHeight() + "px");
            $wrapper.addClass("fixed");
          }
        }

        // Update the height of the curtain, in case it's changed
        self.updateHeight = function() {
          self.height = $curtain.height();
          return self.height;
        }

        // Curtain height plus an arbitrary buffer
        self.getTotalHeight = function() {
          return self.height + self.buffer;
        }

        self.unfurl = function() {
          var scrollFactor = self.height + self.buffer;

          // Unbind for a second
          self.unbind();

          $('html, body').animate({
            scrollTop: scrollFactor
          }, {
            duration: 400,
            complete: function() {
              self.bind();
            }
          });
        }

        self.init();
      }

      $(function() {
        var $curtain = $(".peel-away");

        if($curtain.length) {
          Curtain($curtain);
        }
      });

      var StateManager = {
        state: "",
        touch: 'ontouchstart' in document.documentElement,

        handleResize: function() {
          var width = jQuery("body").width();

          if (width < 480 && this.state != "tiny") {
            this.state = "tiny";
            var e = jQuery.Event( "respond", { size: this.state } );
            jQuery(document).trigger(e);
          } else if (width > 479 && width < 601 && this.state != "small") {
            this.state = "small";
            var e = jQuery.Event( "respond", { size: this.state } );
            jQuery(document).trigger(e);
          } else if (width > 600 && width < 768 && this.state != "medium") {
            this.state = "medium";
            var e = jQuery.Event( "respond", { size: this.state } );
            jQuery(document).trigger(e);
          } else if (width > 767 && this.state != "large") {
            this.state = "large";
            var e = jQuery.Event( "respond", { size: this.state } );
            jQuery(document).trigger(e);
          }
        },

        init: function() {
          var self = this;
          self.handleResize();
          jQuery(window).bind("resize", function(){ self.handleResize(); });
          if(StateManager.touch) {
            //console.log("got touch!");
            jQuery("body").addClass("touch");
          }
        }
      };

      jQuery(function() {
        StateManager.init();
        jQuery(window).on("load",function() {
          StateManager.state = '';
          jQuery(window).trigger('resize');
        });
      });



  });
})(jQuery, Drupal);
