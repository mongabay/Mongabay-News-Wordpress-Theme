jQuery(".slider-series").slick({
  infinite: true,
  centerMode: true,
  centerPadding: "40px",
  arrows: false,
  slidesToShow: 1,
  dots: false,
  lazyLoad: 'ondemand',
});

jQuery(".slider-featured").slick({
  infinite: true,
  centerMode: true,
  centerPadding: "40px",
  arrows: false,
  slidesToShow: 1,
  dots: true,
  lazyLoad: 'ondemand',
});
