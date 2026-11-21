/**
 * Initialize Masonry for shorts grid layouts
 */

(function () {
  "use strict";

  function initMasonry() {
    const shortsGrids = document.querySelectorAll(".shorts.grid--3");

    if (shortsGrids.length === 0 || typeof MasonryGrid === "undefined") {
      return;
    }

    shortsGrids.forEach(function (grid) {
      // Skip if already initialized
      if (grid.dataset.masonryInit === "true") {
        return;
      }

      // Wait for images to load
      const images = grid.querySelectorAll("img");
      let loadedImages = 0;

      function checkAllImagesLoaded() {
        loadedImages++;
        if (loadedImages === images.length || images.length === 0) {
          initGrid(grid);
        }
      }

      if (images.length === 0) {
        initGrid(grid);
      } else {
        images.forEach(function (img) {
          if (img.complete) {
            checkAllImagesLoaded();
          } else {
            img.addEventListener("load", checkAllImagesLoaded);
            img.addEventListener("error", checkAllImagesLoaded);
          }
        });
      }
    });
  }

  function initGrid(grid) {
    grid.dataset.masonryInit = "true";

    new MasonryGrid({
      container: grid,
      itemSelector: ".article--container",
      columnGap: 40,
      rowGap: 40,
      responsive: {
        768: {
          columnCount: 2,
        },
        1024: {
          columnCount: 3,
        },
      },
    });
  }

  // Initialize on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initMasonry);
  } else {
    initMasonry();
  }

  // Expose for re-initialization after dynamic content loads
  window.initShortsMasonry = initMasonry;
})();
