jQuery(document).ready(function($) {
    $('a[href^="#/"]').on('click', function(e){
      e.preventDefault();
    })
    $(document).on("click", ".filter, .page-numbers", function(e) {
        e.preventDefault();

        if($(this).hasClass('blog-posts__category')) {
            $('.blog-posts__category.active').removeClass('active');
            $(this).addClass('active');
        }
        else {
            $('.page-numbers.current').removeClass('active');
            $(this).addClass('current');
        }
  
        let categoryID = ($(this).hasClass("blog-posts__category")) ? $(this).data('category') : $(".blog-posts__category.active").data('category');
        let page = ($(this).hasClass("page-numbers")) ? $(this).text() : 1;
        let urlBlog = $(this).data('url') ? $(this).data('url') : $(this).attr('href');
        
        //let page = ($(this).hasClass("page-numbers")) ? $(this).text() : $(".page-numbers.current").text();
        //   console.log(categoryID);
        //   console.log(Number(page));
  
        $.ajax({
            url: mmc_ajax_params.ajaxurl,
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'filter_posts',
                category_id: categoryID,
                page_num: Number(page)
            },
            success: function(response) {
                $('.blog-posts__wrap').html(response);
                history.pushState(null, null, urlBlog);
            }
        });
    });

      $('.archive__category').on('click', function(e){
        e.preventDefault();
        var categoryID = $(this).data('category-id');
        $('.blog-posts__category.active').removeClass('active');
        $('.blog-posts__category[data-category="' + categoryID + '"]').addClass('active');

        if ($("#post-category").length) {
            var categoryText = $(this).text();
            $("#post-category").text(categoryText);
        }
        $.ajax({
            url: mmc_ajax_params.ajaxurl,
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'filter_posts',
                category_id: categoryID
            },
            success: function(response) {
                $('.blog-posts__wrap').html(response);
            }
        });
      });
 
      $(document).ready(function() {
        // Перевірте, чи URL містить параметр category_id
        var urlParams = new URLSearchParams(window.location.search);
        var categoryID = urlParams.get('id');
        if (categoryID) {
            $('.blog-posts__category.active').removeClass('active');
            $('.blog-posts__category[data-category="' + categoryID + '"]').addClass('active');
            var target = $("#blog-posts");
            if (target.length) {
              var offset = target.offset().top - 50;
              $("html, body").animate({
                scrollTop: offset
              }, 1000);
            }
            $.ajax({
                url: mmc_ajax_params.ajaxurl,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'filter_posts',
                    category_id: categoryID
                },
                success: function(response) {
                    $('.blog-posts__wrap').html(response);
                }
            });
        }
    });
  
      $('.basic-cleaning__menu a').click(function() {
          $('.basic-cleaning__menu a').removeClass('active');
          $(this).addClass('active');
          var categoryId = $(this).attr('id');
          var template = $(this).data('post-template');
          $.ajax({
              url: mmc_ajax_params.ajaxurl,
              type: 'POST',
              data: {
                  action: 'load_category_posts',
                  category_id: categoryId,
                  template : template,
              },
              beforeSend: function(response) {
                  $('.basic-cleaning__image, .basic-cleaning__row').addClass('animate__animated animate__fadeOut animate__fast');
              },
              success: function(response) {
                  $('.basic-cleaning__column').html(response);
                  $('.basic-cleaning__image, .basic-cleaning__row').addClass('animate__animated animate__fadeIn animate__fast')
              }
          });
      });
  
      $(document).on('click', '.basic-cleaning__row .column', function() {
          $('.basic-cleaning__row .column').removeClass('active');
          $(this).addClass('active');
          var postId = $(this).data('post-id')
          var template = $(this).data('post-template');
          var postCount = $(this).data('post-count');
          $.ajax({
              url: mmc_ajax_params.ajaxurl,
              type: 'POST',
              data: {
                  action: 'load_category_posts',
                  post_id: postId,
                  template : template,
                  post_count : postCount,
              },
              beforeSend: function(response) {
                  $('.column__head p, .column__head .column__body').addClass('animate__animated animate__fadeOut animate__fast');
                  $('.basic-cleaning__image .gallery_one img').addClass('animate__animated animate__fadeOut animate__fast');
                  $('.basic-cleaning__image .gallery_two img').addClass('animate__animated animate__fadeOut animate__fast');
              },
              success: function(response) {
                  $('.basic-cleaning__image').replaceWith(response);
                  $('.column__head p, .column__head .column__body').addClass('animate__animated animate__fadeIn animate__fast');
                  $('.basic-cleaning__image .gallery_one img').addClass('animate__animated animate__section10Left animate__fast');
                  $('.basic-cleaning__image .gallery_two img').addClass('animate__animated animate__fadeIn animate__fast');
              }
          });
      });
  });
  