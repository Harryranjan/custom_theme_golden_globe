/* =============================================================
   GOLDEN GLOBE — MAIN JS
   Single flat file — no build step required
   ============================================================= */

/* ── Navigation ── */
(function () {
  var header = document.querySelector(".site-header");
  var toggle = document.querySelector("[data-nav-toggle]");
  var nav = document.querySelector("[data-nav-menu]");
  var overlay = document.querySelector("[data-nav-overlay]");

  if (!header) return;

  function toggleMenu() {
    var isOpen = toggle.getAttribute("aria-expanded") === "true";
    toggle.setAttribute("aria-expanded", String(!isOpen));
    if (nav) nav.classList.toggle("is-open");
    if (overlay) overlay.classList.toggle("is-visible");
    document.body.classList.toggle("nav-open");
  }

  function closeMenu() {
    if (toggle) toggle.setAttribute("aria-expanded", "false");
    if (nav) nav.classList.remove("is-open");
    if (overlay) overlay.classList.remove("is-visible");
    document.body.classList.remove("nav-open");
  }

  function handleScroll() {
    if (window.scrollY > 50) {
      header.classList.add("is-scrolled");
    } else {
      header.classList.remove("is-scrolled");
    }
  }

  if (toggle) toggle.addEventListener("click", toggleMenu);
  if (overlay) overlay.addEventListener("click", closeMenu);

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeMenu();
  });

  window.addEventListener("scroll", handleScroll, { passive: true });
  handleScroll();
})();

/* ── Accordion ── */
(function () {
  var items = document.querySelectorAll("[data-accordion]");
  if (!items.length) return;

  items.forEach(function (item) {
    var trigger = item.querySelector("[data-accordion-trigger]");
    var panel = item.querySelector("[data-accordion-panel]");
    if (!trigger || !panel) return;

    trigger.addEventListener("click", function () {
      var isOpen = trigger.getAttribute("aria-expanded") === "true";

      // Close all
      items.forEach(function (other) {
        var t = other.querySelector("[data-accordion-trigger]");
        var p = other.querySelector("[data-accordion-panel]");
        if (t) t.setAttribute("aria-expanded", "false");
        if (p) p.setAttribute("hidden", "");
      });

      // Toggle this one
      trigger.setAttribute("aria-expanded", String(!isOpen));
      if (isOpen) {
        panel.setAttribute("hidden", "");
      } else {
        panel.removeAttribute("hidden");
      }
    });
  });
})();

/* ── AJAX Portfolio Filter ── */
(function () {
  var container = document.querySelector(".portfolio-grid");
  if (!container) return;

  var grid = container.querySelector("[data-grid]");
  var filters = container.querySelectorAll("[data-filter]");
  var loadMoreBtn = container.querySelector("[data-load-more]");
  var currentPage = 1;
  var currentCat = "all";
  var isLoading = false;

  function setLoadingState(loading) {
    container.classList.toggle("is-loading", loading);
    if (loadMoreBtn) loadMoreBtn.disabled = loading;
  }

  function updateLoadMore(hasMore) {
    if (!loadMoreBtn) return;
    loadMoreBtn.style.display = hasMore ? "inline-flex" : "none";
  }

  function fetchPosts(replace) {
    if (isLoading) return;
    isLoading = true;
    setLoadingState(true);

    var body = new FormData();
    body.append("action", "filter_portfolio");
    body.append(
      "nonce",
      (window.AgencyTheme && window.AgencyTheme.nonce) || "",
    );
    body.append("category", currentCat);
    body.append("page", String(currentPage));

    fetch(
      (window.AgencyTheme && window.AgencyTheme.ajaxUrl) ||
        "/wp-admin/admin-ajax.php",
      {
        method: "POST",
        body: body,
      },
    )
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success && grid) {
          if (replace) {
            grid.innerHTML = data.data.html;
          } else {
            grid.insertAdjacentHTML("beforeend", data.data.html);
          }
          updateLoadMore(data.data.has_more);
        }
      })
      .catch(function (err) {
        console.error("AjaxFilter error:", err);
      })
      .finally(function () {
        isLoading = false;
        setLoadingState(false);
      });
  }

  filters.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      filters.forEach(function (b) {
        b.setAttribute("aria-pressed", "false");
      });
      btn.setAttribute("aria-pressed", "true");
      currentPage = 1;
      currentCat = btn.dataset.filter;
      fetchPosts(true);
    });
  });

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", function () {
      currentPage++;
      fetchPosts(false);
    });
  }
})();

/* ── FAQ Accordion ── */
(function () {
  var questions = document.querySelectorAll(".faq-item__question");
  if (!questions.length) return;

  questions.forEach(function (btn) {
    btn.addEventListener("click", function () {
      var isOpen = btn.getAttribute("aria-expanded") === "true";
      var panelId = btn.getAttribute("aria-controls");
      var panel = panelId ? document.getElementById(panelId) : null;

      // Close all other items first
      questions.forEach(function (other) {
        if (other === btn) return;
        other.setAttribute("aria-expanded", "false");
        var otherId = other.getAttribute("aria-controls");
        var otherPanel = otherId ? document.getElementById(otherId) : null;
        if (otherPanel) otherPanel.hidden = true;
      });

      // Toggle this one
      btn.setAttribute("aria-expanded", String(!isOpen));
      if (panel) panel.hidden = isOpen;
    });
  });
})();

/* ── Location FAQ Accordion ─────────────────────────────────────────────── */
(function () {
  document.querySelectorAll(".loc-faq__toggle").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var expanded = btn.getAttribute("aria-expanded") === "true";
      var panelId = btn.getAttribute("aria-controls");
      var panel = panelId ? document.getElementById(panelId) : null;

      // Collapse all other items in the same FAQ list
      var list = btn.closest(".loc-faq__list");
      if (list) {
        list.querySelectorAll(".loc-faq__toggle").forEach(function (other) {
          if (other !== btn) {
            other.setAttribute("aria-expanded", "false");
            var otherId = other.getAttribute("aria-controls");
            var otherPanel = otherId ? document.getElementById(otherId) : null;
            if (otherPanel) otherPanel.hidden = true;
          }
        });
      }

      btn.setAttribute("aria-expanded", String(!expanded));
      if (panel) panel.hidden = expanded;
    });
  });
})();

/* ── Contact Form Submission ────────────────────────────────────────── */
(function () {
  var form = document.getElementById("agency-contact-form");
  if (!form) return;

  var responseContainer = document.getElementById("agency-form-response");
  var submitBtn = form.querySelector('button[type="submit"]');
  var btnText = submitBtn ? submitBtn.querySelector(".btn-text") : null;
  var btnLoader = submitBtn ? submitBtn.querySelector(".btn-loader") : null;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Toggle loading state
    if (submitBtn) submitBtn.disabled = true;
    if (btnText) btnText.style.display = "none";
    if (btnLoader) btnLoader.style.display = "inline-block";
    if (responseContainer) {
      responseContainer.style.display = "none";
      responseContainer.className = "agency-form__response";
    }

    var formData = new FormData(form);
    formData.append("action", "submit_contact_form");
    formData.append(
      "nonce",
      form.querySelector('input[name="agency_contact_nonce"]').value,
    );

    fetch(
      (window.AgencyTheme && window.AgencyTheme.ajaxUrl) ||
        "/wp-admin/admin-ajax.php",
      {
        method: "POST",
        body: formData,
      },
    )
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (responseContainer) {
          responseContainer.style.display = "block";
          responseContainer.textContent = (data.data && data.data.message) || data.message || "Message received.";
          responseContainer.classList.add(
            data.success
              ? "agency-form__response--success"
              : "agency-form__response--error",
          );
        }

        if (data.success) {
          form.reset();
        }
      })
      .catch(function (err) {
        console.error("Form submission error:", err);
        if (responseContainer) {
          responseContainer.style.display = "block";
          responseContainer.textContent = "Something went wrong. Please try again.";
          responseContainer.classList.add("agency-form__response--error");
        }
      })
      .finally(function () {
        // Reset button state
        if (submitBtn) submitBtn.disabled = false;
        if (btnText) btnText.style.display = "inline-block";
        if (btnLoader) btnLoader.style.display = "none";
      });
  });
})();

/* ── Scroll Reveal Animations ───────────────────────────────────────── */
(function () {
  var reveals = document.querySelectorAll(".reveal-fade");
  if (!reveals.length) return;

  var observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.1,
  };

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  reveals.forEach(function (el) {
    observer.observe(el);
  });
})();
