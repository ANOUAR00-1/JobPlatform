import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Request Interceptor: Attach Token automatically
api.interceptors.request.use(
  (config) => {
    // In a React app, we usually grab this from context or local storage.
    // Given the architecture, grabbing it from localStorage is reliable here.
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.set('Authorization', `Bearer ${token}`);
    }
    
    // CRITICAL: If sending FormData, remove Content-Type header
    // Let the browser set it automatically with the correct boundary
    if (config.data instanceof FormData) {
      delete config.headers['Content-Type'];
    }
    
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response Interceptor: Global 401/403 Handling
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response) {
      const status = error.response.status;
      if (status === 401 || status === 403) {
        // Token is invalid/expired or unauthorized.
        // We trigger a global event here that the AuthContext can listen to for a clean logout,
        // or we simply remove the token and redirect.
        console.warn('Unauthorized access. Redirecting to login...');
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        
        // Dispatch custom event to notify context without strict cyclic dependencies
        window.dispatchEvent(new Event('auth_unauthorized'));
      }
    }
    return Promise.reject(error);
  }
);

export default api;
