const slickHeight = jQuery(".slick-track").outerHeight();
const slideHeight = jQuery(".slick-track").find(".slick-slide").outerHeight();

function resizeSlider() {
  jQuery(".slick-track")
    .find(".slick-slide .slide-wrap")
    .css("height", slickHeight + "px");
}

jQuery(".slider-featured")
  .slick({
    infinite: true,
    centerMode: true,
    centerPadding: "40px",
    arrows: false,
    slidesToShow: 1,
    dots: true,
    lazyLoad: "ondemand",
  })
  .on("setPosition", function () {
    resizeSlider();
  });

jQuery(".slider-formats")
  .slick({
    infinite: true,
    centerMode: true,
    centerPadding: "25%",
    arrows: false,
    slidesToShow: 1,
    dots: true,
    lazyLoad: "ondemand",
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
          dots: true,
          centerPadding: "10%",
        },
      },
    ],
  })
  .on("setPosition", function () {
    resizeSlider();
  });

jQuery(window).on("resize", function (e) {
  resizeSlider();
});
