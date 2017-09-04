window.$ = window.jQuery = require('jquery');

(function ($) {
  'use strict';

  $('#mobile-sidebar-open-trigger').on('click', function () {
    $('.sidebar').addClass('sidebar--open');
  });

  $('#mobile-sidebar-close-trigger').on('click', function () {
    $('.sidebar').removeClass('sidebar--open');
  });

  $('.sidebar__overlay').on('click', function () {
    $('.sidebar').removeClass('sidebar--open');
  });
})(jQuery)
