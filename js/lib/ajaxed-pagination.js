(function ($) {
  let page = 1;
  $(".load-more-button").on("click", function () {
    page++;

    const data = {
      action: "load_more_posts",
      page: page,
    };

    $.ajax({
      url: ajaxpagination.ajaxurl,
      data: data,
      type: "POST",
      beforeSend: function (xhr) {
        $(".load-more-button").text("Loading...");
      },
      success: function (data) {
        if (data) {
          $("#posts").append(data);
          $(".load-more-button").text("Load more");
          $(".load-more-button").append('<span class="icon icon-right"></span>');
          initDialog();
        } else {
          $(".load-more-button").text(ajaxpagination.noposts);
          $(".load-more-button").prop("disabled", true);
        }
      },
    });
  });
})(jQuery);
