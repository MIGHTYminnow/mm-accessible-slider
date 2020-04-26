/* focusin/out event polyfill (firefox) */
!function(){
	var w = window,
	d = w.document;

	if( w.onfocusin === undefined ){
		d.addEventListener('focus' ,addPolyfill ,true);
		d.addEventListener('blur' ,addPolyfill ,true);
		d.addEventListener('focusin' ,removePolyfill ,true);
		d.addEventListener('focusout' ,removePolyfill ,true);
	}
	function addPolyfill(e){
		var type = e.type === 'focus' ? 'focusin' : 'focusout';
		var event = new CustomEvent(type, { bubbles:true, cancelable:false });
		event.c1Generated = true;
		e.target.dispatchEvent( event );
	}
	function removePolyfill(e){
if(!e.c1Generated){ // focus after focusin, so chrome will the first time trigger tow times focusin
	d.removeEventListener('focus' ,addPolyfill ,true);
	d.removeEventListener('blur' ,addPolyfill ,true);
	d.removeEventListener('focusin' ,removePolyfill ,true);
	d.removeEventListener('focusout' ,removePolyfill ,true);
}
setTimeout(function(){
	d.removeEventListener('focusin' ,removePolyfill ,true);
	d.removeEventListener('focusout' ,removePolyfill ,true);
});
}
}();

var myCarousel = (function() {

	var carousel, slides, index, slidenav, settings, timer, setFocus, animationSuspended;

	function forEachElement(elements, fn) {
		for (var i = 0; i < elements.length; i++)
			fn(elements[i], i);
	}

	function removeClass(el, className) {
		if (el.classList) {
			el.classList.remove(className);
		} else {
			el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
		}
	}

	function hasClass(el, className) {
		if (el.classList) {
			return el.classList.contains(className);
		} else {
			return new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
		}
	}

	function init(set) {
		settings = set;
		carousel = document.getElementById(settings.id);
		slides = carousel.querySelectorAll('.slide');

		carousel.className = 'active carousel';

		var ctrls = document.createElement('ul');

		ctrls.className = 'controls';
		ctrls.innerHTML = '<li>' +
				'<button type="button" class="btn-prev"><span class="visuallyhidden">Prev</span> <i class="fas fa-chevron-left"></i></button>' +
			'</li>' +
			'<li>' +
				'<button type="button" class="btn-next"><span class="visuallyhidden">Next</span> <i class="fas fa-chevron-right"></i></button>' +
			'</li>';

		ctrls.querySelector('.btn-prev')
			.addEventListener('click', function () {
				prevSlide(true);
			});
		ctrls.querySelector('.btn-next')
			.addEventListener('click', function () {
				nextSlide(true);
			});

		carousel.appendChild(ctrls);

		if (settings.slidenav || settings.animate) {
			slidenav = document.createElement('ul');

			slidenav.className = 'slidenav';

			if (settings.animate) {
				var li = document.createElement('li');

				if (settings.startAnimated) {
					li.innerHTML = '<button data-action="stop"><span class="visuallyhidden">Pause </span><i class="fas fa-pause"></i></button>';
				} else {
					li.innerHTML = '<button data-action="start"><span class="visuallyhidden">Play </span><i class="fas fa-play"></i></button>';
				}

				slidenav.appendChild(li);
			}

			if (settings.slidenav) {
				forEachElement(slides, function(el, i){
					var li = document.createElement('li');
					var klass = (i===0) ? 'class="current" ' : '';
					var kurrent = (i===0) ? ' <span class="visuallyhidden">(Current Item)</span>' : '';

					li.innerHTML = '<button '+ klass +'data-slide="' + i + '"><span class="visuallyhidden">News</span> ' + (i+1) + kurrent + '</button>';
					slidenav.appendChild(li);
				});
			}

			slidenav.addEventListener('click', function(event) {
				var button = event.target;
				if (button.localName == 'button') {
					if (button.getAttribute('data-slide')) {
						stopAnimation();
						setSlides(button.getAttribute('data-slide'), true);
					} else if (button.getAttribute('data-action') == "stop") {
						stopAnimation();
					} else if (button.getAttribute('data-action') == "start") {
						startAnimation();
					}
				}
			}, true);

			carousel.className = 'active carousel with-slidenav';
			carousel.appendChild(slidenav);
		}

		var liveregion = document.createElement('div');
		liveregion.setAttribute('aria-live', 'polite');
		liveregion.setAttribute('aria-atomic', 'true');
		liveregion.setAttribute('class', 'liveregion visuallyhidden');
		carousel.appendChild(liveregion);

			slides[0].parentNode.addEventListener('transitionend', function (event) {
				var slide = event.target;
				removeClass(slide, 'in-transition');
				if (hasClass(slide, 'current'))	{
					if(setFocus) {
						slide.setAttribute('tabindex', '-1');
						slide.focus();
						setFocus = false;
					}
				}
			});

			carousel.addEventListener('mouseenter', suspendAnimation);
			carousel.addEventListener('mouseleave', function(event) {
				if (animationSuspended) {
					startAnimation();
				}
			});

			carousel.addEventListener('focusin', function(event) {
				if (!hasClass(event.target, 'slide')) {
					suspendAnimation();
				}
			});
			carousel.addEventListener('focusout', function(event) {
				if (!hasClass(event.target, 'slide') && animationSuspended) {
					startAnimation();
				}
			});

		index = 0;
		setSlides(index);

		if (settings.startAnimated) {
			timer = setTimeout(nextSlide, settings.delay);
		}
	}

	function setSlides(new_current, setFocusHere, transition, announceItemHere) {
		setFocus = typeof setFocusHere !== 'undefined' ? setFocusHere : false;
		announceItem = typeof announceItemHere !== 'undefined' ? announceItemHere : false;
		transition = typeof transition !== 'undefined' ? transition : 'none';

		new_current = parseFloat(new_current);

		var length = slides.length;
		var new_next = new_current+1;
		var new_prev = new_current-1;

		if(new_next === length) {
			new_next = 0;
		} else if(new_prev < 0) {
			new_prev = length-1;
		}

		for (var i = slides.length - 1; i >= 0; i--) {
			slides[i].className = "slide";
		}

		slides[new_next].className = 'next slide' + ((transition == 'next') ? ' in-transition' : '');
		slides[new_next].setAttribute('aria-hidden', 'true');

		slides[new_prev].className = 'prev slide' + ((transition == 'prev') ? ' in-transition' : '');
		slides[new_prev].setAttribute('aria-hidden', 'true');


		slides[new_current].className = 'current slide';
		slides[new_current].removeAttribute('aria-hidden');


		if (announceItem) {
			carousel.querySelector('.liveregion').textContent = 'Item ' + (new_current + 1) + ' of ' +	 slides.length;
		}

		if(settings.slidenav) {
			var buttons = carousel.querySelectorAll('.slidenav button[data-slide]');
			for (var j = buttons.length - 1; j >= 0; j--) {
				var thumb = jQuery( '.mm-accessible-slider .slide' ).eq( j ).data( 'thumb' );
				buttons[j].className = '';
				buttons[j].innerHTML = '<span class="visuallyhidden">Slide ' + (j+1) + '</span> <img src="' + thumb + '" alt="Slide ' + (j+1) + '">';
			}
			var thumb = jQuery( '.mm-accessible-slider .slide' ).eq( new_current ).data( 'thumb' );
			buttons[new_current].className = "current";
			buttons[new_current].innerHTML = '<span class="visuallyhidden">Slide ' + (new_current+1) + ' (Current Item)</span> <img src="' + thumb + '" alt="Slide ' + (j+1) + '">';
		}

		index = new_current;

	}

	function nextSlide(announceItem) {
		announceItem = typeof announceItem !== 'undefined' ? announceItem : false;

		var length = slides.length,
		new_current = index + 1;

		if(new_current === length) {
			new_current = 0;
		}

		setSlides(new_current, false, 'prev', announceItem);

		if (settings.animate) {
			timer = setTimeout(nextSlide, settings.delay);
		}

	}

	function prevSlide(announceItem) {
		announceItem = typeof announceItem !== 'undefined' ? announceItem : false;

		var length = slides.length,
		new_current = index - 1;

		if(new_current < 0) {
			new_current = length-1;
		}

		setSlides(new_current, false, 'next', announceItem);

	}

	function stopAnimation() {
		if ( settings.startAnimated ) {
			clearTimeout(timer);
			settings.animate = false;
			animationSuspended = false;
			_this = carousel.querySelector('[data-action]');
			_this.innerHTML = '<span class="visuallyhidden">Play </span><i class="fas fa-play"></i>';
			_this.setAttribute('data-action', 'start');
		}
	}

	function startAnimation() {
		if ( settings.startAnimated ) {
			settings.animate = true;
			animationSuspended = false;
			timer = setTimeout(nextSlide, settings.delay);
			_this = carousel.querySelector('[data-action]');
			_this.innerHTML = '<span class="visuallyhidden">Pause </span><i class="fas fa-pause"></i>';
			_this.setAttribute('data-action', 'stop');
		}
	}

	function suspendAnimation() {
		if(settings.animate) {
			clearTimeout(timer);
			settings.animate = false;
			animationSuspended = true;
		}
	}

	return {
		init:init,
		next:nextSlide,
		prev:prevSlide,
		goto:setSlides,
		stop:stopAnimation,
		start:startAnimation
	};
});

MMAS.settings.id = 'slide-1';
var c = new myCarousel();
c.init( MMAS.settings );

MMAS.settings.id = 'slide-2';
var c = new myCarousel();
c.init( MMAS.settings );
