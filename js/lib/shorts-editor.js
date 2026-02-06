jQuery(document).ready(function ($) {

  /** -----------------------------------
   * CONFIGURATION
   * ----------------------------------- */
  const FIELD_IDS = {
    "article-link": "#pods-form-ui-pods-meta-article-link",
    "video-link": "#pods-form-ui-pods-meta-video-link",
    "audio-link": "#pods-form-ui-pods-meta-audio-link",
  };

  const visibilityMap = {
    CONTENT: ["article-link"],
    VIDEO: ["video-link"],
    AUDIO: ["audio-link"],
  };


  /** -----------------------------------
   * GET SELECTED FORMAT (robust)
   * Reads from tag-checklist LI,
   * checkbox UI, or dropdown fallback.
   * ----------------------------------- */
  function getSelectedFormat() {
    // Read visible term label inside <li> (removes button text)
    const $li = $('#tagsdiv-shorts_format ul.tagchecklist li').first();
    if ($li.length) {
      const term = $li.clone().children().remove().end().text().trim();
      if (term) return term.toUpperCase();
    }

    // Checkbox fallback
    const $checked = $('#tagsdiv-shorts_format input[type="checkbox"]:checked').first();
    if ($checked.length) {
      return String($checked.val()).toUpperCase();
    }

    // Select fallback (rare but possible)
    const sel = $('#tagsdiv-shorts_format select').val();
    if (sel) return String(sel).toUpperCase();

    return null;
  }


  /** -----------------------------------
   * HIDE ALL FIELDS
   * ----------------------------------- */
  function hideAll() {
    Object.values(FIELD_IDS).forEach((selector) => {
      const $row = $(selector).closest("tr.pods-field__container");
      if ($row.length) {
        $row.hide().css("display", "none").attr("data-conditional-hidden", "1");
      }
    });
  }


  /** -----------------------------------
   * APPLY VISIBILITY
   * ----------------------------------- */
  function applyVisibility() {
    hideAll();

    const fmt = getSelectedFormat();

    if (!fmt) return;

    const allowed = visibilityMap[fmt] || [];

    allowed.forEach((slug) => {
      const selector = FIELD_IDS[slug];
      const $el = $(selector);

      if (!$el.length) {
        return;
      }

      const $row = $el.closest("tr.pods-field__container");
      if (!$row.length) {
        return;
      }

      // Ensure row becomes visible (table rows need this)
      $row.show().css("display", "table-row").removeAttr("data-conditional-hidden");

      // Safety rerender fix (Gutenberg sometimes hides again)
      setTimeout(() => {
        if ($row.is(":hidden")) {
          $row.css("display", "table-row");
        }
      }, 40);
    });
  }


  /** -----------------------------------
   * WAIT UNTIL PODS FIELDS ARE IN DOM
   * ----------------------------------- */
  function waitForPodsFields(callback) {
    const interval = setInterval(() => {
      if ($(FIELD_IDS["article-link"]).length) {
        clearInterval(interval);
        callback();
      }
    }, 80);
  }


  /** -----------------------------------
   * INITIALIZE LOGIC
   * ----------------------------------- */
  waitForPodsFields(function () {
    // Initial run
    applyVisibility();

    // Watch tag checklist for added/removed taxonomy terms
    const tagList = document.querySelector("#tagsdiv-shorts_format ul.tagchecklist");
    if (tagList) {
      new MutationObserver(applyVisibility)
        .observe(tagList, { childList: true });
    }

    // Listen to "add tag" input field (custom typed tags)
    $(document).on("blur keyup change", "#tagsdiv-shorts_format input.newtag", applyVisibility);

    // Checkbox-based taxonomy UI (Classic Editor)
    $(document).on("change", "#tagsdiv-shorts_format input[type='checkbox']", applyVisibility);
  });

});
