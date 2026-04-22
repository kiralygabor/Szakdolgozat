/**
 * Global Configuration for MiniJobz JavaScript
 */
export const Config = {
    // API Endpoints
    api: {
        cities: '/api/cities',
        settings: '/profile/settings', // Unified endpoint
        notificationsMarkRead: '/notifications/mark-read'
    },

    // UI Constants
    timeouts: {
        debounce: 300,
        dropdown: 150,
        notification: 200,
        errorHide: 800
    },

    // Settings
    autocomplete: {
        minChars: 2
    }
};
