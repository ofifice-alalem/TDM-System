// Theme Management
const ThemeManager = {
    init() {
        this.loadTheme();
        this.bindEvents();
    },

    loadTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        this.updateToggleIcon(savedTheme);
    },

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        this.updateToggleIcon(newTheme);
    },

    updateToggleIcon(theme) {
        const toggleBtn = document.querySelector('.theme-toggle');
        if (toggleBtn) {
            toggleBtn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
        }
    },

    bindEvents() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('theme-toggle')) {
                this.toggleTheme();
            }
        });
    }
};

// Quick Login Functions
function quickLogin(username, password) {
    document.getElementById('username').value = username;
    document.getElementById('password').value = password;
    document.getElementById('loginForm').dispatchEvent(new Event('submit'));
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
});