// Auth Page Specific JavaScript
class AuthThemeManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.init();
    }

    init() {
        this.bindEvents();
        this.addTransitionClass();
    }

    async toggleTheme() {
        try {
            const response = await fetch('/theme/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                document.documentElement.setAttribute('data-theme', data.theme);
                this.updateThemeToggle(data.theme);
            } else {
                console.error('Failed to toggle theme');
                this.toggleThemeLocally();
            }
        } catch (error) {
            console.error('Error toggling theme:', error);
            this.toggleThemeLocally();
        }
    }

    toggleThemeLocally() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        this.updateThemeToggle(newTheme);
        localStorage.setItem('theme', newTheme);
    }

    updateThemeToggle(theme) {
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.innerHTML = theme === 'dark' ?
                '<i class="bi bi-sun"></i>' :
                '<i class="bi bi-moon"></i>';
            toggleBtn.setAttribute('title', theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode');
        }
    }

    addTransitionClass() {
        setTimeout(() => {
            document.documentElement.classList.add('theme-transition');
        }, 100);
    }

    bindEvents() {
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleTheme());
        }

        // Add loading state to form submission
        const authForm = document.querySelector('.auth-form');
        if (authForm) {
            authForm.addEventListener('submit', (e) => {
                const submitBtn = authForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Signing In...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Social button handlers
        this.bindSocialButtons();
    }

    bindSocialButtons() {
        // Google login
        const googleBtn = document.querySelector('.social-btn.google');
        if (googleBtn) {
            googleBtn.addEventListener('click', () => {
                alert('Google authentication would be implemented here.');
            });
        }

        // GitHub login
        const githubBtn = document.querySelector('.social-btn.github');
        if (githubBtn) {
            githubBtn.addEventListener('click', () => {
                alert('GitHub authentication would be implemented here.');
            });
        }
    }
}

// Password visibility toggle (can be added later)
class PasswordToggle {
    constructor() {
        this.init();
    }

    init() {
        // Could add password visibility toggle here
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new AuthThemeManager();
    new PasswordToggle();

    // Add floating animation to background elements
    const bgCircles = document.querySelectorAll('.bg-circle');
    bgCircles.forEach((circle, index) => {
        circle.style.animationDelay = `${index * 2}s`;
    });
});
