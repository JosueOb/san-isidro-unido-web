try {
    require('bootstrap');
    window.$ = window.jQuery = require('jquery');

} catch (e) {
    console.log(e);
}

require('itemslide');
require('./animatescroll');

/**
 * SCROLLSPY - SHRINK NAVIGATION MENU ON SCROll
 */
// Closes responsive menu when a scroll trigger link is clicked
$('.js-scroll-trigger').click(function () {
    $('.navbar-collapse').collapse('hide');
});

// Activate scrollspy to add active class to navbar items on scroll
$('body').scrollspy({
    target: '#mainNav',
    offset: 56
});

// Collapse Navbar
var navbarCollapse = function () {
    if ($("#mainNav").offset().top > 100) {
        $("#mainNav").addClass("navbar-shrink");
    } else {
        $("#mainNav").removeClass("navbar-shrink");
    }
};
// Collapse now if page is not at top
navbarCollapse();
// Collapse the navbar when page is scrolled
$(window).scroll(navbarCollapse);



/**
 * ANIMATE SCROLL
 */
var animateOptions = {
    scrollSpeed:2000,
    easing:'easeInOutQuad'
};

$('#brand').on('click',function(event){
    // $('#home').animatescroll();
    // var href = this.attributes['href'];
    var href = $(this).attr('href');
    $(href).animatescroll(animateOptions);
});

$('.nav-item').on('click', function(event){
    // console.log($(this.firstElementChild).attr('href'));
    var href = $(this.firstElementChild).attr('href')
    $(href).animatescroll(animateOptions);
});

$('.footer-link').on('click', function(event){
    // console.log(this.firstElementChild.firstElementChild);
    var href = $(this).attr('href')
    // console.log(href);
    $(href).animatescroll(animateOptions);
});

/**
 * SCROLLING - CAROUSEL
 */
var carousel;

carousel = $("#scrolling ul");
var items = [].slice.call(document.querySelector("#scrolling").firstElementChild.children)
// console.log(Math.round(items.length/2) - 1);

carousel.itemslide({
    start: Math.round(items.length/2) - 1,
    //swipe_out: true //NOTE: REMOVE THIS OPTION IF YOU WANT TO DISABLE THE SWIPING SLIDES OUT FEATURE. -->
}); //initialize itemslide

$(window).resize(function () {
    carousel.reload();
}); //Recalculate width and center positions and sizes when window is resized