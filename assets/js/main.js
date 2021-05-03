jQuery(document).ready(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 54)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 56
  });

  $("ul.nav-tabs a").click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // Collapse Navbar
  var navbarCollapse = function() {
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

  // Hide navbar when modals trigger
  $('.portfolio-modal').on('show.bs.modal', function(e) {
    $('.navbar').addClass('d-none');
  })
  $('.portfolio-modal').on('hidden.bs.modal', function(e) {
    $('.navbar').removeClass('d-none');
  })

  $(document).on('click', '#load_more_blog', function( event ) {
    event.preventDefault();
    var max_page = $(this).data("max_page");
    var button = $(this);
    var data = {
      action: 'robo_get_blog_post',
      posts_per_page: robo.posts_per_page,
      page: robo.current_page,
    };

    $.ajax({
      url: robo.ajaxurl,
      type: 'POST',
      data: data,
      beforeSend : function ( xhr ) {
        button.text('Loading...');
      },
      success : function( data ){
        if( data ) {
          $('#blog_posts').append(data);
          button.text('Load More');
          robo.current_page++;
          if ( robo.current_page == max_page )
            button.remove();
        } else {
          button.remove();
        }
      }
    });
  });

      $(document).on('click', '#load_more', function(event) {
          event.preventDefault();
          var button = $(this);
          var data = {
            'query': robo.posts,
            'page' : robo.current_page,
            action : 'robo_get_post'
          };

          $.ajax({
            url: robo.ajaxurl,
            type: 'POST',
            data: data,
            beforeSend : function ( xhr ) {
              button.text('Loading...'); // change the button text, you can also add a preloader image
            },
            success : function( data ){
              if( data ) {
                // button.text('Load More').prev().before( data ); // insert new posts
                $('#blog_posts').append(data);
                button.text('Load More');
                robo.current_page++;
                if ( robo.current_page == robo.max_page )
                  button.remove(); // if last page, remove the button
              } else {
                button.remove(); // if no data, remove the button as well
              }
            }
          });
      });

      $(document).on('click', '#event_date', function(event) {
        event.preventDefault();
          var data = {
            action: 'robo_get_event'
          };

          $.ajax({
              url: robo.ajaxurl,
              type: 'POST',
              data: data,
              beforeSend : function ( xhr ) {

              },
              success : function( data ){

              }
          });
      });


        var slickOpts = {
          slidesToShow: 1,
          slidesToScroll: 1,
          //centerMode: true,
          easing: 'swing', // see http://api.jquery.com/animate/
          speed: 700,
          dots: true,
          arrows:false,
          customPaging: function(slick,index) {
              return '<a>' + (index + 1) + '</a>';
          }
        };

        // Init slick carousel
        $('#event_slider_owl').slick( slickOpts );

       var element = document.getElementById("event-calendar");
        // // Create the calendar
        if( element != null ) {
            var myCalendar = jsCalendar.new({
                target: element,
               monthFormat : "month YYYY",
              dayFormat : "DDD",
            });

            window.onload = function(){
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;

                var data = {
                  action: 'robo_get_event',
                  'event_date': today
                };

                $.ajax({
                    url: robo.ajaxurl,
                    type: 'POST',
                    data: data,
                    beforeSend : function ( xhr ) {

                    },
                    success : function( data ){
                      $('#event_slider_owl').empty();
                      $('#event_slider_owl').append(data);
                    }
                });
            };


            // Add events
            myCalendar.onDateClick( function( event, date ) {
                var edate = new Date(date);
                var dd = String(edate.getDate()).padStart(2, '0');
                var mm = String(edate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = edate.getFullYear();

                var today = yyyy + '-' + mm + '-' + dd;
                console.log(today);
                var data = {
                  action: 'robo_get_event',
                  'event_date': today
                };

                $.ajax({
                    url: robo.ajaxurl,
                    type: 'POST',
                    data: data,
                    beforeSend : function ( xhr ) {

                    },
                    success : function( data ){
                      $('#event_slider_owl').empty();
                      $('#event_slider_owl').append(data);

                    }
                });
            });
          }

        var previouscompetitionowls = $("#comp_slider_unq");
        previouscompetitionowls.owlCarousel({

            autoplay: false,
            autoplayTimeout:6000,
            smartSpeed:1400,
            autoplayHoverPause:true,
            items: 1,
            loop: true,
            center: false,
            margin: 0,
            stagePadding: 0,
            dots:false,
            nav:true,
            lazyLoad:true,

            navText : ["<i class='fas fa-chevron-left fa-2x'></i>","<i class='fas fa-chevron-right fa-2x'></i>"]
        });

        $('#jquery-datepicker').datepicker({ dateFormat: 'dd-mm-yy' });

}); // End of use strict
