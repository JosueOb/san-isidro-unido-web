$.extend($.easing, {
  easeOutSine: function easeOutSine(x, t, b, c, d) {
    return c * Math.sin(t / d * (Math.PI / 2)) + b;
  }
});


$(document).ready(function () {

  /**
   * Dropdown adjustments
   */

  var slideConfig = {
    duration: 270,
    easing: 'easeOutSine'
  };

  // Add dropdown animations when toggled.
  $(':not(.main-sidebar--icons-only) .dropdown').on('show.bs.dropdown', function () {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(slideConfig);
  });

  $(':not(.main-sidebar--icons-only) .dropdown').on('hide.bs.dropdown', function () {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(slideConfig);
  });

  /**
   * Sidebar toggles
   */
  $('.toggle-sidebar').click(function (e) {
    $('.main-sidebar').toggleClass('open');
  });
});