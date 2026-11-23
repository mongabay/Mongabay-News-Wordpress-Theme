(function () {
  document.querySelectorAll(".mongabay-mega-menu").forEach(function (menu) {
    const triggers = Array.from(menu.querySelectorAll(".mongabay-mega-menu__trigger[aria-controls]"));
    const settingsToggle = menu.querySelector("[data-settings-toggle]");
    const settingsPanel = settingsToggle
      ? document.getElementById(settingsToggle.getAttribute("aria-controls"))
      : null;
    const menuToggle = menu.querySelector("#secondary-menu");
    const navbarPanel = menuToggle
      ? document.getElementById(menuToggle.getAttribute("aria-controls"))
      : null;
    const themeChoices = Array.from(menu.querySelectorAll("[data-theme-choice]"));
    const accessibilityChoices = Array.from(menu.querySelectorAll("[data-accessibility-choice]"));
    const themePreferenceKey = "mongabay-theme-preference";
    const accessibilityPreferenceKey = "mongabay-accessibility-preferences";
    const accessibilityClasses = {
      monochrome: "mongabay-accessibility-monochrome",
      "mute-media": "mongabay-accessibility-mute-media",
      "enlarge-buttons": "mongabay-accessibility-enlarge-buttons",
    };

    function closeAll() {
      triggers.forEach(function (trigger) {
        trigger.setAttribute("aria-expanded", "false");
        const panel = document.getElementById(trigger.getAttribute("aria-controls"));
        if (panel) panel.hidden = true;
      });
      if (settingsToggle && settingsPanel) {
        settingsToggle.setAttribute("aria-expanded", "false");
        settingsPanel.hidden = true;
      }
      if (menuToggle && navbarPanel) {
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.setAttribute("aria-label", menuToggle.dataset.openLabel);
        navbarPanel.hidden = true;
      }
      document.body.classList.remove("mongabay-mega-menu-open");
    }

    const mobileMenu = window.matchMedia("(max-width: 600px)");
    if (mobileMenu.addEventListener) {
      mobileMenu.addEventListener("change", closeAll);
    } else {
      mobileMenu.addListener(closeAll);
    }

    triggers.forEach(function (trigger) {
      trigger.addEventListener("click", function () {
        const opening = trigger.getAttribute("aria-expanded") !== "true";
        closeAll();
        if (opening) {
          trigger.setAttribute("aria-expanded", "true");
          const panel = document.getElementById(trigger.getAttribute("aria-controls"));
          if (panel) {
            panel.hidden = false;
            document.body.classList.add("mongabay-mega-menu-open");
          }
        }
      });
    });

    function applyTheme(theme) {
      const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
      const isDark = theme === "dark" || (theme === "system" && prefersDark);
      document.body.classList.toggle("dark-mode", isDark);
      document.body.classList.toggle("light-mode", !isDark);
      document.querySelectorAll("a.theme-light").forEach(function (element) {
        element.style.display = isDark ? "none" : "block";
      });
      document.querySelectorAll("a.theme-dark").forEach(function (element) {
        element.style.display = isDark ? "block" : "none";
      });
      localStorage.setItem("mongabay-theme", isDark ? "dark-mode" : "light-mode");
      themeChoices.forEach(function (button) {
        button.setAttribute("aria-pressed", String(button.dataset.themeChoice === theme));
      });
    }

    let themePreference = localStorage.getItem(themePreferenceKey);
    if (!["light", "dark", "system"].includes(themePreference)) {
      themePreference = localStorage.getItem("mongabay-theme") === "dark-mode" ? "dark" : "light";
    }
    applyTheme(themePreference);
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", function () {
        applyTheme(themePreference);
      }, { once: true });
    }

    themeChoices.forEach(function (button) {
      button.addEventListener("click", function () {
        themePreference = button.dataset.themeChoice;
        localStorage.setItem(themePreferenceKey, themePreference);
        applyTheme(themePreference);
      });
    });

    const colorScheme = window.matchMedia("(prefers-color-scheme: dark)");
    const onColorSchemeChange = function () {
      if (themePreference === "system") applyTheme("system");
    };
    if (colorScheme.addEventListener) {
      colorScheme.addEventListener("change", onColorSchemeChange);
    } else {
      colorScheme.addListener(onColorSchemeChange);
    }

    function loadAccessibilityPreferences() {
      try {
        const saved = JSON.parse(localStorage.getItem(accessibilityPreferenceKey) || "{}");
        accessibilityChoices.forEach(function (input) {
          const key = input.dataset.accessibilityChoice;
          if (!Object.prototype.hasOwnProperty.call(accessibilityClasses, key)) return;
          const isEnabled = Boolean(saved[key]);
          input.setAttribute("aria-pressed", String(isEnabled));
          document.body.classList.toggle(accessibilityClasses[key], isEnabled);
        });
      } catch (error) {
        console.error("[Mega menu] Could not load accessibility preferences", error);
      }
    }

    const originalMediaMuted = new WeakMap();
    function muteMediaElement(media) {
      if (!originalMediaMuted.has(media)) {
        originalMediaMuted.set(media, media.muted);
      }
      media.muted = true;
    }

    function applyMutedMedia(muted) {
      document.querySelectorAll("audio, video").forEach(function (media) {
        if (muted) {
          muteMediaElement(media);
        } else if (originalMediaMuted.has(media)) {
          media.muted = originalMediaMuted.get(media);
          originalMediaMuted.delete(media);
        }
      });
    }

    loadAccessibilityPreferences();
    accessibilityChoices.forEach(function (button) {
      button.addEventListener("click", function () {
        const isEnabled = button.getAttribute("aria-pressed") !== "true";
        button.setAttribute("aria-pressed", String(isEnabled));
        const saved = {};
        accessibilityChoices.forEach(function (choice) {
          const choiceEnabled = choice.getAttribute("aria-pressed") === "true";
          saved[choice.dataset.accessibilityChoice] = choiceEnabled;
          const accessibilityClass = accessibilityClasses[choice.dataset.accessibilityChoice];
          if (accessibilityClass) {
            document.body.classList.toggle(accessibilityClass, choiceEnabled);
          }
        });
        if (button.dataset.accessibilityChoice === "mute-media") {
          applyMutedMedia(isEnabled);
        }
        try {
          localStorage.setItem(accessibilityPreferenceKey, JSON.stringify(saved));
        } catch (error) {
          console.error("[Mega menu] Could not save accessibility preferences", error);
        }
      });
    });

    const muteMediaChoice = menu.querySelector('[data-accessibility-choice="mute-media"]');
    if (muteMediaChoice && muteMediaChoice.getAttribute("aria-pressed") === "true") applyMutedMedia(true);
    document.addEventListener("play", function (event) {
      if (
        muteMediaChoice &&
        muteMediaChoice.getAttribute("aria-pressed") === "true" &&
        event.target instanceof HTMLMediaElement
      ) {
        muteMediaElement(event.target);
      }
    }, true);
    document.addEventListener("volumechange", function (event) {
      if (
        muteMediaChoice &&
        muteMediaChoice.getAttribute("aria-pressed") === "true" &&
        event.target instanceof HTMLMediaElement &&
        !event.target.muted
      ) {
        muteMediaElement(event.target);
      }
    }, true);

    if (settingsToggle && settingsPanel) {
      settingsToggle.addEventListener("click", function () {
        const opening = settingsToggle.getAttribute("aria-expanded") !== "true";
        closeAll();
        if (opening) {
          settingsToggle.setAttribute("aria-expanded", "true");
          settingsPanel.hidden = false;
          document.body.classList.add("mongabay-mega-menu-open");
        }
      });
    }

    if (menuToggle && navbarPanel) {
      menu.addEventListener("click", function (event) {
        if (event.target !== menuToggle && !menuToggle.contains(event.target)) return;
        event.preventDefault();
        event.stopPropagation();
        const opening = menuToggle.getAttribute("aria-expanded") !== "true";
        closeAll();
        if (opening) {
          menuToggle.setAttribute("aria-expanded", "true");
          menuToggle.setAttribute("aria-label", menuToggle.dataset.closeLabel);
          navbarPanel.hidden = false;
          document.body.classList.add("mongabay-mega-menu-open");
        }
      }, true);
    }

    menu.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        const expandedTrigger = triggers.find(function (trigger) {
          return trigger.getAttribute("aria-expanded") === "true";
        });
        const settingsWereOpen =
          settingsToggle && settingsToggle.getAttribute("aria-expanded") === "true";
        const navbarPanelWasOpen = menuToggle && menuToggle.getAttribute("aria-expanded") === "true";
        closeAll();
        if (expandedTrigger) expandedTrigger.focus();
        else if (settingsWereOpen) settingsToggle.focus();
        else if (navbarPanelWasOpen) menuToggle.focus();
      }
    });

    document.addEventListener("click", function (event) {
      if (!menu.contains(event.target)) {
        closeAll();
      }
    });
  });
})();
