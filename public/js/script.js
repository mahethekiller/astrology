// Enhanced Theme Management
class ThemeManager {
  constructor() {
    this.currentTheme = localStorage.getItem("theme") || "light";
    this.init();
  }

  init() {
    this.applyTheme(this.currentTheme);
    this.bindEvents();
    this.addTransitionClass();
  }

  applyTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
    this.updateThemeToggle(theme);
    this.fixDataTables(theme);
  }

  updateThemeToggle(theme) {
    const toggleBtn = document.getElementById("themeToggle");
    if (toggleBtn) {
      toggleBtn.innerHTML =
        theme === "dark"
          ? '<i class="bi bi-sun"></i>'
          : '<i class="bi bi-moon"></i>';
      toggleBtn.setAttribute(
        "title",
        theme === "dark" ? "Switch to Light Mode" : "Switch to Dark Mode"
      );
    }
  }

  toggleTheme() {
    this.currentTheme = this.currentTheme === "light" ? "dark" : "light";
    this.applyTheme(this.currentTheme);
  }

  addTransitionClass() {
    // Add smooth transition after initial load
    setTimeout(() => {
      document.documentElement.classList.add("theme-transition");
    }, 100);
  }

  fixDataTables(theme) {
    // Reinitialize DataTables if they exist
    if (typeof $.fn.DataTable !== "undefined") {
      $(".dataTable").DataTable().draw();
    }
  }

  bindEvents() {
    const toggleBtn = document.getElementById("themeToggle");
    if (toggleBtn) {
      toggleBtn.addEventListener("click", () => this.toggleTheme());
    }

    // Listen for system theme changes
    if (window.matchMedia) {
      window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", (e) => {
          if (!localStorage.getItem("theme")) {
            this.currentTheme = e.matches ? "dark" : "light";
            this.applyTheme(this.currentTheme);
          }
        });
    }
  }
}

// Mobile Menu
class MobileMenu {
  constructor() {
    this.isOpen = false;
    this.bindEvents();
  }

  bindEvents() {
    const menuBtn = document.querySelector(".mobile-menu-btn");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.createElement("div");

    overlay.className = "mobile-overlay";
    overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        `;
    document.body.appendChild(overlay);

    if (menuBtn && sidebar) {
      menuBtn.addEventListener("click", () =>
        this.toggleMenu(sidebar, overlay)
      );
      overlay.addEventListener("click", () => this.closeMenu(sidebar, overlay));
    }
  }

  toggleMenu(sidebar, overlay) {
    this.isOpen = !this.isOpen;
    sidebar.classList.toggle("mobile-open", this.isOpen);
    overlay.style.display = this.isOpen ? "block" : "none";
    document.body.style.overflow = this.isOpen ? "hidden" : "";
  }

  closeMenu(sidebar, overlay) {
    this.isOpen = false;
    sidebar.classList.remove("mobile-open");
    overlay.style.display = "none";
    document.body.style.overflow = "";
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  new ThemeManager();
  new MobileMenu();

  // Set active navigation link
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
  const navLinks = document.querySelectorAll(".sidebar-menu a");

  navLinks.forEach((link) => {
    const linkHref = link.getAttribute("href");
    if (linkHref === currentPage) {
      link.classList.add("active");
    }
  });
});
