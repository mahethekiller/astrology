// Modal Component
class Modal {
  constructor(modalId) {
    this.modal = document.getElementById(modalId);
    this.init();
  }

  init() {
    const closeBtn = this.modal?.querySelector(".modal-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => this.close());
    }

    this.modal?.addEventListener("click", (e) => {
      if (e.target === this.modal) {
        this.close();
      }
    });
  }

  open() {
    if (this.modal) {
      this.modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    }
  }

  close() {
    if (this.modal) {
      this.modal.style.display = "none";
      document.body.style.overflow = "";
    }
  }
}

// Tab Component
class Tabs {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    this.init();
  }

  init() {
    const tabBtns = this.container?.querySelectorAll(".tab-btn");
    const tabContents = this.container?.querySelectorAll(".tab-content");

    tabBtns?.forEach((btn) => {
      btn.addEventListener("click", () => {
        const target = btn.getAttribute("data-tab");
        this.switchTab(target, tabBtns, tabContents);
      });
    });
  }

  switchTab(target, tabBtns, tabContents) {
    // Update buttons
    tabBtns?.forEach((btn) => {
      btn.classList.toggle("active", btn.getAttribute("data-tab") === target);
    });

    // Update contents
    tabContents?.forEach((content) => {
      content.classList.toggle("active", content.id === target);
    });
  }
}

// Form Validation
class FormValidator {
  constructor(formId) {
    this.form = document.getElementById(formId);
    this.init();
  }

  init() {
    this.form?.addEventListener("submit", (e) => {
      if (!this.validate()) {
        e.preventDefault();
      }
    });
  }

  validate() {
    let isValid = true;
    const inputs = this.form?.querySelectorAll("[data-validate]");

    inputs?.forEach((input) => {
      const value = input.value.trim();
      const type = input.getAttribute("data-validate");

      if (type === "email" && !this.isValidEmail(value)) {
        this.showError(input, "Please enter a valid email address");
        isValid = false;
      } else if (type === "required" && !value) {
        this.showError(input, "This field is required");
        isValid = false;
      } else {
        this.clearError(input);
      }
    });

    return isValid;
  }

  isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  showError(input, message) {
    this.clearError(input);
    input.style.borderColor = "#f94144";

    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message";
    errorDiv.style.cssText = `
            color: #f94144;
            font-size: 12px;
            margin-top: 5px;
        `;
    errorDiv.textContent = message;

    input.parentNode.appendChild(errorDiv);
  }

  clearError(input) {
    input.style.borderColor = "";
    const errorMsg = input.parentNode.querySelector(".error-message");
    if (errorMsg) {
      errorMsg.remove();
    }
  }
}

// Initialize components
document.addEventListener("DOMContentLoaded", function () {
  // Initialize modals
  const modals = document.querySelectorAll(".modal-custom");
  modals.forEach((modal) => {
    new Modal(modal.id);
  });

  // Initialize tabs
  const tabContainers = document.querySelectorAll(".tabs-container");
  tabContainers.forEach((container) => {
    new Tabs(container.id);
  });

  // Initialize form validators
  const forms = document.querySelectorAll("form[data-validate]");
  forms.forEach((form) => {
    new FormValidator(form.id);
  });

  // Demo progress bars animation
  const progressBars = document.querySelectorAll(".progress-bar-custom");
  progressBars.forEach((bar) => {
    const width = bar.style.width;
    bar.style.width = "0";
    setTimeout(() => {
      bar.style.width = width;
    }, 300);
  });
});
