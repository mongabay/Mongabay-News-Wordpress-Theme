(function () {
  let isLoading = false;
  let hasMore = true;
  let currentPage = 1;

  function loadMoreShortVideos() {
    const container = document.getElementById('short-videos-listing');
    const loadingIndicator = document.getElementById('short-videos-loading');
    
    if (!container || isLoading || !hasMore) {
      return;
    }

    isLoading = true;
    container.setAttribute('data-loading', 'true');
    
    if (loadingIndicator) {
      loadingIndicator.style.display = 'flex';
    }

    const nextPage = currentPage + 1;
    const postsPerPage = 5;

    const formData = new FormData();
    formData.append('action', 'load_more_short_videos');
    formData.append('page', nextPage);
    formData.append('posts_per_page', postsPerPage);

    const ajaxUrl =
      (window.shortVideosScrollConfig && window.shortVideosScrollConfig.ajaxurl) ||
      window.ajaxurl ||
      '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success && data.data.html) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = data.data.html;
          
          const loadingIndicator = document.getElementById('short-videos-loading');

          // Insert new items before the loading indicator
          while (tempDiv.firstChild) {
            if (loadingIndicator) {
              container.insertBefore(tempDiv.firstChild, loadingIndicator);
            } else {
              container.appendChild(tempDiv.firstChild);
            }
          }

          currentPage = nextPage;
          hasMore = data.data.has_more;
          container.setAttribute('data-page', currentPage);
          container.setAttribute('data-has-more', hasMore);

          console.log('Loaded page', currentPage, 'has more:', hasMore);
        } else {
          hasMore = false;
          container.setAttribute('data-has-more', 'false');
        }
      })
      .catch((error) => {
        console.error('Error loading more short videos:', error);
      })
      .finally(() => {
        const loadingIndicator = document.getElementById('short-videos-loading');
        if (loadingIndicator) {
          loadingIndicator.style.display = 'none';
        }
        
        isLoading = false;
        container.setAttribute('data-loading', 'false');
      });
  }

  function handleScroll(e) {
    const container = e.target;
    const scrollLeft = container.scrollLeft;
    const scrollWidth = container.scrollWidth;
    const clientWidth = container.clientWidth;

    // Trigger load when user scrolls to 80% of the content
    const threshold = scrollWidth * 0.8;

    if (scrollLeft + clientWidth >= threshold && !isLoading && hasMore) {
      loadMoreShortVideos();
    }
  }

  function init() {
    const container = document.getElementById('short-videos-listing');
    if (!container) {
      return;
    }

    // Get initial state from data attributes
    const pageAttr = container.getAttribute('data-page');
    const hasMoreAttr = container.getAttribute('data-has-more');

    if (pageAttr) {
      currentPage = parseInt(pageAttr, 10);
    }
    if (hasMoreAttr) {
      hasMore = hasMoreAttr === 'true';
    }

    // Add scroll event listener
    container.addEventListener('scroll', handleScroll);

    console.log('Short videos horizontal scroll initialized');
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
