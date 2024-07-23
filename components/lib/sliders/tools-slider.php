<?php
function tools_slider()
{ ?>

  <div id="tools-container" class="container section--highlight in-column pv--40 ph--40 gap--80">
    <div class="container full-width">
      <h1><?php _e('Interested in other Mongabay websites?', 'mongabay'); ?></h1>
    </div>
    <div class="container in-row gap--80 tools-top">
      <div class="tools-item">
        <a href="https://earthhq.org/" target="_blank" title="Earth HQ"><img src="<?php echo get_template_directory_uri(); ?>/img/earthhq_banner.jpg" alt="Earth HQ" /></a>
      </div>
      <div class="tools-item">
        <a href="https://studio.mongabay.com" target="_blank" title="Mongabay Data Studio"><img src="<?php echo get_template_directory_uri(); ?>/img/data_studio.jpg" alt="Data Studio" /></a>
      </div>
      <div class="tools-item">
        <a href="https://conservationeffectiveness.org" target="_blank" title="Conservation Effectiveness"><img src="<?php echo get_template_directory_uri(); ?>/img/conservation_effectiveness_banner.jpg" alt="Conservation Effectiveness" /></a>
      </div>
    </div>
    <div class="container full-width in-row gap--80 tools-bottom">
      <div class="tools-item">
        <a href="https://es.mongabay.com" target="_blank" title="Mongabay Latam"><img src="<?php echo get_template_directory_uri(); ?>/img/mongabay_es_banner.svg" alt="Mongabay Latam" /></a>
      </div>
      <div class="tools-item">
        <a href="https://reforestation.app" target="_blank" title="Reforestation App"><img src="<?php echo get_template_directory_uri(); ?>/img/reforestationapp_banner.jpg" alt="Reforestation App" /></a>
      </div>
      <div class="tools-item">
        <a href="https://mongabay.co.id" target="_blank" title="Mongabay Indonesia"><img src="<?php echo get_template_directory_uri(); ?>/img/mongabay_indonesia_banner.svg" alt="Mongabay Indonesia" /></a>
      </div>
    </div>
  </div>
<?php
  // echo '<script>
  // function scrollHandler() {
  //   const sectionWidth = document.querySelector("#tools-container").offsetWidth;
  //   const sectionHeight = document.querySelector("#tools-container").offsetHeight;
  //   const scrollTop = window.scrollY;
  //   const scrollBottom = scrollTop + window.innerHeight;
  //   const carruselTop = document.querySelector(".tools-top");
  //   const carruselBottom = document.querySelector(".tools-bottom");

  //   if (carruselTop) {
  //       const carruselTopRect = carruselTop.getBoundingClientRect();
  //       const carruselTopWidth = carruselTopRect.width;
  //       const carruselTopScrollWidth = carruselTop.scrollWidth;
  //       let scrollXTop = (scrollTop + sectionHeight) % carruselTopScrollWidth;

  //       if (scrollXTop > carruselTopWidth && scrollXTop < sectionWidth) {
  //           scrollXTop -= carruselTopWidth;
  //       }

  //       carruselTop.style.transform = `translateX(-${scrollXTop}px)`;
  //   }

  //   if (carruselBottom) {
  //       const carruselBottomRect = carruselBottom.getBoundingClientRect();
  //       const carruselBottomWidth = carruselBottomRect.width;
  //       const carruselBottomScrollWidth = carruselBottom.scrollWidth;

  //       let scrollXBottom = (scrollTop + sectionHeight) % carruselBottomScrollWidth;

  //       if (scrollXBottom > carruselBottomWidth && scrollXBottom < sectionWidth) {
  //           scrollXBottom -= carruselBottomWidth;
  //       }

  //       carruselBottom.style.transform = `translateX(${scrollXBottom}px)`;
  //   }
  // }

  // window.addEventListener("scroll", scrollHandler);
  // </script>';
}
