jQuery(document).ready(function($){
  // !!!
  // All scripts are loaded with a delay (after user mouse movement),
  // this file is an exception for delay scripts
  // !!!

    //Cookie
  //var userCity = document.cookie.replace(/(?:(?:^|.*;\s*)user_city\s*\=\s*([^;]*).*$)|^.*$/, "$1");
  // $('.city-button').on('click', function() {
  //   var selectedCity = $(this).data('city');
  //   document.cookie = 'user_city=' + selectedCity + '; path=/';
  //   window.location.href = '/';
  // });

  $('.header__selected-city').on('click', function() {
    $(this).toggleClass('active');
    $('.header__select-sity').toggleClass('active');
    $('.current-language, .drop-block.lang').removeClass('active');
  });

  // Mask banner
  function applyMask(elementId) {
    var element = document.getElementById(elementId);
    if (element) {
      var submitButton = $(element).find('input[type="submit"]');
      var submitDiv = $(element).find('.banner__submit-div');
      var maskValue = element.getAttribute("data-mask");
      var textInvalid = element.getAttribute("data-invalid");
      var invalidTip = $('<span class="wpcf7-not-valid-tip">' + textInvalid + '</span>');
      var formElement = $(element).find('.wpcf7-form');
      var timeoutId;

      submitButton.css('pointer-events', 'none'); // Замінили disabled на pointer-events
      $(".tel-global").mask(maskValue);
      $('input[name="chystota-tel"]').on('keyup', function() {
        var inputValue = $(this).val();
        var digitCount = countDigitsInMask(maskValue);
        if (digitCount === inputValue.replace(/\D/g, '').length) {
          submitButton.css('pointer-events', 'auto'); // Замінили disabled на pointer-events
          formElement.removeClass('invalid');
          invalidTip.remove();
        } else {
          submitButton.css('pointer-events', 'none'); // Замінили disabled на pointer-events
        }
      });

      $(submitDiv).on('mouseenter', function() {
        if (submitButton.css('pointer-events') === 'none') {
          formElement.addClass('invalid');
          formElement.find('.wpcf7-form-control-wrap').append(invalidTip);
          if (timeoutId) {
            clearTimeout(timeoutId);
          }
          timeoutId = setTimeout(function() {
            formElement.removeClass('invalid');
            invalidTip.remove();
          }, 4000);
        }
      });
    }
  }

  function countDigitsInMask(maskValue) {
    var digitMatches = maskValue.match(/\d/g);
    return digitMatches ? digitMatches.length : 0;
  }

  applyMask("banner__form");
  applyMask("mobile-tells");
});
