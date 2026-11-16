function toGraphQLInput(obj) {
  return `{ ${Object.entries(obj)
    .map(([key, value]) => {
      const isEnum = typeof value === "string" && /^[A-Z_]+$/.test(value);

      if (isEnum) {
        return `${key}: ${value}`;
      }

      if (typeof value === "string") {
        return `${key}: "${value}"`;
      }

      return `${key}: ${value}`;
    })
    .join(", ")} }`;
}

let controller;
const domain = window.location.origin;
let selectedTopics = [];
let selectedLocations = [];
const searchInput = document.getElementById("searchInput");
const defaultSuggestions = document.getElementById("default");
const noResults = document.getElementById("no-results");
const formatResults = document.getElementById("formats-results");
const results = document.getElementById("results");
const isMobile =
  /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) &&
  document.querySelector("body").clientWidth < 481;
let isResultsLoading = false;

let totalCount;
let cursor = "";

const formatOptions = formatOptionsLocal;

const defaultFormatsList = [
  "POST",
  "CUSTOM_STORY",
  "VIDEOS",
  "PODCASTS",
  "SPECIALS",
];

// Mapping from shorts sub-type format keys to shorts_format taxonomy slugs
const shortsFormatMap = {
  SHORT_AUDIO: "audio",
  SHORT_VIDEO: "video",
  SHORT_CONTENT: "content",
};
const shortsFormatKeys = Object.keys(shortsFormatMap);

let selectedFormats = [];
let featuredOnly = false;

const articlesData = articlesDataLocal;

const topicsData = topicsDataLocal;

const locationsData = locationsDataLocal;

const clearAllTagsText = "Clear all tags";
const clearSearchInputText = "Clear search input";

// Title to fall back to once every search criteria has been cleared
const initialPageTitle = document.title;

function decodeHtml(encoded) {
  const textarea = document.createElement("textarea");
  textarea.innerHTML = encoded;
  return textarea.value;
}

function wpContentToHtml(raw) {
  // 1. Decode HTML entities (&lt;, &gt;, &quot;, etc.)
  let html = decodeHtml(raw);

  // 2. Convert [caption ...]<img .../> Some text[/caption] into <figure>
  html = html.replace(
    /\[caption[^\]]*\]\s*(<img[^>]*>)\s*([^[]*)\[\/caption\]/gi,
    (_, imgTag, captionText) => {
      return `<figure>${imgTag}<figcaption>${captionText.trim()}</figcaption></figure>`;
    },
  );

  // 3. Split into blocks on blank lines, then wrap plain-text blocks in <p>
  //    (skip blocks that already start with a block-level tag)
  const blockTagRegex = /^\s*<(p|ol|ul|li|figure|div|h[1-6]|blockquote|table|img)\b/i;

  const blocks = html
    .split(/\n\s*\n/) // split on blank lines
    .map((block) => block.trim())
    .filter(Boolean);

  const wrapped = blocks.map((block) => {
    if (blockTagRegex.test(block)) {
      return block; // already block-level HTML, leave as-is
    }
    return `<p>${block}</p>`;
  });

  return wrapped.join("\n");
}

function createDefaultList(type, data) {
  const output = document.getElementById(`${type}-suggestions`);

  Object.values(data).forEach((item) => {
    const itemSpan = document.createElement("span");
    itemSpan.textContent = item[0];

    itemSpan.addEventListener("click", () => {
      if (type === "articles") {
        searchInput.value = item[0];
        fetchArticles(true);
      } else {
        if (type === "topics") {
          if (!selectedTopics.includes(item[1])) {
            createTaxTag("topic", item[0], item[1]);
            fetchArticles(true);
          }
        }

        if (type === "locations") {
          if (!selectedLocations.includes(item[1])) {
            createTaxTag("location", item[0], item[1]);
            fetchArticles(true);
          }
        }

        if (type === "formats") {
          if (!selectedFormats.includes(item[1])) {
            createFormatTag(item[0], item[1], true);
            fetchArticles(true);
          }
        }
      }
    });

    output.appendChild(itemSpan);
  });

  return output;
}

createDefaultList("articles", articlesData);
createDefaultList("topics", topicsData);
createDefaultList("locations", locationsData);
// Format suggestions are generated dynamically in the focus handler to support disabled states

function isValueString(value) {
  return typeof value === "string" || value instanceof String;
}

const queriedSearch = decodeURI(window.location.search);

const queriedParams = queriedSearch.split("&").reduce((acc, param) => {
  const [key, value] = param.split("=");
  if (key === "?s" && value.length > 0) {
    acc["search"] = value;
  } else {
    if (key.length > 0 && value.length > 0) {
      acc[key] = value && value.includes("+") ? value.split("+") : value;
    }
  }

  return acc;
}, {});

if (Object.keys(queriedParams).length > 0) {
  if (queriedParams.search) {
    document.getElementById("searchInput").value = queriedParams.search;
    displayClearButton("#searchInput", ".search-actions", clearSearchInputText);
  }

  if (queriedParams.locations) {
    selectedLocations = isValueString(queriedParams.locations)
      ? [queriedParams.locations]
      : queriedParams.locations;

    if (isValueString(queriedParams.locations)) {
      createTaxTag("location", queriedParams.locations, queriedParams.locations);
    } else {
      queriedParams.locations.forEach((location) => {
        createTaxTag("location", location, location);
      });
    }
  }
  if (queriedParams.topics) {
    selectedTopics = isValueString(queriedParams.topics)
      ? [queriedParams.topics]
      : queriedParams.topics;
    if (isValueString(queriedParams.topics)) {
      createTaxTag("topic", queriedParams.topics, queriedParams.topics);
    } else {
      queriedParams.topics.forEach((topic) => {
        createTaxTag("topic", topic, topic);
      });
    }
  }

  if (queriedParams.formats) {
    if (isValueString(queriedParams.formats)) {
      const upFormatStr = queriedParams.formats.toUpperCase();
      selectedFormats = [upFormatStr];
      createFormatTag(
        formatOptions[queriedParams.formats][0],
        formatOptions[queriedParams.formats][1],
        false,
      );
    } else {
      queriedParams.formats.forEach((format) => {
        const upFormat = format.toUpperCase();

        if (!selectedFormats.includes(upFormat)) {
          selectedFormats.push(formatOptions[format][1]);
          createFormatTag(formatOptions[format][0], formatOptions[format][1], false);
        }
      });
    }
  }

  if (queriedParams.featured === "true") {
    featuredOnly = true;
    document.getElementById("featured").checked = true;
  }

  fetchArticles(true);
}

function formatDate(date) {
  const dateString = new Date(date).toDateString();
  dateArray = dateString.split(" ");
  return `${dateArray[1]} ${dateArray[2]}, ${dateArray[3]}`;
}

/**
 * Shows animated preloader.
 * @param {boolean} show.
 */
function preloader(show) {
  return show
    ? `
        <div class="preloader-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><g stroke="currentColor"><circle cx="12" cy="12" r="9.5" fill="none" stroke-linecap="round" stroke-width="3"><animate attributeName="stroke-dasharray" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1" repeatCount="indefinite" values="0 150;42 150;42 150;42 150"/><animate attributeName="stroke-dashoffset" calcMode="spline" dur="1.5s" keySplines="0.42,0,0.58,1;0.42,0,0.58,1;0.42,0,0.58,1" keyTimes="0;0.475;0.95;1" repeatCount="indefinite" values="0;-16;-59;-59"/></circle><animateTransform attributeName="transform" dur="2s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/></g></svg>
        </div>`
    : null;
}

/**
 * Debounce function.
 * @param {function} func - function to pass which should be delayed.
 * @param {number} delay - delay in milliseconds.
 */
function debounce(func, delay) {
  let timer;
  return function () {
    const context = this;
    const args = arguments;
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(context, args);
    }, delay);
  };
}

/**
 * Checks whether any search criteria is currently active.
 * @returns {boolean} True when a search term, topic, location or format is set.
 */
function hasActiveFilters() {
  return (
    searchInput.value.length > 0 ||
    selectedTopics.length > 0 ||
    selectedLocations.length > 0 ||
    selectedFormats.length > 0
  );
}

/**
 * Builds the query string representing the current search criteria.
 * Empty criteria are left out so the URL always reflects the actual state.
 */
function buildSearchString() {
  return `?s=${searchInput.value}${
    selectedLocations.length ? `&locations=${selectedLocations.join("+")}` : ""
  }${selectedTopics.length ? `&topics=${selectedTopics.join("+")}` : ""}${
    selectedFormats.length ? `&formats=${selectedFormats.join("+").toLowerCase()}` : ""
  }${featuredOnly ? "&featured=true" : ""}`;
}

/**
 * Pushes the current search criteria to the URL and updates the document title.
 */
function updateSearchUrl() {
  const searchValue = searchInput.value;

  let nextTitle = initialPageTitle;

  if (searchValue.length > 0) {
    nextTitle = `Searched for ${searchValue}`;
  } else if (selectedFormats.length > 0) {
    nextTitle = `Showing all ${selectedFormats
      .join(", ")
      .toLowerCase()
      .replace(/_/g, " ")} ${storiesLocal}`;
  }

  window.history.pushState(
    { additionalInformation: "New search performed" },
    nextTitle,
    `${domain}${buildSearchString()}`,
  );

  document.title = nextTitle;
}

/**
 * Restores the default suggestions component and discards any pending results.
 */
function showSuggestions() {
  if (controller) {
    controller.abort();
  }

  isResultsLoading = false;
  cursor = "";
  searchInput.dataset.previousValue = "";

  const resultsFooter = document.querySelector(".results-footer");
  resultsFooter.innerHTML = "";
  resultsFooter.classList.add("hide");

  results.innerHTML = "";
  results.classList.add("hide");
  noResults.classList.add("hide");
  defaultSuggestions.classList.remove("hide");
}

/**
 * Falls back to the suggestions component when no criteria is left.
 * @returns {boolean} True when the search was reset, false when filters remain.
 */
function filtersCheck() {
  if (hasActiveFilters()) {
    return false;
  }

  showSuggestions();
  updateSearchUrl();

  return true;
}

/**
 * Clears the selected tags based on the type.
 * @param {string} type - The type of tags to clear ("topics", "locations" or "formats").
 */
function clearTags(type) {
  if (type === "topics") {
    selectedTopics = [];
    const topicsResults = document.querySelector("#topics-results");

    document.querySelectorAll(".tax-item-wrapper.topics button.tax-tag-topic").forEach((tag) => {
      tag.remove();
    });
    topicsResults.innerHTML = "";
    topicsResults.classList.add("hide");
    document.querySelector(".tax-search-actions.topic").innerHTML = "";
  }

  if (type === "locations") {
    selectedLocations = [];
    const locationsResults = document.querySelector("#locations-results");

    document
      .querySelectorAll(".tax-item-wrapper.locations button.tax-tag-location")
      .forEach((tag) => {
        tag.remove();
      });

    locationsResults.innerHTML = "";
    locationsResults.classList.add("hide");
    document.querySelector(".tax-search-actions.location").innerHTML = "";
  }

  if (type === "formats") {
    selectedFormats = [];
    const formatsResults = document.querySelector("#formats-results");

    document.querySelectorAll(".tax-item-wrapper.format > button").forEach((tag) => {
      tag.remove();
    });

    formatsResults.innerHTML = "";
    formatsResults.classList.add("hide");
    document.querySelector(".tax-search-actions.format").innerHTML = "";
  }

  filtersCheck();
}

function clearSearch() {
  searchInput.value = "";

  if (document.querySelector(".search-input-wrapper button.clear-button")) {
    document.querySelector(".search-input-wrapper button.clear-button").remove();
  }

  filtersCheck();
}

/**
 * Creates clear button for input fields
 * @param {string} insertAfterElement - insert after html selector tag.
 * @param {string} insertLocation - optional insert location as html selector tag.
 */
function displayClearButton(inputTag, insertLocation = "", toolTipText = "") {
  const input = document.querySelector(inputTag);
  const searchInput = document.getElementById("searchInput");

  function renderClearButton() {
    const clearButton = document.createElement("button");
    clearButton.classList.add("clear-button");

    const toolTipWrapper = document.createElement("div");
    toolTipWrapper.classList.add("extra-tooltip");

    const toolTip = document.createElement("span");
    toolTip.classList.add("tooltiptext");

    toolTip.textContent = toolTipText;

    toolTipWrapper.appendChild(toolTip);
    toolTipWrapper.appendChild(clearButton);

    clearButton.addEventListener("click", () => {
      clearButton.remove();

      if (inputTag === "#searchInput") {
        clearSearch();
      }
      if (inputTag === "#searchTopic") {
        clearTags("topics");
      }
      if (inputTag === "#searchLocation") {
        clearTags("locations");
      }
      if (inputTag === "#searchFormat") {
        clearTags("formats");
      }

      // clearSearch/clearTags already reset to the suggestions component and
      // updated the URL when nothing is left, so only refetch when it is not empty.
      if (hasActiveFilters()) {
        fetchArticles(true);
      }
    });

    return toolTipText.length > 0 ? toolTipWrapper : clearButton;
  }

  if (
    input.value.length > 0 &&
    !input.nextSibling.nodeName.includes("BUTTON") &&
    !insertLocation.length
  ) {
    input.insertAdjacentElement("afterend", renderClearButton());
  }

  if (insertLocation.length && !document.querySelector(`${insertLocation} .clear-button`)) {
    document.querySelector(insertLocation).appendChild(renderClearButton());
  }
}

/**
 * @param {'topic' | 'location'} type - Either topic or location.
 * @param {string} name - The name of the tag.
 * @param {string} slug - The slug of the tag.
 * @param {boolean} showResults - Whether to show the results or not.
 */
function createTaxTag(type, name, slug, showResults = false) {
  const tag = document.createElement("button");
  tag.classList.add(`tax-tag-${type}`);

  if (type === "topic" && !selectedTopics.includes(slug)) {
    selectedTopics.push(slug);

    if (
      selectedTopics.length > 0 &&
      !document.querySelector(".tax-search-actions.topic .clear-button")
    ) {
      displayClearButton("#searchTopic", ".tax-search-actions.topic", clearAllTagsText);
    }
  }

  if (type === "location" && !selectedLocations.includes(slug)) {
    selectedLocations.push(slug);

    if (
      selectedLocations.length > 0 &&
      !document.querySelector(".tax-search-actions.location .clear-button")
    ) {
      displayClearButton("#searchLocation", ".tax-search-actions.location", clearAllTagsText);
    }
  }

  tag.textContent = name.replace(/-/g, " ");

  const searchSelectorId = type === "topic" ? "searchTopic" : "searchLocation";

  document
    .querySelector(`.tax-item-wrapper.${type}s`)
    .insertBefore(tag, document.getElementById(searchSelectorId));

  if (showResults) {
    fetchArticles(true);
  }

  tag.addEventListener("click", () => {
    if (type === "topic") {
      selectedTopics.splice(selectedTopics.indexOf(slug), 1);

      if (
        selectedTopics.length === 0 &&
        document.querySelector(`.tax-search-actions.${type} .clear-button`)
      ) {
        document.querySelector(`.tax-search-actions.${type} .clear-button`).remove();
        document.querySelector(`.tax-search-actions.${type}`).innerHTML = "";
      }
    } else {
      selectedLocations.splice(selectedLocations.indexOf(slug), 1);

      if (
        selectedLocations.length === 0 &&
        document.querySelector(`.tax-search-actions.${type} .clear-button`)
      ) {
        document.querySelector(`.tax-search-actions.${type} .clear-button`).remove();
        document.querySelector(`.tax-search-actions.${type}`).innerHTML = "";
      }
    }
    document.querySelector(`.tax-item-wrapper.${type}s`).removeChild(tag);

    if (!filtersCheck()) {
      fetchArticles(true);
    }
  });

  document.getElementById(searchSelectorId).value = "";
}

if (selectedTopics.length > 0) {
  displayClearButton("#searchTopic", ".tax-search-actions.topic", clearAllTagsText);
}
/**
 * * @param {string} name - The name of the format.
 * @param {string} slug - The slug of the format.
 * @param {boolean} showResults - Whether to show the results or not.
 */

function createFormatTag(name, slug, showResults = false) {
  const formatResults = document.getElementById("formats-results");
  const formatTag = document.createElement("button");
  formatTag.textContent = name;

  if (!selectedFormats.includes(slug)) {
    selectedFormats.push(slug);

    if (
      selectedFormats.length > 0 &&
      !document.querySelector(".tax-search-actions.format .clear-button")
    ) {
      displayClearButton("#searchFormat", ".tax-search-actions.format", clearAllTagsText);
    }
  }

  if (document.querySelector(".tax-item-wrapper.format button")) {
    // document.querySelector(".tax-item-wrapper.format button").remove();
  }

  document
    .querySelector(".tax-item-wrapper.format")
    .insertBefore(formatTag, document.getElementById("searchFormat"));

  // if (!selectedFormats.includes(slug)) {
  //   selectedFormats.push(slug);
  // }

  if (document.querySelector(".tax-search-actions.format .clear-button")) {
    document.querySelector(".tax-search-actions.format .clear-button").remove();
  }

  formatTag.addEventListener("click", () => {
    selectedFormats = selectedFormats.filter((format) => format !== slug);

    if (document.querySelector(".tax-item-wrapper.format > button")) {
      document.querySelector(".tax-item-wrapper.format").removeChild(formatTag);
    }

    if (
      selectedFormats.length === 0 &&
      document.querySelector(".tax-search-actions.format .clear-button")
    ) {
      document.querySelector(".tax-search-actions.format").innerHTML = "";
    }

    if (!filtersCheck()) {
      fetchArticles(true);
    }

    // Refresh format suggestions to update disabled states
    const searchFormat = document.getElementById("searchFormat");
    if (searchFormat.value.length > 0) {
      formatFilter();
    }
  });

  if (showResults) {
    formatResults.classList.add("hide");
  }
  document.getElementById("searchFormat").value = "";

  // Refresh format suggestions to update disabled states after adding a tag
  const formatInput = document.getElementById("searchFormat");
  if (formatInput.value.length > 0) {
    formatFilter();
  }
}

function highlightResults(search, item) {
  const regex = new RegExp(search, "gi");

  let text = item.innerHTML;
  text = text.replace(/(<mark class="highlight">|<\/mark>)/gim, "");

  const newText = text.replace(regex, '<mark class="highlight">$&</mark>');
  item.innerHTML = newText;
}

// Various event listenrers to fetch entities and display clear input button
document.getElementById("searchInput").addEventListener(
  "input",
  debounce(function () {
    if (searchInput.value.length > 0) {
      fetchArticles();
      displayClearButton("#searchInput", ".search-actions", clearSearchInputText);
      document.getElementById("articles-suggestions").classList.add("hide");
      return;
    }

    document.getElementById("articles-suggestions").classList.remove("hide");

    if (document.querySelector(".search-input-wrapper button.clear-button")) {
      document.querySelector(".search-input-wrapper button.clear-button").remove();
    }

    // Keep showing results while topics, locations or formats are still selected
    if (!filtersCheck()) {
      fetchArticles(true);
    }
  }, 600),
);

document.getElementById("searchTopic").addEventListener("input", debounce(searchTopic, 500));

document.getElementById("searchLocation").addEventListener("input", debounce(searchLocation, 500));

document.getElementById("searchFormat").addEventListener("input", debounce(formatFilter, 500));

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    const searchResultIds = [
      "topics-results",
      "locations-results",
      "formats-results",
      "topics-suggestions",
      "locations-suggestions",
      "articles-suggestions",
      "formats-suggestions",
    ];

    searchResultIds.forEach((id) => {
      document.getElementById(id).classList.add("hide");
    });
  }
});

// hide search results when clicked outside
document.addEventListener("click", (e) => {
  const searchResultIds = [
    "topics-results",
    "locations-results",
    "formats-results",
    "topics-suggestions",
    "locations-suggestions",
    "articles-suggestions",
    "formats-suggestions",
  ];

  if (!searchResultIds.includes(e.target.id)) {
    searchResultIds.forEach((id) => {
      if (
        e.target.id === "searchTopic" ||
        e.target.id === "searchLocation" ||
        e.target.id === "searchInput" ||
        e.target.id === "searchFormat"
      ) {
        return;
      }

      document.getElementById(id).classList.add("hide");
    });
  }
});

document.getElementById("searchInput").addEventListener("focus", () => {
  document.getElementById("articles-suggestions").classList.remove("hide");

  document.querySelectorAll(".dropdown").forEach((dropdown) => {
    if (dropdown.id !== "articles-suggestions") {
      dropdown.classList.add("hide");
    }
  });
});

document.getElementById("searchTopic").addEventListener("focus", () => {
  const suggestionsTopic = document.getElementById("topics-suggestions");
  suggestionsTopic.classList.remove("hide");

  document.querySelectorAll(".dropdown").forEach((dropdown) => {
    if (dropdown.id !== "topics-suggestions") {
      dropdown.classList.add("hide");
    }
  });

  suggestionsTopic.style.top = `${
    document.querySelector(".tax-item-wrapper.topics").offsetHeight
  }px`;
});

document.getElementById("searchLocation").addEventListener("focus", () => {
  const suggestionsLocation = document.getElementById("locations-suggestions");
  suggestionsLocation.classList.remove("hide");

  document.querySelectorAll(".dropdown").forEach((dropdown) => {
    if (dropdown.id !== "locations-suggestions") {
      dropdown.classList.add("hide");
    }
  });

  suggestionsLocation.style.top = `${
    document.querySelector(".tax-item-wrapper.locations").offsetHeight
  }px`;
});

document.getElementById("searchFormat").addEventListener("focus", () => {
  const suggestionsFormat = document.getElementById("formats-suggestions");

  // Regenerate suggestions with current disabled states
  const hasShorts = selectedFormats.some((f) => shortsFormatKeys.includes(f));
  const hasNonShorts = selectedFormats.some((f) => !shortsFormatKeys.includes(f));

  // Clear and rebuild suggestions
  suggestionsFormat.innerHTML = "";

  Object.values(formatOptions).forEach((format) => {
    const formatSpan = document.createElement("span");
    formatSpan.textContent = format[0];

    const formatSlug = format[1];
    const isShorts = shortsFormatKeys.includes(formatSlug);
    const isDisabled = (hasShorts && !isShorts) || (hasNonShorts && isShorts);

    if (isDisabled) {
      formatSpan.classList.add("disabled");
      formatSpan.style.opacity = "0.5";
      formatSpan.style.cursor = "not-allowed";
    } else {
      formatSpan.addEventListener("click", () => {
        if (!selectedFormats.includes(formatSlug)) {
          createFormatTag(format[0], formatSlug, true);
          fetchArticles(true);
        }
      });
    }

    suggestionsFormat.appendChild(formatSpan);
  });

  suggestionsFormat.classList.remove("hide");

  document.querySelectorAll(".dropdown").forEach((dropdown) => {
    if (dropdown.id !== "formats-suggestions") {
      dropdown.classList.add("hide");
    }
  });

  suggestionsFormat.style.top = `${
    document.querySelector(".tax-item-wrapper.format").offsetHeight
  }px`;
});

document.getElementById("featured").addEventListener("change", () => {
  featuredOnly = !featuredOnly;
  fetchArticles(true);
});

/**
 * Convert video URL to embeddable format (YouTube, Vimeo, etc.)
 * @param {string} url - The video URL
 * @returns {object} - Object with type ('embed' or 'video') and url
 */
function getEmbedUrl(url) {
  if (!url) return { type: "video", url: "" };

  // YouTube - handle watch, youtu.be, and shorts URLs
  let youtubeMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([^&\?\/]+)/);
  if (youtubeMatch) {
    return {
      type: "embed",
      url: `https://www.youtube.com/embed/${youtubeMatch[1]}`,
    };
  }

  // Vimeo
  let vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
  if (vimeoMatch) {
    return {
      type: "embed",
      url: `https://player.vimeo.com/video/${vimeoMatch[1]}`,
    };
  }

  // Check if it's a direct video file
  const videoExtensions = ["mp4", "webm", "ogv", "ogg", "m4v", "mov"];
  const ext = url.split("?")[0].split(".").pop().toLowerCase();
  if (videoExtensions.includes(ext)) {
    return { type: "video", url: url };
  }

  // For other URLs, treat as direct video
  return { type: "video", url: url };
}

/**
 * Render a shorts video card for search results.
 * @param {object} node - The ShortArticle node from GraphQL.
 */
function renderShortsVideo(node) {
  const listItem = document.createElement("div");
  listItem.classList.add("article--container", "shorts-trigger", "shorts-video-card");

  const articleLink = node.articleLink || node.link;
  const shareUrl = `${domain}/shorts/#/${node.databaseId || ""}`;
  const embedUrl = node.videoLink || "";

  listItem.dataset.articlelink = articleLink;
  listItem.dataset.shareurl = shareUrl;
  listItem.dataset.mediaFormat = "video";
  listItem.dataset.embedUrl = embedUrl;

  // Featured image container
  const imageContainer = document.createElement("div");
  imageContainer.classList.add("featured-image", "shorts-video-featured-image");

  const postFormatIcon = document.createElement("div");
  postFormatIcon.classList.add("post-icon");
  postFormatIcon.innerHTML = `<span class='icon icon-play'></span>`;
  imageContainer.appendChild(postFormatIcon);

  if (node.featuredImage?.node?.srcSet || node.featuredImage?.node?.sourceUrl) {
    const postImage = document.createElement("img");
    if (node.featuredImage.node.srcSet) {
      postImage.srcset = node.featuredImage.node.srcSet;
    } else {
      postImage.src = node.featuredImage.node.sourceUrl;
    }
    imageContainer.appendChild(postImage);
  }

  const overlay = document.createElement("div");
  overlay.classList.add("img-overlay");
  imageContainer.appendChild(overlay);

  const videoOverlay = document.createElement("div");
  videoOverlay.classList.add("shorts-video-overlay");

  const titleContainer = document.createElement("div");
  titleContainer.classList.add("title", "headline");
  const postTitle = document.createElement("h3");
  postTitle.textContent = node.title;
  titleContainer.appendChild(postTitle);
  videoOverlay.appendChild(titleContainer);

  const postMeta = document.createElement("div");
  postMeta.classList.add("post-meta");
  const byline = document.createElement("span");
  byline.classList.add("byline");
  const bylinesArray = node.byline?.nodes?.map((b) => b.name) || [];
  byline.textContent = bylinesArray.join(", ");
  const postDate = document.createElement("span");
  postDate.classList.add("date");
  postDate.textContent = formatDate(node.date);
  postMeta.appendChild(byline);
  postMeta.appendChild(postDate);
  videoOverlay.appendChild(postMeta);

  imageContainer.appendChild(videoOverlay);
  listItem.appendChild(imageContainer);

  // Hidden content - will be populated by dialog if needed
  const postExcerpt = document.createElement("div");
  postExcerpt.classList.add("post-excerpt", "hidden");
  listItem.appendChild(postExcerpt);

  // Hidden media embed
  const mediaEmbed = document.createElement("div");
  mediaEmbed.classList.add("shorts-media-embed", "hidden");
  if (embedUrl) {
    const embedData = getEmbedUrl(embedUrl);

    if (embedData.type === "embed") {
      // Use iframe for YouTube, Vimeo, etc.
      const iframe = document.createElement("iframe");
      iframe.src = embedData.url;
      iframe.setAttribute("allowfullscreen", "");
      iframe.setAttribute("allow", "autoplay; fullscreen");
      iframe.setAttribute("frameborder", "0");
      iframe.style.width = "100%";
      iframe.style.aspectRatio = "16/9";
      mediaEmbed.appendChild(iframe);
    } else {
      // Use HTML5 video element for direct video files
      const video = document.createElement("video");
      video.src = embedData.url;
      video.controls = true;
      video.style.width = "100%";
      video.style.aspectRatio = "16/9";
      video.setAttribute("playsinline", "");
      mediaEmbed.appendChild(video);
    }
  }
  listItem.appendChild(mediaEmbed);

  document.getElementById("post-results").appendChild(listItem);
}

/**
 * Render a shorts audio card for search results.
 * @param {object} node - The ShortArticle node from GraphQL.
 */
function renderShortsAudio(node) {
  const listItem = document.createElement("div");
  listItem.classList.add("article--container", "shorts-audio-card", "rounded");

  const audioTop = document.createElement("div");
  audioTop.classList.add("shorts-audio-top");

  const audioInfo = document.createElement("div");
  audioInfo.classList.add("shorts-audio-info");

  const titleContainer = document.createElement("div");
  titleContainer.classList.add("title", "headline");
  const postTitle = document.createElement("h3");
  postTitle.textContent = node.title;
  titleContainer.appendChild(postTitle);
  audioInfo.appendChild(titleContainer);

  const postMeta = document.createElement("div");
  postMeta.classList.add("post-meta");
  const byline = document.createElement("span");
  byline.classList.add("byline");
  const bylinesArray = node.byline?.nodes?.map((b) => b.name) || [];
  byline.textContent = bylinesArray.join(", ");
  const postDate = document.createElement("span");
  postDate.classList.add("date");
  postDate.textContent = formatDate(node.date);
  postMeta.appendChild(byline);
  postMeta.appendChild(postDate);
  audioInfo.appendChild(postMeta);

  audioTop.appendChild(audioInfo);

  if (node.featuredImage?.node?.sourceUrl) {
    const audioThumbnail = document.createElement("div");
    audioThumbnail.classList.add("shorts-audio-thumbnail");

    const postFormatIcon = document.createElement("div");
    postFormatIcon.classList.add("post-icon");
    postFormatIcon.innerHTML = `<span class='icon icon-podcast'></span>`;
    audioThumbnail.appendChild(postFormatIcon);

    const postImage = document.createElement("img");
    postImage.src = node.featuredImage.node.sourceUrl;
    audioThumbnail.appendChild(postImage);

    audioTop.appendChild(audioThumbnail);
  }

  listItem.appendChild(audioTop);

  // Audio sticky shell
  const stickyShell = document.createElement("div");
  stickyShell.classList.add("shorts-audio-sticky-shell");

  if (node.featuredImage?.node?.sourceUrl) {
    const stickyThumbnail = document.createElement("div");
    stickyThumbnail.classList.add("shorts-audio-thumbnail", "sticky-thumbnail");
    const stickyImage = document.createElement("img");
    stickyImage.src = node.featuredImage.node.sourceUrl;
    stickyThumbnail.appendChild(stickyImage);
    stickyShell.appendChild(stickyThumbnail);
  }

  const stickyTitle = document.createElement("div");
  stickyTitle.classList.add("shorts-audio-sticky-title", "headline");
  const stickyH3 = document.createElement("h3");
  stickyH3.textContent = node.title;
  stickyTitle.appendChild(stickyH3);
  stickyShell.appendChild(stickyTitle);

  const closeBtn = document.createElement("button");
  closeBtn.type = "button";
  closeBtn.classList.add("shorts-audio-sticky-close");
  closeBtn.setAttribute("aria-label", "Close player");
  closeBtn.innerHTML = `<span class="icon icon-cancel"></span>`;
  stickyShell.appendChild(closeBtn);

  const playerSlot = document.createElement("div");
  playerSlot.classList.add("shorts-audio-sticky-player-slot");
  stickyShell.appendChild(playerSlot);

  listItem.appendChild(stickyShell);

  // Audio embed
  const audioEmbed = document.createElement("div");
  audioEmbed.classList.add("shorts-audio-embed", "bg-theme-accent");
  const playerHost = document.createElement("div");
  playerHost.classList.add("shorts-audio-player-host");

  const embedUrl = node.audioLink || "";
  if (embedUrl) {
    const audio = document.createElement("audio");
    audio.controls = true;
    audio.preload = "metadata";
    audio.src = embedUrl;
    playerHost.appendChild(audio);
  }

  audioEmbed.appendChild(playerHost);
  listItem.appendChild(audioEmbed);

  // Hidden content - will be populated by dialog if needed
  const postExcerpt = document.createElement("div");
  postExcerpt.classList.add("post-excerpt", "hidden");
  listItem.appendChild(postExcerpt);

  document.getElementById("post-results").appendChild(listItem);
}

/**
 * Fetch articles based on search criteria.
 * @param {boolean} fromStart - Whether to fetch articles from the start or not.
 */

async function fetchArticles(fromStart = false) {
  if (controller) {
    controller.abort();
  }

  controller = new AbortController();
  const signal = controller.signal;

  isResultsLoading = true;
  const searchInput = document.getElementById("searchInput");
  const resultsFooter = document.querySelector(".results-footer");
  const searchValue = searchInput.value;

  const previousSearchValue = searchInput.dataset.previousValue || "";
  searchInput.dataset.previousValue = searchValue;

  const loadModeButton = document.querySelector(".load-more");

  const clearResults = cursor.length === 0 || previousSearchValue !== searchValue || fromStart;

  if (clearResults) {
    results.innerHTML = "";
    cursor = "";
  }

  defaultSuggestions.classList.add("hide");
  resultsFooter.classList.remove("hide");
  resultsFooter.innerHTML = preloader(true);

  if (loadModeButton) {
    resultsFooter.innerHTML = preloader(true);
  }

  // if (!searchValue.length) {
  //   results.innerHTML = "";
  //   isResultsLoading = false;
  //   // return;
  // }

  updateSearchUrl();

  const topicGql = `{taxonomy:TOPIC,terms:${JSON.stringify(
    selectedTopics,
  )},field:SLUG,operator:IN,includeChildren:true}`;
  const locationGql = `{taxonomy:LOCATION,terms:${JSON.stringify(
    selectedLocations,
  )},field:SLUG,operator:IN,includeChildren:true}`;
  const seriesQql = `{taxonomy:SERIAL,operator:EXISTS,field: SLUG}`;

  const taxArray = [];

  if (selectedTopics.length) {
    taxArray.push(topicGql);
  }
  if (selectedLocations.length) {
    taxArray.push(locationGql);
  }
  if (selectedFormats.includes("SPECIAL")) {
    taxArray.push(seriesQql);
  }

  // Determine which shorts sub-types are selected
  const selectedShortsFormats = selectedFormats.filter((f) => shortsFormatKeys.includes(f));
  const selectedNonShortsFormats = selectedFormats.filter((f) => !shortsFormatKeys.includes(f));

  // Build contentTypes: map shorts sub-types to SHORT_ARTICLE
  let contentTypes;
  if (!selectedFormats.length) {
    contentTypes = defaultFormatsList;
  } else {
    contentTypes = [...selectedNonShortsFormats];
    if (selectedShortsFormats.length > 0) {
      contentTypes.push("SHORT_ARTICLE");
    }
  }

  // Safeguard: ensure contentTypes is never empty
  if (!contentTypes.length) {
    contentTypes = defaultFormatsList;
  }

  // Add shorts_format taxonomy filter when specific sub-types are selected
  // IMPORTANT LIMITATION: Taxonomy filters apply to ALL content types in the query.
  // When mixing shorts with other post types (POST, CUSTOM_STORY, etc.), we CANNOT use
  // the shorts_format taxonomy filter because it would exclude those other post types.
  //
  // UI Solution: The format dropdown disables incompatible options to prevent mixing:
  // - Selecting any shorts format disables other post types (Articles, Videos, etc.)
  // - Selecting any other post type disables shorts formats
  //
  // This prevents pagination issues from client-side filtering while making the
  // limitation clear to users through the UI.

  if (selectedShortsFormats.length > 0 && selectedNonShortsFormats.length === 0) {
    const formatsToInclude = selectedShortsFormats.map((format) => shortsFormatMap[format]);
    const shortsFormatGql = `{taxonomy:SHORTSFORMAT,terms:${JSON.stringify(formatsToInclude)},field:SLUG,operator:IN}`;
    taxArray.push(shortsFormatGql);
  }
  // Note: Mixing shorts with other post types is prevented by the UI
  // (format dropdown disables incompatible options)

  const keyWordQuery = `search:\"${searchValue}\",`;
  const taxQuery = `taxQuery:{taxArray:[${taxArray.join(",")}]},`;
  const metaArray = [];
  const featuredMetaQuery = {
    key: "featured_as",
    compare: "EQUAL_TO",
    value: "featured",
  };

  if (featuredOnly) {
    metaArray.push(featuredMetaQuery);
  }

  const paginate = `,after:\"${cursor}\"`;
  const node = `__typename,title,link,date,byline{nodes{name}},featuredImage{node{srcSet sourceUrl(size: MEDIUM)}}`;
  const shortNode = `__typename,databaseId,title,link,date,unencodedContent,byline{nodes{name}},featuredImage{node{srcSet sourceUrl(size: MEDIUM)}},shortsFormats{nodes{slug}},articleLink,videoLink,audioLink`;
  const pageInfo = `pageInfo{endCursor hasNextPage hasPreviousPage startCursor total}`;

  const query = `query{
    contentNodes(where: {
      status:PUBLISH,
      ${searchValue.length > 0 ? keyWordQuery : ""}
      ${taxArray.length > 0 ? taxQuery : ""}
      ${
        metaArray.length
          ? `metaQuery:{relation: AND,metaArray:[${metaArray.map(toGraphQLInput).join(", ")}]},`
          : ""
      }
      contentTypes: [${contentTypes.join(", ")}]
    }
      first: 24
      ${cursor.length ? paginate : ""}
    ) 
      {
        ${pageInfo}
        edges {
          node {
            ... on Post {
              ${node}
            }
            ... on Podcast {
              ${node}
            }
            ... on Video {
              ${node}
            }
            ... on CustomStory {
              ${node}
            }
            ... on ShortArticle {
              ${shortNode}
            }
            ... on SpecialsArticle {
              ${node}
            }
          }
        }
    }
  }`;

  // `${domain}/graphql?query=query{${
  //   selectedFormat === "special" ? "posts" : selectedFormat
  // }(first:24${cursor.length ? paginate : ""},where:{status:PUBLISH${
  //   searchValue.length ? keyWordQuery : ""
  // },${taxQuery.length > 0 ? taxQuery : ""}
  // }){edges{node{title,link,date,byline{nodes{name}},featuredImage{node{srcSet sizes(size: THUMBNAIL)}}}}pageInfo{endCursor hasNextPage hasPreviousPage startCursor total}}}`,

  let resp;

  try {
    resp = await fetch(`${domain}/graphql`, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      // Public, PUBLISH-only data. Sending no cookies keeps the request
      // unauthenticated, which is what WPGraphQL Smart Cache requires to
      // emit a Cache-Control header (and what CDNs require to cache at all).
      credentials: "omit",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        query: query,
      }),
      signal: signal,
    });
  } catch (error) {
    // If the fetch was aborted, silently return
    if (error.name === "AbortError") {
      return;
    }
 
    resultsFooter.innerHTML = preloader(false);
    defaultSuggestions.classList.add("hide");
    noResults.classList.remove("hide");

    return;
  }

  if (signal.aborted) {
    return; // Abort the fetch if the signal is true
  }

  let responseData;

  try {
    const responseText = await resp.text();

    if (!responseText) {
      resultsFooter.innerHTML = preloader(false);
      defaultSuggestions.classList.add("hide");
      noResults.classList.remove("hide");

      return;
    }
    try {
      responseData = JSON.parse(responseText);
    } catch (parseError) {
      resultsFooter.innerHTML = preloader(false);
      defaultSuggestions.classList.add("hide");
      noResults.classList.remove("hide");

      return;
    }
  } catch (e) {
    if (signal.aborted) {
      return;
    }

    resultsFooter.innerHTML = preloader(false);
    defaultSuggestions.classList.add("hide");
    noResults.classList.remove("hide");

    return;
  }

  if (signal.aborted) {
    return; // Don't render results for a request that was replaced or cleared
  }

  const { errors, data } = responseData;

  if (errors) {
    console.error("GraphQL errors:", errors);
    // Log each error in detail
    errors.forEach((error, index) => {
      console.error(`Error ${index + 1}:`, error.message);
      if (error.locations) {
        console.error(`  Locations:`, error.locations);
      }

      if (error.path) {
        console.error(`  Path:`, error.path);
      }
    });
    resultsFooter.innerHTML = preloader(false);
    defaultSuggestions.classList.add("hide");

    noResults.classList.remove("hide");
    return;
  }

  if (clearResults) {
    const resultsList = document.createElement("div");
    resultsList.id = "post-results";

    // Always use masonry layout for search results
    resultsList.classList.add("shorts-masonry");

    const listViewButton = document.createElement("button");
    listViewButton.id = "list-view";
    listViewButton.classList.add("demo-icon");
    listViewButton.textContent = "L";
    const gridViewButton = document.createElement("button");
    gridViewButton.id = "grid-view";
    gridViewButton.classList.add("active");
    gridViewButton.textContent = "G";

    const resultsTotal = document.createElement("div");
    const resultsHeader = document.createElement("div");
    const resultsViewToggles = document.createElement("div");
    const resultsHeaderLeft = document.createElement("div");
    const resultsRSS = document.createElement("a");
    resultsRSS.classList.add("theme--button", "primary", "simple");
    resultsRSS.textContent = "RSS";

    const hasTopics = selectedTopics.length > 0;
    const hasLocations = selectedLocations.length > 0;

    // Map shorts sub-types back to 'short-article' for RSS feed
    const selectedShortsFormatsRss = selectedFormats.filter((f) => shortsFormatKeys.includes(f));
    const rssFormats = selectedFormats.map((f) => {
      if (shortsFormatKeys.includes(f)) {
        return "short_article";
      }
      return f;
    });
    // Remove duplicates
    const uniqueRssFormats = [...new Set(rssFormats)];

    // Build shorts_format parameter for RSS feed
    const shortsSlugs = selectedShortsFormatsRss.map((f) => shortsFormatMap[f]);
    const hasShortsFormat = shortsSlugs.length > 0;

    resultsRSS.href = `${domain}/feed/?s=${searchValue}&type=${
      uniqueRssFormats.includes("SPECIALS")
        ? "post"
        : uniqueRssFormats.join(",").toLowerCase().replace(/_/g, "-")
    }${hasTopics ? `&topic=${selectedTopics.join(",")}` : ""}${
      hasLocations ? `&location=${selectedLocations.join(",")}` : ""
    }${hasShortsFormat ? `&shorts_format=${shortsSlugs.join(",")}` : ""}`;
    resultsRSS.target = "_blank";

    resultsHeader.id = "results-header";
    resultsViewToggles.id = "results-view-toggles";
    resultsTotal.id = "results-total";
    resultsRSS.id = "results-rss";
    resultsHeaderLeft.id = "results-header-left";

    resultsHeaderLeft.appendChild(resultsRSS);
    resultsHeaderLeft.appendChild(resultsTotal);
    resultsHeader.appendChild(resultsHeaderLeft);

    // Hide view toggles if only short video/audio formats are selected
    // (these have fixed layouts and shouldn't be toggled)
    const onlyShortsVideoAudio =
      selectedFormats.length > 0 &&
      selectedFormats.every((f) => f === "SHORT_VIDEO" || f === "SHORT_AUDIO") &&
      selectedFormats.some((f) => f === "SHORT_VIDEO" || f === "SHORT_AUDIO");

    if (!isMobile && !onlyShortsVideoAudio) {
      resultsHeader.appendChild(resultsViewToggles);
      resultsViewToggles.appendChild(listViewButton);
      resultsViewToggles.appendChild(gridViewButton);
    }

    results.appendChild(resultsHeader);
    results.appendChild(resultsList);
  }

  const foundResultsCount = data.contentNodes.edges.length;

  if (foundResultsCount === 0) {
    resultsFooter.innerHTML = preloader(false);
    defaultSuggestions.classList.add("hide");

    noResults.classList.remove("hide");
    return;
  }

  if (!data.contentNodes.pageInfo.hasPreviousPage) {
    totalCount = data.contentNodes.pageInfo.total;
    document.getElementById("results-total").textContent = `${totalCount} ${
      totalCount > 1 ? storiesLocal : storyLocal
    }`;
  }

  noResults.classList.add("hide");

  data.contentNodes.edges.forEach((edge) => {
    const node = edge.node;

    if (!node || Object.keys(node).length === 0) {
      return;
    }

    // Special handling for ShortArticle with video/audio formats
    if (node.__typename === "ShortArticle") {
      const shortsFormat = node.shortsFormats?.nodes?.[0]?.slug || "content";

      if (shortsFormat === "video") {
        renderShortsVideo(node);
        return;
      } else if (shortsFormat === "audio") {
        renderShortsAudio(node);
        return;
      }
    }

    const hasNoResponsiveImage =
      node.featuredImage === null ||
      (!node.featuredImage && !node.featuredImage.node) ||
      node.featuredImage.node.srcSet === null;

    const hasThumbnailImage = node.featuredImage && node.featuredImage.node.sourceUrl;

    const listItem = document.createElement("div");
    listItem.classList.add("article--container", "pv--8");
    const isContentShort =
      node.__typename === "ShortArticle" && node.shortsFormats?.nodes?.[0]?.slug === "content";
    const postLink = document.createElement(isContentShort ? "div" : "a");

    // Content shorts open in the shorts dialog, every other card links to its permalink
    if (!isContentShort) {
      postLink.href = node.link;
    }

    const postImage = document.createElement("img");
    const postFormatIcon = document.createElement("div");
    postFormatIcon.classList.add("post-icon");

    let iconClass = "";

    switch (node.__typename) {
      case "Video":
        iconClass = "icon-play";
        break;
      case "Podcast":
        iconClass = "icon-podcast";
        break;
      case "SpecialsArticle":
        iconClass = "icon-specials";
        break;
      case "ShortArticle":
        iconClass = "icon-shorts";
        break;
      default:
        iconClass = "";
        break;
    }

    if (iconClass.length > 0) {
      postFormatIcon.innerHTML = `<span class='icon ${iconClass}'></span>`;
    }

    const titleContainer = document.createElement("div");
    titleContainer.classList.add("title", "headline");
    const postTitle =
      hasNoResponsiveImage && !hasThumbnailImage
        ? document.createElement("h2")
        : document.createElement("h4");
    titleContainer.appendChild(postTitle);
    const postMeta = document.createElement("div");
    postMeta.classList.add("post-meta", "pv--8");

    const byline = document.createElement("span");
    byline.classList.add("byline");
    const postDate = document.createElement("span");
    postDate.classList.add("date");

    if (isContentShort) {
      const postExcerpt = document.createElement("div");
      postExcerpt.classList.add("post-excerpt", "hidden");
      const parsedContent = wpContentToHtml(node.unencodedContent);

      postExcerpt.innerHTML = parsedContent;
      listItem.appendChild(postExcerpt);
    }

    listItem.appendChild(postLink);
    const imageContainer = document.createElement("div");
    imageContainer.classList.add("featured-image", "mb--8");

    if (node.__typename !== "Post" && (!hasNoResponsiveImage || hasThumbnailImage)) {
      imageContainer.appendChild(postFormatIcon);
    }

    if (!hasNoResponsiveImage || hasThumbnailImage) {
      imageContainer.appendChild(postImage);
      postLink.appendChild(imageContainer);
    } else {
      postLink.classList.add("no-image");
    }

    if (isMobile) {
      const titleMetaContainer = document.createElement("div");
      titleMetaContainer.appendChild(titleContainer);
      titleMetaContainer.appendChild(postMeta);
      postLink.appendChild(titleMetaContainer);
    } else {
      postLink.appendChild(titleContainer);
      postLink.appendChild(postMeta);
    }

    postMeta.appendChild(byline);
    postMeta.appendChild(postDate);

    postTitle.textContent = node.title;

    if (hasNoResponsiveImage && !hasThumbnailImage) {
      postImage.src = `${domain}/gql-src/no-image.png`;
      postImage.style.opacity = 0;
      // imageContainer.remove();
    } else {
      if (!hasNoResponsiveImage) {
        postImage.srcset = node.featuredImage.node.srcSet;
        postImage.sizes = node.featuredImage.node.sizes;
      } else {
        postImage.src = node.featuredImage.node.sourceUrl;
      }
    }

    if (!node.byline.nodes.length) {
      byline.textContent = "";
    } else {
      const bylinesArray = node.byline.nodes.map((byline) => byline.name);
      byline.textContent = bylinesArray.join(", ");
    }
    postDate.textContent = formatDate(node.date);

    if (node.__typename === "ShortArticle" && node.shortsFormats?.nodes?.[0]?.slug === "content") {
      listItem.classList.add("shorts-trigger");
      listItem.dataset.articlelink = node.articleLink;
      listItem.dataset.shareurl = `${domain}/shorts/#/${node.databaseId || ""}`;
      listItem.dataset.mediaFormat = "content";
    }
    document.getElementById("post-results").appendChild(listItem);
  });

  if (data.contentNodes.pageInfo.hasNextPage) {
    cursor = data.contentNodes.pageInfo.endCursor;
    const loadMore = document.createElement("button");
    loadMore.classList.add("load-more", "theme--button", "primary");
    loadMore.innerHTML = `${loadMoreLocal}<span class="icon icon-right"></span>`;
    loadMore.addEventListener("click", () => fetchArticles(false));
    resultsFooter.innerHTML = "";

    resultsFooter.appendChild(loadMore);
  } else {
    resultsFooter.innerHTML = preloader(false);
  }

  defaultSuggestions.classList.add("hide");
  results.classList.remove("hide");

  // Reinitialize shorts dialog handlers for dynamically added content
  if (typeof window.initShortsDialog === "function") {
    setTimeout(() => {
      window.initShortsDialog();
    }, 100);
  }

  // Initialize masonry layout
  const postResults = document.getElementById("post-results");

  const MasonryConstructor =
    window.MasonryGrid || window.Masonry || window.masonryGrid || window.masonry;

  if (postResults && MasonryConstructor) {
    // Wait for images to load and DOM to be ready
    setTimeout(() => {
      if (!postResults.dataset.masonryInit) {
        postResults.dataset.masonryInit = "true";

        // Try the API that works with masonry-grid library
        try {
          new MasonryConstructor({
            container: postResults,
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
        } catch (e) {
          console.error("Masonry initialization failed:", e);
        }
      }
    }, 150);
  }
}

// Search for topics
async function searchTopic() {
  document.getElementById("topics-suggestions").classList.add("hide");
  const searchValue = document.getElementById("searchTopic").value;
  const topicsResults = document.getElementById("topics-results");
  const topicsActions = document.querySelector(".tax-search-actions.topic");
  topicsActions.innerHTML = preloader(true);

  if (!searchValue.length) {
    topicsResults.innerHTML = "";
    topicsResults.classList.add("hide");
    topicsActions.innerHTML = "";
    return;
  }

  const resp = await fetch(
    `${domain}/graphql?query=query{topics(first:10,where:{search:\"${searchValue}\"}){edges{node{name slug}}}}`,
    { method: "GET", cache: "default", credentials: "omit" },
  );
  const { data } = await resp.json();

  if (data.topics.edges.length === 0) {
    topicsResults.innerHTML = "<p>No topics found</p>";
    topicsResults.classList.remove("hide");
    topicsActions.innerHTML = "";
    return;
  }

  if (searchValue.length > 0 && data.topics.edges.length > 0) {
    document.getElementById("topics-suggestions").classList.add("hide");
  }

  topicsResults.classList.remove("hide");
  topicsResults.innerHTML = "";
  topicsResults.style.top = `${document.querySelector(".tax-item-wrapper.topics").offsetHeight}px`;

  data.topics.edges.forEach((edge) => {
    const node = edge.node;
    const topic = document.createElement("span");

    topic.addEventListener("click", () => {
      if (!selectedTopics.includes(node.slug)) {
        createTaxTag("topic", node.name, node.slug);

        if (searchValue.length > 0) {
          // fetchArticles();
        }
        fetchArticles(true);
        // clearSearch();
      }
    });

    topic.textContent = node.name;
    highlightResults(searchValue, topic);
    topicsResults.appendChild(topic);
  });

  if (document.querySelector(".tax-search-actions.topic .preloader-wrapper")) {
    topicsActions.removeChild(
      document.querySelector(".tax-search-actions.topic .preloader-wrapper"),
    );
  }
}

// Search for locations
async function searchLocation() {
  document.getElementById("locations-suggestions").classList.add("hide");
  const searchValue = document.getElementById("searchLocation").value;
  const locationsResults = document.getElementById("locations-results");
  const locationActions = document.querySelector(".tax-search-actions.location");
  locationActions.innerHTML = preloader(true);

  if (!searchValue.length) {
    locationsResults.innerHTML = "";
    locationsResults.classList.add("hide");
    locationActions.innerHTML = "";
    return;
  }

  const resp = await fetch(
    `${domain}/graphql?query=query{locations(first:10,where:{search:\"${searchValue}\"}){edges{node{name slug}}}}`,
    { method: "GET", cache: "default", credentials: "omit" },
  );
  const { data } = await resp.json();

  if (data.locations.edges.length === 0) {
    locationsResults.innerHTML = "<p>No results found</p>";
    locationsResults.classList.remove("hide");
    return;
  }

  if (searchValue.length > 0) {
    // displayClearButton("#searchLocation", ".tax-search-actions.location");
  }

  locationsResults.classList.remove("hide");

  locationsResults.innerHTML = "";

  locationsResults.style.top = `${
    document.querySelector(".tax-item-wrapper.locations").offsetHeight
  }px`;

  data.locations.edges.forEach((edge) => {
    const node = edge.node;
    const location = document.createElement("span");

    location.addEventListener("click", () => {
      if (!selectedLocations.includes(node.slug)) {
        createTaxTag("location", decodeURI(node.name), node.slug);

        // if (searchValue.length > 0) {
        //   fetchArticles(true);
        // }
        fetchArticles(true);
      }
    });

    location.textContent = decodeURI(node.name);
    highlightResults(searchValue, location);
    locationsResults.appendChild(location);
  });

  locationActions.removeChild(
    document.querySelector(".tax-search-actions.location .preloader-wrapper"),
  );
}

// Articles format filter
function formatFilter() {
  const searchValue = document.getElementById("searchFormat").value;

  if (!searchValue.length) {
    formatResults.classList.add("hide");
    return;
  }

  if (searchValue.length > 0) {
    document.getElementById("formats-suggestions").classList.add("hide");
  }

  const transformedSearch = searchValue.replace(/\ /g, "").toLowerCase();

  let filteredOptions = Object.values(formatOptions).filter((value) =>
    value[0].toLowerCase().includes(transformedSearch),
  );

  if (Object.keys(filteredOptions).length === 0) {
    formatResults.innerHTML = "<p>No format found</p>";
    formatResults.classList.remove("hide");
    return;
  }

  // Check what types of formats are already selected
  const hasShorts = selectedFormats.some((f) => shortsFormatKeys.includes(f));
  const hasNonShorts = selectedFormats.some((f) => !shortsFormatKeys.includes(f));

  formatResults.innerHTML = "";

  Object.values(filteredOptions).forEach((format) => {
    const formatSpan = document.createElement("span");
    formatSpan.textContent = format[0];

    const formatSlug = format[1];
    const isShorts = shortsFormatKeys.includes(formatSlug);
    const isDisabled = (hasShorts && !isShorts) || (hasNonShorts && isShorts);

    if (isDisabled) {
      formatSpan.classList.add("disabled");
      formatSpan.style.opacity = "0.5";
      formatSpan.style.cursor = "not-allowed";
    } else {
      formatSpan.addEventListener("click", () => {
        createFormatTag(format[0], format[1]);
      });
    }

    highlightResults(searchValue, formatSpan);
    formatResults.appendChild(formatSpan);
  });

  formatResults.style.top = `${document.querySelector(".tax-item-wrapper.format").offsetHeight}px`;
  formatResults.classList.remove("hide");
}
