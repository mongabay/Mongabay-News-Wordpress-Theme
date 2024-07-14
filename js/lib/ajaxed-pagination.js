(function ($) {
  let page = 1;

  $(".load-more-button").on("click", function () {
    page++;

    const postType = $(this).data("post-type");
    const taxonomy = $(this).data("taxonomy");
    const terms = $(this).data("terms");
    const perPage = $(this).data("per-page");
    const preloader =
      '<div class="preloader-wrapper"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g stroke="currentColor"><circle cx="12" cy="12" r="9.5" fill="none" stroke-linecap="round" stroke-width="3"><animate attributeName="stroke-dasharray" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1" repeatCount="indefinite" values="0 150;42 150;42 150;42 150"/><animate attributeName="stroke-dashoffset" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1" repeatCount="indefinite" values="0;-16;-59;-59"/></circle><animateTransform attributeName="transform" dur="2s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/></g></svg></div>';

    const loadingButton = preloader + "Loading...";
    const initialButton = "Load more" + '<span class="icon icon-right"></span>';
    const noResultsButton = "No older posts";

    const data = {
      action: "load_more_posts",
      page: page,
      post_type: postType,
      taxonomy: taxonomy,
      terms: terms,
      posts_per_page: perPage,
    };

    $.ajax({
      url: ajaxpagination.ajaxurl,
      data: data,
      type: "POST",
      beforeSend: function () {
        $(".load-more-button").html(loadingButton);
      },
      success: function (data) {
        if (data) {
          $("#posts").append(data);
          $(".load-more-button").html(initialButton);
          $(".load-more-button").data("post-type", postType);

          if (postType === "short-article") {
            initDialog();
          }
        } else {
          console.log("No more posts");
          $(".load-more-button").html(noResultsButton);
          $(".load-more-button").addClass("disabled");
        }
      },
    });
  });
})(jQuery);
