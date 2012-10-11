/*
rci_slider.js

This is the default html to match the default css and default settings
please note that the anchors match the slides 1 for 1 in the right order and that
the anchors match the slide ids

<div class="rci_slider">
	<a href="#" class="rci-slider-previous">Previous</a>
	<a href="#" class="rci-slider-next">Next</a>
	<a href="#" class="rci-slider-play">Play</a>
	<div class="rci-slider-counter"></div>

	<ul class="rci-slider-navigation">
		<li><a href="#slide1">Slide 1</a></li>
		<li><a href="#slide2">Slide 2</a></li>
		<li><a href="#slide3">Slide 3</a></li>
		<li><a href="#slide4">Slide 4</a></li>
	</ul>
	
	<div class="rci-slider-viewport">
		<div class="rci-slide-container">
		    <div class="rci-slide" id="slide1">
		        Slide 1
		    </div>
		    <div class="rci-slide" id="slide2">
		        Slide 2
		    </div>
		    <div class="rci-slide" id="slide3">
		        Slide 3
		    </div>
		    <div class="rci-slide" id="slide4">
		        Slide 4
		    </div>
		</div>
	</div>
</div>
*/

var rci_slider_count = 1;

(function($) {
	$.fn.rci_slider = function(op){
		return this.each(function(){
			new $.rci_slider(this,op);
			//this ensures a unique id in the case of multiple sliders on the page
			rci_slider_count++;
		});
	}

	/**
	 * $.rci_slider
	 * starts up an individual slider instance by setting variables, setting up the slides, displays the first and starts auto play
	 */
    $.rci_slider = function(self,op){
		//setup basic class variables
		this.options = $.extend({},this.defaults,op);
		this.setup();
		this.element = $(self).addClass('js-enabled');
		var self = this;

		//parse slides and any navigation elements into class variables
		this._setup_slides();
		this._setup_navigation();

		//is the anchor tag a slide?
        if(this.options.hashtag && location.hash){
            hash = location.hash.slice(1);
			if(this.element_array[hash] !== undefined) this.current_slide = this.element_array[hash];
		}

		//display current slide and mark the nav
		$(this.elements[this.current_slide]).show();
		$(this.navigation_array[this.current_slide]).addClass(this.options.current_class);

		//set the start interval of auto play
		//self is used here due to scope
        if(this.options.auto_play){
			this.play_timeout = window.setTimeout(function(){self.start_interval();},self.options.initial_timeout);
		}
		
		//prevent slider from changing while user is hovered with mouse
		$(this.element)
			.mouseenter(function(){
				self.pause_interval();
				if(self.play_timeout){
				    self.playing = true;
					window.clearTimeout(self.play_timeout);
					delete self.play_timeout;//this is necessary to make this check report undefined for this var in the future
				}
			})
			.mouseleave(function(){
				self.unpause_interval();
			});

		this._update_counter();
	}

	/**
	 * $.rci_slider.prototype
	 * holds the common methods for the slider and default setup
	 */
	 $.extend($.rci_slider.prototype,{
        defaults : {
			'slides':'.rci-slide',                  //selector for slides
			'navigation':'.rci-slider-navigation',  //selector for navigation anchors
			'current_class':'current',              //class applied to current slide's anchor
			'hashtag':true,                         //load first element based on anchor tag
			'change_speed':400,             		//the higher this is, the more cpu used
			'auto_play':false,
			'timeout':4000,                         //timeout between slides during play
			'initial_timeout':null,                 //pause before starting initial play (after window loads)
			'change_callback':null,
			'change_type':'fade',                   //what type of transition: fade, slide_left, slide_right, slide_up, slide_down
			'previous':'.rci-slider-previous',      //selector for previous button
			'next':'.rci-slider-next',              //selector for next button
			'play':'.rci-slider-play',              //selector for play button
			'play_class':'playing',                 //class applied to play button while playing
			'playing_text':null,                    //text displayed while slider is playing
			'paused_text':null,                     //text displayed while slider is paused
			'counter':'.rci-slider-counter'         //container for current/total count
		},

		setup : function(){
			this.element_array = [];                //this holds the slides
			this.navigation_array = [];             //this holds the navigation anchors
			this.current_slide = 0;
			this.running = false;
			this.playing = false;
			this.was_playing = false;
			this.previous_button = null;
			this.next_button = null;
			this.play_button = null;
			this.counter = null;
			//modern browsers support hash change call backs
			//ie8 supports it, but only when not in compatibility mode
			this.supports_hashchange = ("onhashchange" in window && ( document.documentMode === undefined || document.documentMode > 7 ));
		},

		/**
		 * start a window interval to change the slide - speed is based off options
		 * will change the html of the caller if set in options
		 */
		start_interval : function(){
            var self = this;
            window.clearInterval(this.play_interval);
            delete this.play_timeout;       //this is necessary to make the mouseleave check report undefined for this var
			this.play_interval = setInterval(function(){self.next();}, self.options.timeout + self.options.change_speed);
			this.play_button.addClass(this.options.play_class).html(function(i,oldhtml){
				return (self.options.playing_text)? self.options.playing_text:oldhtml;
			});
			this.playing = true;
		},
		
		/**
		 * kill the window interval that changes the slide
		 * will change the html of the caller if set in options
		 */
		stop_interval : function(){
			var self = this;
			this.playing = false;
			window.clearInterval(this.play_interval);
			this.play_button.removeClass(this.options.play_class).html(function(i,oldhtml){
				return (self.options.paused_text)? self.options.paused_text:oldhtml;
			});
		},
		
		/**
		 * pauses the play functionality while someone is over the player
		 * does not change the play button text/class
		 */
		pause_interval : function(){
			window.clearInterval(this.play_interval);
		},
		
		/**
		 * unpause the play functionality
		 */
		unpause_interval : function(){
			if(this.playing) this.start_interval();
		},

        /**
		 * provides functionality for a play/pause button
		 */
        play : function(){
            var self = this;
			if(this.playing){
				return this.stop_interval();
			}else{
				this.next();
				this.start_interval();
				//if someone pushes play we want it to start playing, but pause once they move their mouse to interact
				//this may need to be changed
				window.setTimeout(function(){
					$(self.element).mousemove(function(){
						self.pause_interval();
						$(self.element).unbind('mousemove');
					});
				},200);
			}
		},

		/**
		 * functionality for a previous button / link
		 */
        previous : function(){
			//previous slide, or last if on first element
			previous_slide = (this.current_slide == 0)? (this.elements.length - 1):this.current_slide - 1;
			if(this.supports_hashchange){
				window.location.hash=$(this.elements[previous_slide]).attr("id");
				return;
			}
			this.go(previous_slide);
		},

		/**
		 * functionality for next button / link
		 */
		next : function(){
			//next slide, or first if on last element
			next_slide = (this.current_slide == (this.elements.length - 1))? 0:this.current_slide + 1;

			//we check the playing variable here so that if someone sits on this page with autoplay
			//they don't have to hit back a million times to actually go back
			if(this.playing || !this.supports_hashchange){
				this.go(next_slide);
				return;
			}

			window.location.hash=$(this.elements[next_slide]).attr("id");
		},

		/**
		 * advances to indicated index number
         * @param   to  integer slide index number
		 */
		go : function(to){
			var self = this;
			
			//prevent any action if already on slide we want to go to
			if(this.current_slide == to) return;

			//change classes on the navigation elements
			$(this.navigation_array[this.current_slide]).removeClass(this.options.current_class);
			$(this.navigation_array[to]).addClass(this.options.current_class);

			//swapped slides based on desired animation type
			this[this.options.change_type](to);
		},
		
		/**
		 * fade between the two slides
		 * @param   to  integer slide index number
		 */
		fade : function(to){
			var self = this;

			$(this.elements[this.current_slide]).fadeOut(this.options.change_speed);

			$(this.elements[to]).fadeIn(this.options.change_speed,function(){
				self._slide_changed(to);
			});
		},
		
		/**
		 * sliding function prototype called by the various sliding methods
		 */
		slide : function(to,to_css,current_animate,to_animate){
            var self = this;

            $(this.elements[to]).css(to_css).show(0);

			$(this.elements[this.current_slide]).animate(current_animate,this.options.change_speed,function(){
				$(this).hide(0);
			});

			$(this.elements[to]).animate(to_animate,this.options.change_speed,function(){
				self._slide_changed(to);
			});
		},
		
		/**
		 * move slides to the left; current slides out left, and the new one comes from the right
		 * @param   to  integer slide index number
		 */
		slide_left : function(to){
			var width = $(this.element).width();
			this.slide(to,{left:width},{left:(0 - width)},{left:0});
		},

		slide_right : function(to){
			var width = $(this.element).width();
			this.slide(to,{left:(0 - width)},{left:width},{left:0});
		},

		slide_up : function(to){
			var height = $(this.element).height();
			this.slide(to,{top:height},{top:(0 - height)},{top:0});
		},
		
		slide_down : function(to){
			var height = $(this.element).height();
			this.slide(to,{top:(0 - height)},{top:height},{top:0});
		},
		
		/**
		 * call all the events that happen after the new slide is in place
		 */
		_slide_changed : function(to){
        	this.current_slide = to;
			this._update_counter();
			if(this.options.change_callback){
				this.options.change_callback.apply(this);
			}
		},
		
		_update_counter : function(){
			this.counter.html((this.current_slide + 1) +"/"+this.elements.length);
		},
		
		/**
		 * setup navigation links based on browser functionality and settings
		 * play, next, previous and individual anchors are setup here
		 */
		_setup_navigation : function(){
		    var self = this;

			//previous, next and play
			(this.previous_button = $(this.element).find(this.options.previous)).click(function(){self.previous();self.stop_interval();return false;});
			(this.next_button = $(this.element).find(this.options.next)).click(function(){self.next();self.stop_interval();return false;});
			(this.play_button = $(this.element).find(this.options.play)).click(function(){self.play();return false;});
			this.counter = $(this.element).find(this.options.counter);

			//clicking on any anchor link will stop the play functionality
			$(this.element).find(this.options.navigation+' a').click(function(){
				self.stop_interval();
			}).each(function(i){
				self.navigation_array[i] = $(this);
			});

			//sets up hash change listener for modern browsers
			if(this.supports_hashchange){
				$(window).bind('hashchange', function(){
					hash = location.hash.slice(1);
					if(self.element_array[hash] !== undefined){
						self.go(self.element_array[hash]);
					}
				});
				return;
			}

			//old browsers and non-history saving functionality
			$(this.element).find(this.options.navigation+' a').each(function(i){
				$(this).click(function(e){
				    e.preventDefault();
					self.go(i);
				});
			});
		},

		/**
		 * find slides, hide them and create internal pointer array
		 */
		 _setup_slides : function(){
			this.elements = $(this.element).find(this.options.slides);
			var self = this;
			
	        $(this.elements).hide().each(function(i){
                //if this element has no id, make one
				$(this).attr("id",($(this).attr("id") || 'slide_'+rci_slider_count+'_'+i));
				self.element_array[$(this).attr("id")] = i;
			});
		}
	});
})(jQuery);