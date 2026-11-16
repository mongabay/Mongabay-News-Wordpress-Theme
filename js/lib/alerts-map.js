(function () {
  const config = window.alertsMapConfig || {};
  const GRAPHQL_ENDPOINT = config.graphqlEndpoint || "/graphql";
  const MAPBOX_TOKEN = config.mapboxToken || "";
  const markerSourceId = "alerts-markers";
  const markerLayerId = "alerts-marker-circles";

  let map;
  let mapReady = false;
  let allAlerts = [];
  let filteredAlerts = [];
  let selectedAlertIndex = -1;
  let articleDetailsRequest = 0;

  function buildQuery() {
    return `
      query AlertArticles($after: String) {
        shortArticles(first: 20, after: $after, where: { hasAlert: true, status: PUBLISH, orderby: { field: DATE, order: DESC } }) {
          nodes {
            databaseId
            title
            uri
            date
            excerpt
            alert
            alertMapCoordinates
            alertMapZoom
            alertMapSource
            mapBoxStyle
            articleLink
            featuredImage {
              node {
                sourceUrl(size: MEDIUM)
              }
            }
            topics { nodes { name slug } }
            locations {
              nodes {
                name
              }
            }
          }
          pageInfo { hasNextPage endCursor }
        }
      }
    `;
  }

  async function fetchAlerts() {
    const alerts = [];
    let after = null;
    let hasNextPage = true;
    let pageCount = 0;

    while (hasNextPage && pageCount < 10) {
      const response = await fetch(GRAPHQL_ENDPOINT, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: buildQuery(), variables: { after } }),
      });

      if (!response.ok) {
        throw new Error(`Alert request failed with status ${response.status}`);
      }

      const result = await response.json();
      if (!result.data?.shortArticles) {
        throw new Error(result.errors?.map((error) => error.message).join(", ") || "Alert data is unavailable");
      }
      if (result.errors?.length) {
        console.warn("GraphQL returned partial alert data.", result.errors);
      }

      const connection = result.data.shortArticles;
      alerts.push(...connection.nodes.filter((alert) => alert.alertMapCoordinates));
      hasNextPage = connection.pageInfo.hasNextPage;
      after = connection.pageInfo.endCursor;
      pageCount++;
    }

    return { alerts, truncated: hasNextPage };
  }

  function parseCoordinates(coordString) {
    if (!coordString) return null;
    const parts = coordString.split(",").map((part) => Number.parseFloat(part.trim()));
    return parts.length >= 2 && parts.every(Number.isFinite) ? [parts[1], parts[0]] : null;
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    return Number.isNaN(date.getTime())
      ? ""
      : date.toLocaleDateString("en-GB", { day: "numeric", month: "short", year: "numeric" });
  }

  function getTerms(alert, taxonomy) {
    return alert[taxonomy]?.nodes || [];
  }

  function getTermLabel(term) {
    return term.name || term.slug?.replace(/-/g, " ").replace(/\b\w/g, (letter) => letter.toUpperCase()) || "";
  }

  function getTermValue(term) {
    return term.slug || getTermLabel(term).toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  }

  function getThemeColor(name, fallback) {
    return getComputedStyle(document.body).getPropertyValue(name).trim() || fallback;
  }

  function getMarkerColorExpression() {
    return [
      "case",
      ["==", ["get", "index"], selectedAlertIndex],
      getThemeColor("--theme-emerald-green", "#006a54"),
      getThemeColor("--theme-red", "#e63946"),
    ];
  }

  function initMap() {
    window.mapboxgl.accessToken = MAPBOX_TOKEN;
    map = new window.mapboxgl.Map({
      container: "alerts-globe",
      style: "mapbox://styles/mapbox/satellite-streets-v12",
      projection: "globe",
      center: [20, 20],
      zoom: 1.5,
    });

    map.addControl(new window.mapboxgl.NavigationControl(), "top-right");
    map.on("error", function (event) {
      console.error("Mapbox alert map error.", event.error || event);
      showMapMessage(config.mapUnavailable || "The map is unavailable.");
    });
    map.on("style.load", function () {
      map.setFog({
        color: "rgb(10, 20, 40)",
        "high-color": "rgb(20, 30, 60)",
        "horizon-blend": 0.08,
        "space-color": "rgb(10, 15, 30)",
        "star-intensity": 0.6,
      });
    });

    map.on("load", function () {
      map.addSource(markerSourceId, {
        type: "geojson",
        data: { type: "FeatureCollection", features: [] },
      });
      map.addLayer({
        id: markerLayerId,
        type: "circle",
        source: markerSourceId,
        paint: {
          "circle-color": getMarkerColorExpression(),
          "circle-radius": ["case", ["==", ["get", "index"], selectedAlertIndex], 12, 8],
          "circle-stroke-color": getThemeColor("--theme-body-white", "#ffffff"),
          "circle-stroke-width": 2,
        },
      });
      map.on("click", markerLayerId, function (event) {
        if (!event.features?.length) return;
        const index = Number(event.features[0].properties.index);
        const alert = filteredAlerts[index];
        if (alert) openArticle(alert, index);
      });
      map.on("mouseenter", markerLayerId, function () {
        map.getCanvas().style.cursor = "pointer";
      });
      map.on("mouseleave", markerLayerId, function () {
        map.getCanvas().style.cursor = "";
      });

      mapReady = true;
      renderMarkers();
    });
  }

  function populateFilter(selectId, taxonomy, defaultLabel) {
    const select = document.getElementById(selectId);
    const terms = new Map();
    allAlerts.forEach((alert) => {
      getTerms(alert, taxonomy).forEach((term) => {
        const value = getTermValue(term);
        if (value) terms.set(value, getTermLabel(term));
      });
    });

    Array.from(terms.entries())
      .sort((a, b) => a[1].localeCompare(b[1]))
      .forEach(([slug, name]) => {
        const option = document.createElement("option");
        option.value = slug;
        option.textContent = name;
        select.appendChild(option);
      });
    select.options[0].textContent = defaultLabel;
    select.closest("label").hidden = terms.size === 0;
    select.addEventListener("change", applyFilters);
  }

  function applyFilters() {
    const location = document.getElementById("alerts-location-filter").value;
    const topic = document.getElementById("alerts-topic-filter").value;
    const format = document.getElementById("alerts-format-filter")?.value || "all";

    filteredAlerts = allAlerts.filter((alert) => {
      const matchesLocation = location === "all" || getTerms(alert, "locations").some((term) => getTermValue(term) === location);
      const matchesTopic = topic === "all" || getTerms(alert, "topics").some((term) => getTermValue(term) === topic);
      const matchesFormat = format === "all";
      return matchesLocation && matchesTopic && matchesFormat;
    });

    selectedAlertIndex = -1;
    renderArticles();
    renderMarkers();
  }

  function createArticleCard(alert, index) {
    const card = document.createElement("div");
    card.classList.add("article--container", "pv--8");
    const link = document.createElement("a");
    link.href = alert.articleLink || alert.uri || "#";

    const imageUrl = alert.featuredImage?.node?.sourceUrl;
    const imageContainer = document.createElement("div");
    imageContainer.classList.add("featured-image", "mb--8");
    const image = document.createElement("img");
    image.src = imageUrl || `${window.location.origin}/gql-src/no-image.png`;
    image.alt = "";
    image.loading = "lazy";
    imageContainer.appendChild(image);
    link.appendChild(imageContainer);

    const title = document.createElement("div");
    title.classList.add("title", "headline");
    const heading = document.createElement("h4");
    heading.textContent = alert.title;
    title.appendChild(heading);
    link.appendChild(title);

    const meta = document.createElement("div");
    meta.classList.add("post-meta", "pv--8");
    const date = document.createElement("span");
    date.classList.add("date");
    date.textContent = formatDate(alert.date);
    meta.appendChild(date);
    link.appendChild(meta);
    card.appendChild(link);

    link.addEventListener("click", (event) => {
      event.preventDefault();
      openArticle(alert, index);
    });
    return card;
  }

  function renderArticles() {
    const grid = document.getElementById("alerts-article-grid");
    const status = document.getElementById("alerts-results-status");
    grid.replaceChildren();

    if (!filteredAlerts.length) {
      status.textContent = config.noAlertsLabel || "No alerts found for these filters.";
      return;
    }

    status.textContent = `${filteredAlerts.length} ${config.alertsLabel || "alerts"}`;
    filteredAlerts.forEach((alert, index) => {
      grid.appendChild(createArticleCard(alert, index));
    });
  }

  function renderMarkers() {
    if (!mapReady) return;
    const source = map.getSource(markerSourceId);
    if (!source) return;

    const features = [];
    filteredAlerts.forEach((alert, index) => {
      const coordinates = parseCoordinates(alert.alertMapCoordinates);
      if (!coordinates) return;
      features.push({
        type: "Feature",
        properties: { index },
        geometry: { type: "Point", coordinates },
      });
    });

    source.setData({ type: "FeatureCollection", features });
    if (map.getLayer(markerLayerId)) {
      map.setPaintProperty(markerLayerId, "circle-color", getMarkerColorExpression());
      map.setPaintProperty(markerLayerId, "circle-radius", [
        "case",
        ["==", ["get", "index"], selectedAlertIndex],
        12,
        8,
      ]);
    }
  }

  async function fetchArticleDetails(databaseId) {
    const query = `
      query AlertArticleDetails($id: ID!) {
        shortArticle(idType: DATABASE_ID, id: $id) {
          content
          byline { nodes { name } }
        }
      }
    `;
    const response = await fetch(GRAPHQL_ENDPOINT, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables: { id: databaseId } }),
    });

    if (!response.ok) {
      throw new Error(`Article detail request failed with status ${response.status}`);
    }

    const result = await response.json();
    if (result.errors?.length || !result.data?.shortArticle) {
      throw new Error(result.errors?.map((error) => error.message).join(", ") || "Article details are unavailable");
    }
    return result.data.shortArticle;
  }

  async function openArticle(alert, index) {
    selectedAlertIndex = index;
    renderMarkers();
    const requestId = ++articleDetailsRequest;

    const dialog = document.getElementById("alerts-article-dialog");
    const imageContainer = document.getElementById("alerts-dialog-image");
    const imageUrl = alert.featuredImage?.node?.sourceUrl;
    const articleUrl = alert.articleLink || alert.uri || "#";

    document.getElementById("alerts-dialog-title").textContent = alert.title;
    document.getElementById("alerts-dialog-byline").textContent = "";
    document.getElementById("alerts-dialog-date").textContent = formatDate(alert.date);
    imageContainer.replaceChildren();
    if (imageUrl) {
      const image = document.createElement("img");
      image.src = imageUrl;
      image.alt = "";
      imageContainer.appendChild(image);
    }
    document.getElementById("alerts-dialog-content").innerHTML = alert.excerpt || "";
    document.getElementById("alerts-dialog-link").href = articleUrl;
    const content = document.getElementById("alerts-dialog-content");
    content.innerHTML = alert.excerpt || "";

    if (!dialog.open) dialog.showModal();
    const coordinates = parseCoordinates(alert.alertMapCoordinates);
    if (coordinates && map) {
      map.flyTo({
        center: coordinates,
        zoom: alert.alertMapZoom ? Number.parseFloat(alert.alertMapZoom) : 4,
        duration: 1000,
      });
    }

    try {
      const details = await fetchArticleDetails(alert.databaseId);
      if (requestId !== articleDetailsRequest || !dialog.open) return;
      content.innerHTML = details.content || alert.excerpt || "";
      document.getElementById("alerts-dialog-byline").textContent =
        details.byline?.nodes?.map((author) => author.name).filter(Boolean).join(", ") || "";
    } catch (error) {
      console.error("Unable to load full alert article.", error);
    }
  }

  function initDialog() {
    const dialog = document.getElementById("alerts-article-dialog");
    document.getElementById("alerts-dialog-close").addEventListener("click", () => dialog.close());
    dialog.addEventListener("click", (event) => {
      if (event.target === dialog) dialog.close();
    });
  }

  function showMapMessage(message) {
    const mapMessage = document.getElementById("alerts-map-message");
    mapMessage.textContent = message;
    mapMessage.hidden = false;
  }

  async function init() {
    initDialog();
    if (MAPBOX_TOKEN) {
      try {
        if (!window.mapboxgl) throw new Error("Mapbox GL JS is unavailable.");
        initMap();
      } catch (error) {
        console.error("Unable to initialize the alerts map.", error);
        showMapMessage(config.mapUnavailable || "The map is unavailable.");
      }
    } else {
      showMapMessage(config.mapUnavailable || "The map is unavailable.");
    }

    try {
      const result = await fetchAlerts();
      allAlerts = result.alerts;
      populateFilter("alerts-location-filter", "locations", config.allLocationsLabel || "All locations");
      populateFilter("alerts-topic-filter", "topics", config.allTopicsLabel || "All topics");
      filteredAlerts = allAlerts.slice();
      renderArticles();
      renderMarkers();
      if (result.truncated) {
        document.getElementById("alerts-results-status").textContent +=
          ` ${config.alertsTruncatedLabel || "Additional alerts were omitted."}`;
      }
    } catch (error) {
      console.error("Unable to load alerts.", error);
      document.getElementById("alerts-results-status").textContent =
        config.alertsUnavailable || "Alerts could not be loaded. Please try again later.";
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
