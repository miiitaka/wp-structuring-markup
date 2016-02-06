/* Markup (JSON-LD) structured in schema.org */
jQuery(document).ready(function ($) {
  'use strict';

  // add row
  $('.schema-admin-table').on('click', '.markup-time.plus', function () {
    var newRow = $(this).closest('tr.opening-hours').clone();
    $(this).closest('tr.opening-hours').after(newRow);
    newRow.find('input').val('');
    newRow.find('.minus').show();
    newRow.find('input').each(function () {
      var
        name = $(this).prop('name'),
        currentIndex = parseInt(name.split('][')[2], 10),
        nextIndex = currentIndex + 1;

      if (currentIndex === 0) {
        $(this).parent().find('.plus:last-child').after('<a class="dashicons dashicons-minus markup-time minus"></a>');
      }
      $(this).prop('name', name.replace(currentIndex, nextIndex));
    });
  });

  // remove row
  $('.schema-admin-table').on('click', '.markup-time.minus', function () {
    $(this).closest('tr.opening-hours').fadeOut('normal', function () {
      $(this).remove();
    });
  });
});