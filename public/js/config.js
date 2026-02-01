// API Configuration
const API_CONFIG = {
    BASE_URL: 'http://127.0.0.1:8000',
    API_PREFIX: '/api',
    
    // API Endpoints - Phase 1: Auth Only
    AUTH: {
        LOGIN: '/auth/login',
        LOGOUT: '/auth/logout',
        USER: '/auth/user'
    }
};

// Helper function to build full API URL
function apiUrl(endpoint) {
    return API_CONFIG.BASE_URL + API_CONFIG.API_PREFIX + endpoint;
}