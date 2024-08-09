jQuery.event.special.touchstart = {
  setup: function( _, ns, handle ) {
    this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
  }
};
jQuery.event.special.touchmove = {
  setup: function( _, ns, handle ) {
    this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
  }
};
jQuery.event.special.wheel = {
  setup: function( _, ns, handle ){
    this.addEventListener("wheel", handle, { passive: true });
  }
};
jQuery.event.special.mousewheel = {
  setup: function( _, ns, handle ){
    this.addEventListener("mousewheel", handle, { passive: true });
  }
};

jQuery(document).ready(function($){
  // Language switch
  $('.current-language').click(function() {
    $(this).toggleClass('active');
    $('.drop-block.lang').toggleClass('active');
    $('.header__selected-city, .header__select-sity').removeClass('active');
  });

  // Additional-services block
  $('.additional-services__slider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
    arrows: false,
    fade: true,
    speed: 400,
  });

  $('.additional-services__categories').on('click', '.slider-dot', function() {
    var tabId = $(this).data('tab');
    var tabIndex = parseInt(tabId.replace('tab', '')) - 1;
    $('.additional-services__slider').slick('slickGoTo', tabIndex);
  });

  $('.additional-services__slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
    $('.slider-dot').removeClass('active');
    $('.slider-dot[data-tab="tab' + (nextSlide + 1) + '"]').addClass('active');
    $(slick.$slides[currentSlide]).addClass(' animate__fadeOut animate__faster');
    $(slick.$slides[nextSlide]).removeClass(' animate__fadeOut animate__faster').addClass(' animate__fadeIn animate__faster');
  });

  $('.additional-services__slider').on('afterChange', function(event, slick, currentSlide) {
    $(slick.$slides[currentSlide]).removeClass(' animate__fadeOut animate__faster');
  });

  var additionalMenu = $('#additional-menu');
  if (additionalMenu.length > 0) {
    additionalMenu.append('<li class="slide-line"></li>');
    var firstTabLink = additionalMenu.find('.tablinks:first-child');
    if (firstTabLink.length > 0) {
      var firstWidth = firstTabLink.outerWidth();
      var tablinksY = $(window).width() >= 768 ? 29 : 19;
      gsap.set('#additional-menu .slide-line', {
        width: firstWidth,
        x: 0,
        y: tablinksY
      });
      $(document).on('click', '#additional-menu li a', function() {
        var $this = $(this),
          offset = $this.offset(),
          offsetBody = $('#additional-box').offset();
        var slideLine = $('#additional-menu .slide-line');
        if (slideLine.length > 0) {
          gsap.to(slideLine, {
            duration: 0.6,
            css: {
              width: $this.outerWidth() + 'px',
              x: (offset.left - offsetBody.left) + 'px',
              y: (offset.top - offsetBody.top + $this.height()) + 'px',
              rotation: 0.01
            },
            ease: 'power1.out'
          });
        }
        return false;
      });
      $('#basic-cleaning__menu > li a').eq(0).trigger('click');
    }
  }

  // block-modern-team
  $('.modern-team__team').slick({
    infinite: false,
  	slidesToShow: 5,
  	slidesToScroll: 1,
  	dots: true,
  	arrows: true,
  	prevArrow: $('.modern-team .modern-team__prev-slide'),
      nextArrow: $('.modern-team .modern-team__next-slide'),
      responsive: [
    {
      breakpoint: 1180,
      settings: {
        slidesToShow: 3,
      }
    },
    {
        breakpoint: 750,
        settings: {
          slidesToShow: 1.5,
          arrows: false,
        }
      }
    ],
  });

  // block-about-us
  $('.about-us__slider-photos').slick({
    infinite: false,
  	slidesToShow: 2,
  	slidesToScroll: 1,
  	arrows: true,
    dots: true,
  	prevArrow: $('.slider-navigation .about-us__prev-slide'),
	  nextArrow: $('.slider-navigation .about-us__next-slide'),
    responsive: [
      {
        breakpoint: 1180,
        settings: {
          dots: true
        }
      },
      {
        breakpoint: 501,
        settings: {
          slidesToShow: 1,
          dots: true,
          arrow: false,
        }
      },
    ],
  });

  // answers-questions
  $(".answers-questions__item").click(function () {
    var currentItem = $(this);
    var currentAnswer = currentItem.find(".answers-questions__body");
    $(".answers-questions__item.active").not(currentItem).removeClass("active");
    $(".answers-questions__body").not(currentAnswer).css("max-height", "0");
    currentItem.toggleClass("active");
    if (currentItem.hasClass("active")) {
      currentAnswer.css("max-height", currentAnswer.prop("scrollHeight") + "px");
    } else {
      currentAnswer.css("max-height", "0");
    }
  });

  $(document).on('click', '.page-numbers, .archive__category', function(e) {
    var target = $("#blog-posts");
    if (target.length) {
      var offset = target.offset().top - 120;
      $("html, body").animate({
        scrollTop: offset
      }, 1000);
    }
  });  

  // block-about-us
  var $activeCard = null;
  $('.flip-card').on('click', function () {
    var $card = $(this);
    if ($card.is($activeCard)) {
      $card.css('transform', 'rotateY(0)');
      $activeCard = null;
    } else {
      if ($activeCard !== null) {
        $activeCard.css('transform', 'rotateY(0)');
      }
      $activeCard = $card;
      $card.css('transform', 'rotateY(180deg)');
    }
  });

  // Progress line (header)
  const scrollInput= document.querySelector('#progress');
  $(function() {
    $(window).on("resize scroll", function() {
      updateProgress(scrollInput);
    });
    
    function updateProgress(element) {
      let height = $(document).height() - $(window).height(),
          progress = $(window).scrollTop() / height;
      
      $(element).css("width", (progress * 100) + "%");
    }
    
  });

  // Hide part of the menu while scrolling
  $(document).ready(function() {
    var lastScrollTop = 0;
    var header = $("#header");
    $(window).scroll(function() {
        var scrollTop = $(this).scrollTop();
        if (scrollTop > 80) {
            if (scrollTop > lastScrollTop) {
                header.addClass("unpinned");
            } else {
                header.removeClass("unpinned");
            }
        }
        lastScrollTop = scrollTop;
    });
  });
  // block_about-us
  $('.our-works__slider').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    prevArrow: $('.our-works__prev-slide'),
    nextArrow: $('.our-works__next-slide'),
    responsive: [
      {
        breakpoint: 1180,
        settings: {
          dots: true
        }
      },
      {
        breakpoint: 750,
        settings: {
          slidesToShow: 1,
          dots: true,
          arrows: false,
        }
      },
    ],
  });

  // block-social-responsibility
  $('.social-responsibility__slider').slick({
    infinite: false,
  	slidesToShow: 1,
  	slidesToScroll: 1,
  	dots: true,
  	arrows: true,
    prevArrow: $('.social-responsibility__navigation .prev-slide'),
    nextArrow: $('.social-responsibility__navigation .next-slide'),
    responsive: [
      {
        breakpoint: 1180,
        settings: {
          arrows: false,
        }
      }
    ],
  });

  $("#single-banner__copy").click(function () {
    copyLink();
  });
  function copyLink() {
    var link = document.createElement('textarea');
    link.value = window.location.href;
    document.body.appendChild(link);
    link.select();
    document.execCommand('copy');
    document.body.removeChild(link);
    alert('Link copied!');
  }


  //Anchor
  // $("a.anchor-link").click(function(e) {
  //   e.preventDefault();

  //   var target = $($(this).attr("href"));
  //   if (target.length) {
  //     var offset = target.offset().top;
  //     $("html, body").animate({
  //       scrollTop: offset
  //     }, 1000);
  //   }
  // });

  $("a.anchor-top").click(function(e) {
    e.preventDefault();
    $("html, body").animate({
      scrollTop: 0
    }, 1000);
  });

  $('.price-of-window__gallery').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    prevArrow: $('.price-of-window__box .prev-slide'),
    nextArrow: $('.price-of-window__box .next-slide'),
    responsive: [
      {
        breakpoint: 1180,
        settings: {
          dots: true,
          arrows: false
        }
      },
      {
        breakpoint: 501,
        settings: {
          slidesToShow: 1,
          dots: true,
          arrows: false,
        }
      },
    ],
  });

  $('#windows-balconies__items-num').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    prevArrow: '.windows-balconies__arrow-prev',
    nextArrow: '.windows-balconies__arrow-next'
  });
  
  $('#windows-balconies__items').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    prevArrow: '.windows-balconies__items-prev',
    nextArrow: '.windows-balconies__items-next'
  });

  // Footer
  $('.footer__column-title').click(function() {
    $(this).toggleClass('active').siblings('.footer__subnav').toggleClass('active');
  });

  //basic-cleaning
  var basic_cleaning_menu = $('#basic-cleaning__menu');
  if (basic_cleaning_menu.length > 0) {
    basic_cleaning_menu.append('<li class="slide-line"></li>');
    var firstTabLinkBasic = basic_cleaning_menu.find('.basic-cleaning__btn:first-child');
    if (firstTabLinkBasic.length > 0) {
      var firstWidth = firstTabLinkBasic.outerWidth();
      var tablinksY = $(window).width() >= 768 ? 28 : 19;
      gsap.set('#basic-cleaning__menu .slide-line', {
        width: firstWidth,
        x: 0,
        y: tablinksY
      });
      $(document).on('click', '#basic-cleaning__menu li a', function() {
        var $this = $(this),
          offset = $this.offset(),
          offsetBody = $('#basic-cleaning__box').offset();
        var slideLine = $('#basic-cleaning__menu .slide-line');
        if (slideLine.length > 0) {
          gsap.to(slideLine, {
            duration: 0.6,
            css: {
              width: $this.outerWidth() + 'px',
              x: (offset.left - offsetBody.left) + 'px',
              y: (offset.top - offsetBody.top + $this.height()) + 'px',
              rotation: 0.01
            },
            ease: 'power1.out'
          });
        }
        return false;
      });
    }
  }

  $('.dft-text-picture__gallery').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    prevArrow: $('.dft-text-picture__navigation .prev-slide'),
    nextArrow: $('.dft-text-picture__navigation .next-slide'),
    responsive: [
    {
      breakpoint: 1180,
      settings: {
        dots: true
      }
    },
    {
      breakpoint: 501,
      settings: {
        slidesToShow: 1,
        dots: true,
        arrows: false,
      }
    },
  ],
  });

  //Additions h2 for scrolling
  let headingH2 = document.querySelectorAll(".single-content__body h2");
  let countH2 = 1;
  for (let elem of headingH2) {
    elem.setAttribute("id","single" + '-' + countH2++);
  }
  $('.sidebar a[href^="#"]').on('click', function (event) {
    event.preventDefault();
    var target = $(this.hash);
    if (target.length) {
      $('html, body').animate({
        scrollTop: target.offset().top - 10
      }, 1000);
    }
  });
  var anchorLinks = $('a[href^="#single-"]');
  $(window).scroll(function () {
    anchorLinks.each(function () {
      var link = $(this);
      var target = $(link.attr('href'));
      var targetPosition = target.offset().top;
      var windowHeight = window.innerHeight;
      var scrollTop = $(window).scrollTop();
  
      if (scrollTop > targetPosition - windowHeight && scrollTop < targetPosition) {
        anchorLinks.removeClass('active');
        link.addClass('active');
        return false;
      }
    });
  });

  var closeBtn = document.querySelector('.popup-modal__close');
  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      document.getElementById('thankPopup').style.display = 'none';
    });
  }
  document.addEventListener('wpcf7mailsent', function(event) {
    function openPopup() {
      document.getElementById('thankPopup').style.display = 'block';
    }
    if ($('#banner__form').length > 0) {
      $('#banner__form input[type="submit"]').prop('disabled', true);
    }
    if ($('#mobile-tells').length > 0) {
      $('#mobile-tells input[type="submit"]').prop('disabled', true);
    }
    openPopup();
  }, false);

  $(document).ready(function() {
    // Знаходимо елемент з id "privacy-policy"
    var privacyPolicySection = $('#privacy-policy');
  
    // Якщо такий елемент існує
    if (privacyPolicySection.length > 0) {
      // Знаходимо існуючий ul елемент
      var ul = privacyPolicySection.find('.privacy-policy__menu');
  
      // Знаходимо всі h2 елементи в межах елемента з id "privacy-policy"
      var h2Elements = privacyPolicySection.find('h2[id^="single-"]');
  
      // Перебираємо знайдені h2 елементи
      h2Elements.each(function(index, h2Element) {
        // Отримуємо текст та id з h2 елементу
        var text = $(h2Element).text();
        var id = $(h2Element).attr('id');
  
        // Створюємо елемент li для списку та a для посилання
        var li = $('<li>');
        var a = $('<a>').attr('href', '#' + id);
  
        // Додаємо текст та структуру елементів li та a
        a.html(text);
        li.append(a);
  
        // Додаємо елемент li до існуючого ul
        ul.append(li);
  
        // Додаємо обробник подій для посилань
        a.on('click', function(e) {
          e.preventDefault();
          var target = $(a.attr('href')); // Вибираємо цільовий елемент за посиланням href
          if (target.length > 0) {
            $('html, body').animate({
              scrollTop: target.offset().top - 10
            }, 1000);
          }
        });
      });
  
      // Знаходимо всі посилання
      var anchorLinks = ul.find('a');
  
      $(window).on('scroll', function() {
        var scrollTop = $(window).scrollTop();
  
        anchorLinks.each(function() {
          var link = $(this);
          var target = $(link.attr('href'));
          var targetPosition = target.offset().top;
          var windowHeight = window.innerHeight;
          var scrollTop = $(window).scrollTop();
      
          if (scrollTop > targetPosition - windowHeight && scrollTop < targetPosition) {
            anchorLinks.removeClass('active');
            link.addClass('active');
          }
        });
      });
    }
  });
  if ($('#footer-mobile__menu').length) {
    var dataTextValue = $('#footer-mobile__menu').data('text');
    $('.footer-mobile__tells .wpcf7-submit').val(dataTextValue);
  }
  $(document).ready(function() {
    $(document).on('click', '.column__body-close', function() {
      var section = $(this).closest('.basic-cleaning__image');
      section.empty();
      $('.basic-cleaning__row .column.active').removeClass('active');
    });
  });
  /* footer-mobile__platform */
  $(document).ready(function() {
    $(".footer-mobile__display").click(function() {
      if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        $(".footer-mobile__item, .footer-mobile__display, .slide-line").removeClass("active");
        $(".footer-mobile__subitem").addClass("animate__fadeOutDown").removeClass("animate__fadeIn animate__faster'");
        setTimeout(function() {
          $(".footer-mobile__subitem").removeClass("active");
        }, 300);
      }
    });

    $(".footer-mobile__line").click(function() {
      $(".footer-mobile__display, .footer-mobile__item, .footer-mobile__display, .slide-line").removeClass("active");
      $(".footer-mobile__subitem").addClass("animate__fadeOutDown").removeClass("animate__fadeIn animate__faster'");
      setTimeout(function() {
        $(".footer-mobile__subitem").removeClass("active");
      }, 300);
    }); 

    $(".footer-mobile__item").click(function() {
      if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        $(".footer-mobile__display, .slide-line").removeClass("active");
        $(".footer-mobile__subitem").addClass("animate__fadeOutDown").removeClass("animate__fadeIn animate__faster'");
        setTimeout(function() {
          $(".footer-mobile__subitem").removeClass("active");
        }, 300);
      } else {
        $(this).addClass("active");
        $(".footer-mobile__item").not(this).removeClass("active");
        if ($(this).hasClass("footer-mobile__tellme")) {
          $(".footer-mobile__subitem").removeClass("active animate__fadeIn animate__faster'");
          $(".footer-mobile__display, .slide-line").addClass("active");
          $(".footer-mobile__tells").removeClass("animate__fadeOutDown").addClass('animate__fadeIn animate__faster active');
          // $(".footer-mobile__tells input").focus();
        } else if ($(this).hasClass("footer-mobile__chat")) {
          $(".footer-mobile__subitem").removeClass("active animate__fadeIn animate__faster'");
          $(".footer-mobile__display, .slide-line").addClass("active");
          $(".footer-mobile__chats").removeClass("animate__fadeOutDown").addClass('animate__fadeIn animate__faster active');
        }
      }
    });    

    var footerMobileMenu = jQuery('#footer-mobile__menu');
    if (footerMobileMenu.length > 0) {
      // Додати елемент .slide-line до меню
      footerMobileMenu.append('<li class="slide-line"></li>');
      var firstTabLink = footerMobileMenu.find('.tablinks:first-child');
      if (firstTabLink.length > 0) {
        var firstWidth = firstTabLink.outerWidth();
        // Встановити початкову позицію та ширину для .slide-line
        gsap.set('#footer-mobile__menu .slide-line', {
          width: firstWidth,
          x: 32,
          y: 72
        });
        // Анімувати slide-line при кліку
        jQuery(document).on('click', '#footer-mobile__menu li a', function() {
          var $this = jQuery(this),
            offset = $this.offset(),
            // Знайти зміщення обгортки div  
            offsetBody = jQuery('#footer-mobile__box').offset();
          var slideLine = jQuery('#footer-mobile__menu .slide-line');
          if (slideLine.length > 0) {
            gsap.to(slideLine, {
              duration: 0.5,
              css: {
                width: $this.outerWidth() + 'px',
                x: (offset.left - offsetBody.left) + 'px',
                y: (offset.top - offsetBody.top + $this.height()) + 'px',
                rotation: 0.01
              },
              ease: 'power1.out'
            });
          }
  
          return false;
        });
  
        // Активувати перший пункт меню при завантаженні сторінки
        jQuery('#tablinks > li a').first().trigger("click");
      }
    }
  });
  /* footer-mobile__platform */
  $(".header__menu-toggle").on("click", function () {
    $(this).css('display', 'none');
    $('.header__menu-close').css('display', 'block');
    $(".header-mobile").addClass('active');
    $('html').css('overflow', 'hidden');
    $(".header").addClass('mobile');
  });
  $(".header__menu-close").on("click", function () {
    $(this).css('display', 'none');
    $('.header__menu-toggle').css('display', 'block');
    $(".header-mobile").removeClass('active');
    $('html').css('overflow-y', 'scroll');
    $(".header").removeClass('mobile');
  });
  $(".header-mobile__title").on("click", function () {
    $(this).toggleClass('active');
    var menuId = $(this).data('menu');
    $("#" + menuId).slideToggle();
  });
  $('.header-mobile__subnav').filter(function() {
    return $(this).find('li.menu-item.current-menu-item').length > 0;
  }).find('.menu').css('display', 'block');

  $(".mobile-bnt").on("click", function () {
    $('.blog-mobile').css('display', 'flex');
    $('html').css('overflow', 'hidden');
  });
  $(".blog-mobile__close, .blog-mobile__category").on("click", function () {
    $('.blog-mobile').css('display', 'none');
    $('html').css('overflow-y', 'scroll');
  
    if ($(this).hasClass("blog-mobile__category")) {
      var categoryText = $(this).find("span").text();
      $("#post-category").text(categoryText);
    }
  });

  $('.column').on('click', function() {
    if ($(window).width() <= 1024 && $(window).width() > 765) {
      // Перевірка ширини екрану
      $('html, body').animate({
        scrollTop: $('.basic-cleaning__image').offset().top - 52
      }, 1000);
    }
  });

  //Utm
  let current_url = window.location.href;
  if(current_url.indexOf('?referralCode') > -1){
      let arr_storke = current_url.split('?');
      let adding_url = '?' + arr_storke[1];
      let referralCode = adding_url.split('referralCode=')[1].split('&')[0]
      $('input[name=utm_source]').val('Refferal marketing')
      $('input[name=utm_medium]').val(referralCode)
      $("a").each(function( index ) {
        let dflt_url = $(this).attr('href');
        $(this).attr('href', dflt_url + adding_url);
      });
  }

  if(current_url.indexOf('?utm') > -1){
      let arr_storke = current_url.split('?');
      let adding_url = '?' + arr_storke[1];

      if(current_url.indexOf('utm_source=') > -1){
          let utm_source = adding_url.split('utm_source=')[1].split('&')[0]
          $('input[name=utm_source]').val(utm_source)
      }
      if(current_url.indexOf('utm_medium=') > -1){
          let utm_medium = adding_url.split('utm_medium=')[1].split('&')[0]
          $('input[name=utm_medium]').val(utm_medium)
      }
      if(current_url.indexOf('utm_campaign=') > -1){
          let utm_campaign = adding_url.split('utm_campaign=')[1].split('&')[0]
          $('input[name=utm_campaign]').val(utm_campaign)
      }
      if(current_url.indexOf('utm_term=') > -1){
          let utm_term = adding_url.split('utm_term=')[1].split('&')[0]
          $('input[name=utm_term]').val(utm_term)
      }
      if(current_url.indexOf('utm_content=') > -1){
          let utm_content = adding_url.split('utm_content=')[1].split('&')[0]
          $('input[name=utm_content]').val(utm_content)
      }

      $("a").each(function( index ) {
        let dflt_url = $(this).attr('href');
        $(this).attr('href', dflt_url + adding_url);
      });
    }
  
});