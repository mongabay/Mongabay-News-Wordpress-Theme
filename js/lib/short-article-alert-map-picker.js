(function () {
  const logPrefix = "[Alert map picker]";
  const config = window.shortArticleAlertMapPickerConfig || {};
  const initialCoordinatesField = document.getElementById(
    "pods-form-ui-pods-meta-alert-map-coordinates",
  );

  function initializePicker(coordinatesField) {
    const host = coordinatesField.closest("td") || coordinatesField.parentElement;
    const coordinatesRow = document.createElement("div");
    coordinatesRow.className = "short-article-alert-map-coordinates-row";
    coordinatesField.parentElement.insertBefore(coordinatesRow, coordinatesField);
    coordinatesRow.appendChild(coordinatesField);

    const searchToggle = document.createElement("button");
    searchToggle.type = "button";
    searchToggle.className = "short-article-alert-map-search-toggle";
    searchToggle.setAttribute("aria-label", config.searchLabel || "Search alert location");
    searchToggle.setAttribute("aria-expanded", "false");
    searchToggle.innerHTML = '<span class="icon icon-search" aria-hidden="true"></span>';
    coordinatesRow.appendChild(searchToggle);

    const picker = document.createElement("div");
    picker.className = "short-article-alert-map-picker";
    picker.innerHTML =
      '<label class="screen-reader-text" for="short-article-alert-map-search"></label>' +
      '<input id="short-article-alert-map-search" type="search" autocomplete="off" />' +
      '<div class="short-article-alert-map-results" role="listbox" aria-label="Map search results"></div>' +
      '<div class="short-article-alert-map" aria-label="Pick an alert location on the map"></div>' +
      '<p class="short-article-alert-map-status" role="status" aria-live="polite"></p>';
    host.appendChild(picker);

    const searchInput = picker.querySelector("input");
    const results = picker.querySelector(".short-article-alert-map-results");
    const mapContainer = picker.querySelector(".short-article-alert-map");
    const status = picker.querySelector(".short-article-alert-map-status");
    picker.querySelector("label").textContent = config.searchLabel || "Search alert location";
    searchInput.placeholder = config.searchPlaceholder || "Search for a place";
    searchInput.hidden = true;
    searchToggle.setAttribute("aria-controls", searchInput.id);
    let map;
    let marker;
    let markerAdded = false;
    let searchTimeout;
    let coordinatesSyncTimeout;
    let requestNumber = 0;
    let syncingCoordinatesField = false;

    function findStyleField() {
      return (
        document.getElementById("pods-form-ui-pods-meta-styles-url") ||
        document.querySelector('[name="pods_meta[styles_url]"], [name="styles_url"]')
      );
    }

    function setupStyleSelector(styleField) {
      if (styleField.dataset.mapboxStylePickerReady === "1") return;
      styleField.dataset.mapboxStylePickerReady = "1";

      const selectorWrap = document.createElement("div");
      selectorWrap.className = "short-article-alert-style-picker";
      const selector = document.createElement("select");
      selector.setAttribute("aria-label", config.stylesPlaceholder || "Choose a Mapbox style");
      const placeholder = document.createElement("option");
      placeholder.value = "";
      placeholder.textContent = config.stylesPlaceholder || "Choose a Mapbox style";
      selector.appendChild(placeholder);
      const selectorStatus = document.createElement("span");
      selectorStatus.className = "short-article-alert-style-status";
      selectorStatus.setAttribute("role", "status");
      selectorWrap.append(selector, selectorStatus);
      (styleField.closest("td") || styleField.parentElement).appendChild(selectorWrap);

      selector.addEventListener("change", function () {
        const selectedOption = selector.selectedOptions[0];
        if (!selectedOption || !selectedOption.value) return;

        const valueSetter = Object.getOwnPropertyDescriptor(
          Object.getPrototypeOf(styleField),
          "value",
        )?.set;
        if (valueSetter) valueSetter.call(styleField, selectedOption.value);
        else styleField.value = selectedOption.value;
        styleField.dispatchEvent(new Event("input", { bubbles: true }));
        styleField.dispatchEvent(new Event("change", { bubbles: true }));
        selectorStatus.textContent = "";
      });

      loadMapboxStyles()
        .then(function (styles) {
          styles.forEach(function (style) {
            const option = document.createElement("option");
            option.value = "mapbox://styles/mongabay/" + style.id;
            option.textContent = style.name;
            selector.appendChild(option);
          });

          const existingOption = Array.from(selector.options).find(function (option) {
            return option.value && option.value === styleField.value;
          });
          if (existingOption) selector.value = existingOption.value;
        })
        .catch(function (error) {
          selector.disabled = true;
          selectorStatus.textContent =
            error.message || config.stylesUnavailable || "Mapbox styles could not be loaded.";
          console.error(logPrefix, "Styles could not be loaded", error);
        });
    }

    async function loadMapboxStyles() {
      const response = await fetch(config.ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
        body: new URLSearchParams({
          action: "mongabay_get_mapbox_styles",
          nonce: config.stylesNonce,
        }),
      });
      const result = await response.json();
      if (!response.ok || !result.success || !Array.isArray(result.data)) {
        throw new Error(
          result.data && result.data.message ? result.data.message : "Styles request failed",
        );
      }
      return result.data;
    }

    function waitForStyleField() {
      const styleField = findStyleField();
      if (styleField) {
        setupStyleSelector(styleField);
        return;
      }

      const styleFieldObserver = new MutationObserver(function () {
        const renderedStyleField = findStyleField();
        if (!renderedStyleField) return;
        styleFieldObserver.disconnect();
        setupStyleSelector(renderedStyleField);
      });
      styleFieldObserver.observe(document.body, { childList: true, subtree: true });
    }

    waitForStyleField();

    function parseCoordinates(value) {
      const parts = (value || "").split(",").map(function (part) {
        return Number(part.trim());
      });
      if (
        parts.length !== 2 ||
        !Number.isFinite(parts[0]) || !Number.isFinite(parts[1]) ||
        parts[0] < -90 || parts[0] > 90 ||
        parts[1] < -180 || parts[1] > 180
      ) return null;
      return [parts[1], parts[0]];
    }

    function getLiveCoordinatesField() {
      return (
        document.getElementById("pods-form-ui-pods-meta-alert-map-coordinates") ||
        document.querySelector(
          '[name="pods_meta[alert_map_coordinates]"], [name="alert_map_coordinates"]',
        ) ||
        coordinatesField
      );
    }

    function getLiveZoomField() {
      return (
        document.getElementById("pods-form-ui-pods-meta-alert-map-zoom-level") ||
        document.querySelector(
          '[name="pods_meta[alert_map_zoom_level]"], [name="alert_map_zoom_level"]',
        )
      );
    }

    function setFieldValue(field, value) {
      if (!field) return;
      const valueSetter = Object.getOwnPropertyDescriptor(
        Object.getPrototypeOf(field),
        "value",
      )?.set;
      if (valueSetter) valueSetter.call(field, value);
      else field.value = value;
      field.dispatchEvent(new Event("input", { bubbles: true }));
      field.dispatchEvent(new Event("change", { bubbles: true }));
    }

    function getPlaceZoom(feature) {
      const zoomByPlaceType = {
        country: 4,
        region: 6,
        district: 8,
        place: 10,
        locality: 12,
        postcode: 12,
        neighborhood: 14,
        address: 16,
        poi: 16,
      };
      const placeTypes = feature.place_type || [];
      for (let index = placeTypes.length - 1; index >= 0; index -= 1) {
        if (zoomByPlaceType[placeTypes[index]]) return zoomByPlaceType[placeTypes[index]];
      }
      return 10;
    }

    function syncMapFromCoordinatesField() {
      if (syncingCoordinatesField || !map || !marker) return;
      const field = getLiveCoordinatesField();
      const coordinates = parseCoordinates(field.value);
      if (!coordinates) return;

      const [longitude, latitude] = coordinates;
      if (longitude < -180 || longitude > 180 || latitude < -90 || latitude > 90) return;

      const zoomField = getLiveZoomField();
      const requestedZoom = Number.parseFloat(zoomField && zoomField.value);
      const zoom = Number.isFinite(requestedZoom) && requestedZoom >= 0 && requestedZoom <= 22
        ? requestedZoom
        : Math.max(map.getZoom(), 8);
      marker.setLngLat(coordinates);
      if (!markerAdded) {
        marker.addTo(map);
        markerAdded = true;
      }
      map.flyTo({ center: coordinates, zoom: zoom });
    }

    function scheduleCoordinatesSync() {
      if (syncingCoordinatesField) return;
      window.clearTimeout(coordinatesSyncTimeout);
      if (!parseCoordinates(getLiveCoordinatesField().value)) return;
      coordinatesSyncTimeout = window.setTimeout(syncMapFromCoordinatesField, 400);
    }

    function setCoordinates(longitude, latitude, zoom) {
      if (
        !Number.isFinite(longitude) ||
        !Number.isFinite(latitude) ||
        longitude < -180 || longitude > 180 ||
        latitude < -90 || latitude > 90
      ) return;

      const field = getLiveCoordinatesField();
      const value = latitude.toFixed(6) + ", " + longitude.toFixed(6);
      syncingCoordinatesField = true;
      setFieldValue(field, value);
      syncingCoordinatesField = false;
      marker.setLngLat([longitude, latitude]);
      if (!markerAdded) {
        marker.addTo(map);
        markerAdded = true;
      }
      map.flyTo({ center: [longitude, latitude], zoom: Number.isFinite(zoom) ? zoom : map.getZoom() });
      status.textContent = value;
    }

    function addResult(feature) {
      const button = document.createElement("button");
      button.type = "button";
      button.className = "short-article-alert-map-result";
      button.setAttribute("role", "option");
      button.textContent = feature.place_name;
      button.addEventListener("click", function () {
        const coordinates = feature.geometry.coordinates;
        setCoordinates(coordinates[0], coordinates[1], getPlaceZoom(feature));
        results.replaceChildren();
        searchInput.value = feature.place_name;
      });
      results.appendChild(button);
    }

    async function searchPlaces(query) {
      const currentRequest = ++requestNumber;
      const url = new URL(
        "https://api.mapbox.com/geocoding/v5/mapbox.places/" + encodeURIComponent(query) + ".json",
      );
      url.searchParams.set("access_token", config.mapboxToken);
      url.searchParams.set("autocomplete", "true");
      url.searchParams.set("limit", "5");

      try {
        const response = await fetch(url.toString());
        if (!response.ok) throw new Error("Mapbox search request failed");
        const data = await response.json();
        if (currentRequest !== requestNumber) return;

        results.replaceChildren();
        if (!data.features || !data.features.length) {
          status.textContent = config.noResults || "No places found.";
          return;
        }
        data.features.forEach(addResult);
        status.textContent = "";
      } catch (error) {
        console.error(logPrefix, "Search failed", error);
        if (currentRequest === requestNumber)
          status.textContent = config.mapUnavailable || "Mapbox is unavailable.";
      }
    }

    function ensureMap() {
      if (map) return;
      if (!config.mapboxToken || !window.mapboxgl) return;

      window.mapboxgl.accessToken = config.mapboxToken;
      const savedCoordinates = parseCoordinates(coordinatesField.value);
      const savedZoom = Number.parseFloat((getLiveZoomField() || {}).value);
      map = new window.mapboxgl.Map({
        container: mapContainer,
        style: "mapbox://styles/mapbox/streets-v12",
        center: savedCoordinates || [0, 15],
        zoom: Number.isFinite(savedZoom) && savedZoom >= 0 && savedZoom <= 22
          ? savedZoom
          : (savedCoordinates ? 5 : 1.5),
      });
      map.on("error", function (event) {
        console.error(logPrefix, "Mapbox error", {
          message: event.error && event.error.message,
          error: event.error,
          sourceId: event.sourceId,
          sourceType: event.sourceType,
          tile: event.tile,
        });
      });
      map.on("zoomend", function () {
        const zoomField = getLiveZoomField();
        if (zoomField) setFieldValue(zoomField, map.getZoom().toFixed(2));
      });
      marker = new window.mapboxgl.Marker({ draggable: true });
      if (savedCoordinates) {
        marker.setLngLat(savedCoordinates).addTo(map);
        markerAdded = true;
      }

      map.on("click", function (event) {
        setCoordinates(event.lngLat.lng, event.lngLat.lat, map.getZoom());
      });
      marker.on("dragend", function () {
        const coordinates = marker.getLngLat();
        setCoordinates(coordinates.lng, coordinates.lat);
      });
    }

    function alertIsEnabled() {
      const alertFields = getAlertFields();
      if (!alertFields.length) return true;

      const checkableFields = alertFields.filter(function (field) {
        return field.type === "checkbox" || field.type === "radio";
      });
      if (checkableFields.length) {
        return checkableFields.some(function (field) {
          return (
            field.checked && ["1", "true", "yes", "on"].includes((field.value || "").toLowerCase())
          );
        });
      }

      return ["1", "true", "yes", "on"].includes((alertFields[0].value || "").toLowerCase());
    }

    function getAlertFields() {
      const fields = Array.from(
        document.querySelectorAll(
          '#pods-form-ui-pods-meta-alert, [name="pods_meta[alert]"], [name="alert"]',
        ),
      );
      document.querySelectorAll('[id^="pods-form-ui-pods-meta-alert-"]').forEach(function (field) {
        if (/^pods-form-ui-pods-meta-alert-\d+$/.test(field.id)) fields.push(field);
      });
      return Array.from(new Set(fields));
    }

    function isAlertField(field) {
      return getAlertFields().includes(field);
    }

    function updateVisibility() {
      const visible = alertIsEnabled();
      picker.hidden = !visible;
      if (visible) {
        ensureMap();
        if (map)
          window.requestAnimationFrame(function () {
            map.resize();
          });
      }
    }

    searchInput.addEventListener("input", function () {
      window.clearTimeout(searchTimeout);
      const query = searchInput.value.trim();
      if (query.length < 2 || !config.mapboxToken) {
        results.replaceChildren();
        return;
      }
      searchTimeout = window.setTimeout(function () {
        searchPlaces(query);
      }, 250);
    });

    searchToggle.addEventListener("click", function () {
      searchInput.hidden = !searchInput.hidden;
      searchToggle.setAttribute("aria-expanded", String(!searchInput.hidden));
      if (!searchInput.hidden) searchInput.focus();
      else results.replaceChildren();
    });

    document.addEventListener("change", function (event) {
      if (isAlertField(event.target)) {
        updateVisibility();
      }
    });
    document.addEventListener("input", function (event) {
      if (isAlertField(event.target)) {
        updateVisibility();
      }
    });
    document.addEventListener("input", function (event) {
      if (event.target === getLiveCoordinatesField()) scheduleCoordinatesSync();
    });
    document.addEventListener("change", function (event) {
      if (event.target === getLiveCoordinatesField()) scheduleCoordinatesSync();
    });
    updateVisibility();

    if (!config.mapboxToken) status.textContent = config.mapUnavailable || "Mapbox is unavailable.";
  }

  if (initialCoordinatesField) {
    initializePicker(initialCoordinatesField);
  } else {
    const fieldObserver = new MutationObserver(function () {
      const coordinatesField = document.getElementById(
        "pods-form-ui-pods-meta-alert-map-coordinates",
      );
      if (!coordinatesField) return;

      fieldObserver.disconnect();
      initializePicker(coordinatesField);
    });
    fieldObserver.observe(document.body, { childList: true, subtree: true });
  }
})();
