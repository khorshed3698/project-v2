/**
 * Home Banner Slider
 * @param  {[homeBannerSlider]} $ [Bootstrap carosal slider]
 * @return {[caption animate]}   [description]
 */
(function() {
    "use strict";

    var myCarousel = document.getElementById("homeBannerSlider");

    // Check if myCarousel is found
    if (!myCarousel) {
        return;
    }

    var firstAnimatingElems = myCarousel
        .querySelector(".carousel-item:first-child")
        .querySelectorAll("[data-animation ^= 'animated']");

    var carousel = new bootstrap.Carousel(myCarousel);

    doAnimations(firstAnimatingElems);

    myCarousel.addEventListener("slide.bs.carousel", function(e) {
        var animatingElems = e.relatedTarget.querySelectorAll("[data-animation ^= 'animated']");
        doAnimations(animatingElems);
    });

    function doAnimations(elems) {
        var animEndEv = "webkitAnimationEnd animationend";

        elems.forEach(function(elem) {
            var animationClasses = elem.getAttribute("data-animation").split(" ");

            animationClasses.forEach(function(animationClass) {
                elem.classList.add(animationClass);
            });

            elem.addEventListener(animEndEv, function() {
                animationClasses.forEach(function(animationClass) {
                    elem.classList.remove(animationClass);
                });
            }, { once: true });
        });
    }
})();


/**
 * Smooth Scroll Function
 */
(function($) {
	"use strict";

	$('a[href*="#"].smoothScroll:not([href="#"])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html, body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});
})(jQuery);


$(document).ready(function () {
  $('.ossServ-tabText-item').hover(
    function () {
      $(this).parent().addClass('ossSrvTabActive');
    },
    function () {
      if (!$(this).hasClass('active')) {
        $(this).parent().removeClass('ossSrvTabActive');
      }
    }
  );

  $( ".ossServ-tabText-item" ).on( "click", function() {
    $(".oss-serv-menu-item").removeClass('ossSrvTabActive');
    $(this).parent().addClass('ossSrvTabActive');

    if (!$('#ossServiceTabmenu').hasClass('activeTabMenuContainer')) {
      $('#ossServiceTabmenu').addClass('activeTabMenuContainer');
    }
  });

});




