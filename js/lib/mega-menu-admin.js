(function () {
  const editor = document.querySelector(".mongabay-mega-menu-editor");
  const template = document.getElementById("mongabay-mega-menu-item-template");
  if (!editor || !template) return;

  function syncTitleMode(item, titleField) {
    const isTitle = titleField.checked;
    item.classList.toggle("is-title-mode", isTitle);
    item.querySelectorAll(":scope > .mongabay-mega-menu-editor-fields [data-title-incompatible] [data-field]").forEach(
      function (field) {
        if (isTitle) {
          field.value = field.dataset.field === "image_row" ? "0" : "";
        } else if (field.dataset.field === "image_span" && !field.value) {
          field.value = "6";
        }
        field.disabled = isTitle;
      },
    );
    const spanControl = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-image-span-control] select");
    const rowControl = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-image-row-control] select");
    if (spanControl) spanControl.disabled = isTitle || item.dataset.panelItem !== "true";
    if (rowControl) rowControl.disabled = isTitle || item.dataset.panelItem !== "true";
  }

  function updateListNames(list) {
    const listPath = list.dataset.path;
    Array.from(list.children).forEach(function (item, index) {
      const itemPath = listPath + "[" + index + "]";
      item.classList.toggle("is-top-level", listPath === "menu");
      const isPanelItem = listPath !== "menu" && (listPath.match(/\[children\]/g) || []).length === 1;
      item.dataset.panelItem = String(isPanelItem);
      const titleControl = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-title-toggle-control]");
      const spanControl = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-image-span-control]");
      const rowControl = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-image-row-control]");
      const titleField = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-field='is_title']");
      const labelField = item.querySelector(":scope > .mongabay-mega-menu-editor-fields [data-field='label']");
      const summary = item.querySelector(":scope > .mongabay-mega-menu-editor-heading [data-item-summary]");
      const collapseButton = item.querySelector(":scope > .mongabay-mega-menu-editor-heading [data-mega-menu-action='collapse']");
      if (titleControl && listPath === "menu") {
        titleControl.style.display = "none";
        titleControl.querySelector("input").disabled = true;
      }
      if (spanControl) {
        spanControl.style.display = isPanelItem ? "" : "none";
      }
      if (rowControl) {
        rowControl.style.display = isPanelItem ? "" : "none";
      }
      if (titleField && !titleField.dataset.modeReady) {
        titleField.addEventListener("change", function () {
          syncTitleMode(item, titleField);
        });
        titleField.dataset.modeReady = "1";
      }
      if (titleField) syncTitleMode(item, titleField);
      if (labelField && summary && !labelField.dataset.summaryReady) {
        summary.textContent = labelField.value || summary.dataset.emptyLabel;
        labelField.addEventListener("input", function () {
          summary.textContent = labelField.value || summary.dataset.emptyLabel;
        });
        labelField.dataset.summaryReady = "1";
      }
      if (collapseButton) {
        const isCollapsed = collapseButton.getAttribute("aria-expanded") === "false";
        const label = isCollapsed ? collapseButton.dataset.expandLabel : collapseButton.dataset.collapseLabel;
        const icon = collapseButton.querySelector(".dashicons");
        collapseButton.setAttribute("aria-label", label);
        collapseButton.title = label;
        if (icon) {
          icon.classList.toggle(collapseButton.dataset.collapseIcon, !isCollapsed);
          icon.classList.toggle(collapseButton.dataset.expandIcon, isCollapsed);
        }
      }
      item.querySelectorAll(":scope > .mongabay-mega-menu-editor-fields [data-field]").forEach(
        function (field) {
          field.name = itemPath + "[" + field.dataset.field + "]";
        },
      );

      const children = item.querySelector(":scope > .mongabay-mega-menu-editor-children > .mongabay-mega-menu-editor-list");
      if (children) {
        children.dataset.path = itemPath + "[children]";
        updateListNames(children);
      }
    });
  }

  function addItem(list) {
    list.appendChild(template.content.firstElementChild.cloneNode(true));
    updateListNames(list);
  }

  function duplicateItem(item, list) {
    const copy = item.cloneNode(true);
    list.insertBefore(copy, item.nextSibling);
    updateListNames(list);
  }

  let imageFrame;
  let activeImageField;

  function setImage(field, url) {
    const input = field.querySelector("[data-field='image']");
    const preview = field.querySelector(".mongabay-mega-menu-editor-image-preview");
    if (!input || !preview) return;
    input.value = url;
    if (url) preview.src = url;
    else preview.removeAttribute("src");
    preview.hidden = !url;
  }

  editor.addEventListener("click", function (event) {
    const button = event.target.closest("[data-mega-menu-action]");
    if (!button) return;

    const action = button.dataset.megaMenuAction;
    if (action === "select-image") {
      activeImageField = button.closest(".mongabay-mega-menu-editor-image-field");
      if (!activeImageField) return;

      if (!imageFrame) {
        imageFrame = wp.media({
          title: button.dataset.mediaTitle,
          button: { text: button.dataset.mediaButton },
          library: { type: "image" },
          multiple: false,
        });
        imageFrame.on("select", function () {
          const attachment = imageFrame.state().get("selection").first().toJSON();
          const imageUrl = attachment.sizes && attachment.sizes.medium
            ? attachment.sizes.medium.url
            : attachment.url;
          if (imageUrl && activeImageField) setImage(activeImageField, imageUrl);
        });
      }

      imageFrame.open();
      return;
    }

    if (action === "clear-image") {
      const imageField = button.closest(".mongabay-mega-menu-editor-image-field");
      if (imageField) setImage(imageField, "");
      return;
    }

    if (action === "add") {
      addItem(editor.querySelector(":scope > .mongabay-mega-menu-editor-list"));
      return;
    }

    const item = button.closest(".mongabay-mega-menu-editor-item");
    const list = item && item.parentElement;
    if (!item || !list) return;

    if (action === "add-child") {
      addItem(item.querySelector(":scope > .mongabay-mega-menu-editor-children > .mongabay-mega-menu-editor-list"));
    } else if (action === "duplicate") {
      duplicateItem(item, list);
    } else if (action === "collapse" && item.classList.contains("is-top-level")) {
      const collapsed = item.classList.toggle("is-collapsed");
      button.setAttribute("aria-expanded", String(!collapsed));
      const label = collapsed ? button.dataset.expandLabel : button.dataset.collapseLabel;
      button.setAttribute("aria-label", label);
      button.title = label;
      const icon = button.querySelector(".dashicons");
      if (icon) {
        icon.classList.toggle(button.dataset.collapseIcon, !collapsed);
        icon.classList.toggle(button.dataset.expandIcon, collapsed);
      }
    } else if (action === "remove") {
      item.remove();
      updateListNames(list);
    } else if (action === "up" && item.previousElementSibling) {
      list.insertBefore(item, item.previousElementSibling);
      updateListNames(list);
    } else if (action === "down" && item.nextElementSibling) {
      list.insertBefore(item.nextElementSibling, item);
      updateListNames(list);
    }
  });

  editor.querySelectorAll(".mongabay-mega-menu-editor-list").forEach(updateListNames);
})();
