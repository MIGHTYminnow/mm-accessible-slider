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
	var carousel, slides, index, slidenav, settings, timer, setFocus, animationSuspended, clickedBtn;
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
		slides = carousel.querySelectorAll('.wcag-slide');
		carousel.className = 'active wcag-carousel';
		var ctrls = document.createElement('ul');
		ctrls.className = 'controls';
		ctrls.innerHTML = '<li>' +
			'<button type="button" class="btn-prev"><img alt="Previous Slide" src="https://www.w3.org/WAI/tutorials/img/chevron-left-75c7dd0b.png" /></button>' +
			'</li>' +
			'<li>' +
			'<button type="button" class="btn-next"><img alt="Next Slide" src="https://www.w3.org/WAI/tutorials/img/chevron-right-2f19bc8b.png" /> </button>' +
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
		if (settings.slidenav) {
			slidenav = document.createElement('ul');
			slidenav.className = 'slidenav';
			if (settings.slidenav) {
				forEachElement(slides, function(el, i){
					var li = document.createElement('li');
					var klass = (i===0) ? 'class="current" ' : '';
					var kurrent = (i===0) ? ' <span class="visuallyhidden">(Current Item)</span>' : '';
					var thumb = jQuery( '.wcag-slides li' ).eq( i ).data( 'thumb' );
					if ( typeof thumb !== 'undefined' ) {
						li.innerHTML = '<button '+ klass +'data-slide="' + i + '"><img src="' + thumb + '" alt="View slide ' + (i+1) + '"></button>';
					} else {
						li.innerHTML = '<button '+ klass +'data-slide="' + i + '"><span class="visuallyhidden">News</span> ' + (i+1) + kurrent + '</button>';
					}
					slidenav.appendChild(li);
				});
			}
			carousel.className = 'active wcag-carousel with-slidenav';
			carousel.appendChild(slidenav);
			jQuery( '.wcag-carousel .slidenav' ).on( 'click', 'button', function(){
				var slide = jQuery(this).data('slide');
				if ( typeof slide !== 'undefined' ) {
					stopAnimation();
					setSlides( slide, true );
				}
			});
		}
		var liveregion = document.createElement('div');
		liveregion.setAttribute('aria-live', 'polite');
		liveregion.setAttribute('aria-atomic', 'true');
		liveregion.setAttribute('class', 'liveregion visuallyhidden');
		carousel.appendChild(liveregion);
		slides[0].parentNode.addEventListener('transitionend', function (event) {
			var slide = event.target;
			removeClass(slide, 'in-transition');
			if (hasClass(slide, 'current'))  {

			}
		});
		index = 0;
		setSlides(index);

		jQuery( '.wcag-carousel button' ).on( 'click', function(){
			clickedBtn = jQuery(this);
			clickedBtn.focus();
		});
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
			slides[i].className = "wcag-slide";
		}
		if ( slides[new_next] ) {
			slides[new_next].className = 'next wcag-slide' + ((transition == 'next') ? ' in-transition' : '');
			slides[new_next].setAttribute('aria-hidden', 'true');
		}
		if ( slides[new_prev] ) {
			slides[new_prev].className = 'prev wcag-slide' + ((transition == 'prev') ? ' in-transition' : '');
			slides[new_prev].setAttribute('aria-hidden', 'true');
		}
		slides[new_current].className = 'current wcag-slide';
		slides[new_current].removeAttribute('aria-hidden');
		if (announceItem) {
			carousel.querySelector('.liveregion').textContent = 'Item ' + (new_current + 1) + ' of ' +   slides.length;
		}
		if(settings.slidenav) {
			var buttons = carousel.querySelectorAll('.slidenav button[data-slide]');
			for (var j = buttons.length - 1; j >= 0; j--) {
				buttons[j].className = '';
				/*buttons[j].innerHTML = '<span class="visuallyhidden">News</span> ' + (j+1);*/
				buttons[j].setAttribute( 'aria-current', 'false' );
			}
			buttons[new_current].className = "current";
			buttons[new_current].setAttribute( 'aria-current', 'true' );
			/*buttons[new_current].innerHTML = '<span class="visuallyhidden">News</span> ' + (new_current+1) + ' <span class="visuallyhidden">(Current Item)</span>';*/
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
	}
	function startAnimation() {
	}
	function suspendAnimation() {
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
if ( jQuery( '#c' ).size() > 0 ) {
	var c = new myCarousel();
	c.init({
		id: 'c',
		slidenav: true,
	});
}
