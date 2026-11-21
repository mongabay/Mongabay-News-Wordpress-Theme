(function () {
  function initializeListingFilters() {
    const filters = document.querySelector("[data-listing-filters]");
    if (!filters) return;

    const row = filters.querySelector("[data-filter-row]");
    const config = window.listingFilterConfigLocal || {};
    const defaults = config.defaults || {};
    const labels = config.labels || {};
    const maxFilters = 3;
    let activeField = null;

    function getSelectedFilters() {
      return Array.from(row.querySelectorAll("[data-filter-field][data-slug]")).map(function (field) {
        return {
          taxonomy: field.dataset.taxonomy,
          slug: field.dataset.slug,
        };
      });
    }

    function navigate(filtersToApply) {
      const slugs = filtersToApply.map(function (item) {
        return item.slug;
      }).filter(Boolean);

      window.location.assign(
        slugs.length
          ? filters.dataset.listUrl + slugs.map(encodeURIComponent).join("/") + "/"
          : filters.dataset.homeUrl,
      );
    }

    function updateAddButton() {
      let button = row.querySelector("[data-add-filter]");
      if (getSelectedFilters().length >= maxFilters) {
        if (button) button.remove();
      } else if (!button) {
        button = document.createElement("button");
        button.type = "button";
        button.className = "listing-filter-add";
        button.dataset.addFilter = "";
        button.setAttribute("aria-label", labels.addFilter || "");
        button.innerHTML = '<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M12 5v14M5 12h14" /></svg>';
        row.appendChild(button);
      }
    }

    function closeDropdowns() {
      row.querySelectorAll(".listing-filter-dropdown").forEach(function (dropdown) {
        dropdown.classList.add("hide");
      });
    }

    function closeField(field) {
      if (!field) return;
      clearTimeout(field.searchTimer);
      field.searchVersion = (field.searchVersion || 0) + 1;
      if (field.request) field.request.abort();
      field.classList.remove("is-editing");
      const input = field.querySelector("[data-filter-input]");
      input.setAttribute("aria-expanded", "false");
      closeDropdowns();

      if (field.dataset.slug) {
        field.querySelector("[data-selected-pill]").hidden = false;
        input.hidden = true;
        input.value = "";
      } else {
        field.remove();
      }
      if (activeField === field) activeField = null;
      updateAddButton();
    }

    function appendOption(container, taxonomy, name, slug) {
      const option = document.createElement("span");
      option.textContent = name;
      option.dataset.taxonomy = taxonomy;
      option.dataset.slug = slug;
      option.setAttribute("role", "option");
      option.tabIndex = 0;
      container.appendChild(option);
    }

    function renderOptions(field, terms, isDefault) {
      const input = field.querySelector("[data-filter-input]");
      const results = field.querySelector(isDefault ? "[data-filter-suggestions]" : "[data-filter-results]");
      const selectedSlug = field.dataset.slug;
      const availableTerms = terms.filter(function (term) {
        return term.slug !== selectedSlug && !getSelectedFilters().some(function (selected) {
          return selected.taxonomy === term.taxonomy && selected.slug === term.slug;
        });
      });

      closeDropdowns();
      results.replaceChildren();
      if (!availableTerms.length && !isDefault) {
        const empty = document.createElement("p");
        empty.textContent = labels.noResults || "";
        results.appendChild(empty);
      } else {
        availableTerms.forEach(function (term) {
          appendOption(results, term.taxonomy, term.name, term.slug);
        });
      }

      results.classList.toggle("hide", isDefault && !availableTerms.length);
      input.setAttribute("aria-expanded", String(availableTerms.length > 0));
      if (activeField) activeField.classList.add("is-editing");
    }

    function renderDefaults(field) {
      const terms = [];
      ["topic", "location"].forEach(function (taxonomy) {
        (defaults[taxonomy] || []).forEach(function (item) {
          terms.push({
            taxonomy: taxonomy,
            name: item[0],
            slug: item[1],
          });
        });
      });
      renderOptions(field, terms, true);
    }

    function showError(field) {
      const input = field.querySelector("[data-filter-input]");
      const results = field.querySelector("[data-filter-results]");
      results.replaceChildren();
      const message = document.createElement("p");
      message.textContent = labels.searchError || "";
      results.appendChild(message);
      closeDropdowns();
      results.classList.remove("hide");
      input.setAttribute("aria-expanded", "true");
    }

    function searchTerms(field, query, searchVersion) {
      const graphqlQuery =
        "query { topics(first: 10, where: { search: " +
        JSON.stringify(query) +
        " }) { edges { node { name slug } } } locations(first: 10, where: { search: " +
        JSON.stringify(query) +
        " }) { edges { node { name slug } } } }";
      const url = new URL(config.graphqlUrl || "/graphql", window.location.href);
      url.searchParams.set("query", graphqlQuery);
      const request = field.request = new AbortController();

      fetch(url.toString(), {
        method: "GET",
        cache: "default",
        credentials: "omit",
        signal: request.signal,
      })
        .then(function (response) {
          if (!response.ok) throw new Error("Term search request failed: " + response.status);
          return response.json();
        })
        .then(function (response) {
          if (field.request !== request || field.searchVersion !== searchVersion) return;
          if (response.errors || !response.data || !response.data.topics || !response.data.locations) {
            throw new Error("Term search returned an invalid GraphQL response");
          }
          const terms = [];
          response.data.topics.edges.forEach(function (edge) {
            terms.push({ taxonomy: "topic", name: edge.node.name, slug: edge.node.slug });
          });
          response.data.locations.edges.forEach(function (edge) {
            terms.push({ taxonomy: "location", name: edge.node.name, slug: edge.node.slug });
          });
          renderOptions(field, terms, false);
        })
        .catch(function (error) {
          if (
            error.name === "AbortError" ||
            field.request !== request ||
            field.searchVersion !== searchVersion
          ) {
            return;
          }
          console.error(error);
          showError(field);
        });
    }

    function activateField(field, placeholder) {
      if (activeField && activeField !== field) closeField(activeField);
      activeField = field;
      field.classList.add("is-editing");
      const selectedPill = field.querySelector("[data-selected-pill]");
      const input = field.querySelector("[data-filter-input]");
      if (selectedPill) selectedPill.hidden = true;
      input.hidden = false;
      input.placeholder = placeholder || labels.addFilter || "";
      input.value = "";
      renderDefaults(field);
      input.focus();
    }

    function createField() {
      const field = document.createElement("div");
      field.className = "tax-item-wrapper listing-filter-field is-editing";
      field.dataset.filterField = "";
      const selection = document.createElement("div");
      selection.className = "listing-filter-selection";
      const input = document.createElement("input");
      input.type = "text";
      input.dataset.filterInput = "";
      input.placeholder = labels.addFilter || "";
      input.setAttribute("aria-label", labels.addFilter || "");
      input.setAttribute("aria-autocomplete", "list");
      input.setAttribute("aria-expanded", "false");
      input.autocomplete = "off";
      selection.appendChild(input);
      field.appendChild(selection);

      ["suggestions", "results"].forEach(function (type) {
        const dropdown = document.createElement("div");
        dropdown.className = "listing-filter-dropdown dropdown hide";
        dropdown.dataset["filter" + type[0].toUpperCase() + type.slice(1)] = "";
        dropdown.setAttribute("role", "listbox");
        field.appendChild(dropdown);
      });

      const addButton = row.querySelector("[data-add-filter]");
      row.insertBefore(field, addButton || null);
      return field;
    }

    row.addEventListener("click", function (event) {
      const addButton = event.target.closest("[data-add-filter]");
      if (addButton) {
        if (getSelectedFilters().length >= maxFilters) return;
        if (activeField) closeField(activeField);
        const field = createField();
        activateField(field, labels.addFilter);
        updateAddButton();
        return;
      }

      const editButton = event.target.closest("[data-edit-filter]");
      if (editButton) {
        const field = editButton.closest("[data-filter-field]");
        activateField(field, field.querySelector("[data-edit-filter] span").textContent);
        return;
      }

      const removeButton = event.target.closest("[data-remove-filter]");
      if (removeButton) {
        const field = removeButton.closest("[data-filter-field]");
        const remaining = getSelectedFilters().filter(function (item) {
          return item.taxonomy !== field.dataset.taxonomy || item.slug !== field.dataset.slug;
        });
        navigate(remaining);
        return;
      }

      const choice = event.target.closest(".listing-filter-dropdown span[data-slug]");
      if (choice) {
        const field = choice.closest("[data-filter-field]");
        const selected = getSelectedFilters();
        if (field.dataset.slug) {
          const index = selected.findIndex(function (item) {
            return item.taxonomy === field.dataset.taxonomy && item.slug === field.dataset.slug;
          });
          if (index >= 0) {
            selected[index] = {
              taxonomy: choice.dataset.taxonomy,
              slug: choice.dataset.slug,
            };
          }
        } else {
          selected.push({
            taxonomy: choice.dataset.taxonomy,
            slug: choice.dataset.slug,
          });
        }
        navigate(selected.slice(0, maxFilters));
      }
    });

    row.addEventListener("input", function (event) {
      const input = event.target.closest("[data-filter-input]");
      if (!input) return;
      const field = input.closest("[data-filter-field]");
      clearTimeout(field.searchTimer);
      field.searchVersion = (field.searchVersion || 0) + 1;
      if (field.request) field.request.abort();

      const query = input.value.trim();
      if (!query) {
        renderDefaults(field);
        return;
      }

      closeDropdowns();
      field.querySelector("[data-filter-suggestions]").classList.add("hide");
      const searchVersion = field.searchVersion;
      field.searchTimer = setTimeout(function () {
        searchTerms(field, query, searchVersion);
      }, 500);
    });

    row.addEventListener("keydown", function (event) {
      const choice = event.target.closest(".listing-filter-dropdown span[data-slug]");
      if (choice && (event.key === "Enter" || event.key === " ")) {
        event.preventDefault();
        choice.click();
      } else if (event.key === "Escape" && activeField) {
        closeField(activeField);
      }
    });

    document.addEventListener("click", function (event) {
      if (activeField && !row.contains(event.target)) closeField(activeField);
    });

    updateAddButton();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeListingFilters, { once: true });
  } else {
    initializeListingFilters();
  }
})();
