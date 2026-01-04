$(document).ready(function () {
  $('.blogStyle').owlCarousel({
    loop: true,
    margin: 30,
    responsiveClass: true,
    nav: true,
    navText: [
      "<img src='images/prv.png' class='nav-btn prev-slide'>",
      "<img src='images/next.png' class='nav-btn next-slide'>"
    ],
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2

      },
      1000: {
        items: 4
      }
    }
  });


  $('.testimonials').owlCarousel({
    loop: true,
    margin: 100,
    responsiveClass: true,
    dots: true,   // must be true
    nav: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 1

      },
      1000: {
        items: 2
      }
    }
  });

  $('.videoStyle').owlCarousel({
    loop: true,
    margin: 30,
    responsiveClass: true,
    dots: false,
    nav: true,
    navText: [
      "<img src='images/prv.png' class='nav-btn prev-slide'>",
      "<img src='images/next.png' class='nav-btn next-slide'>"
    ],
    responsive: {
      0: {
        items: 1,
        nav: true,
      },
      600: {
        items: 2,
        nav: true,

      },
      1000: {
        items: 3,
        nav: true,
      }
    }
  });



  $(".read-more-btn").click(function () {
    const moreText = $(".more-text");

    moreText.slideToggle(200);

    $(this).text(
      moreText.is(":visible") ? "Read less" : "Read more"
    );
  });


})