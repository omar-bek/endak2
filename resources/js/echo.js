import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',  // مهم: استخدام Pusher وليس Reverb
    key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY || 'e91ff80f1a87987e5a08',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER || 'eu',
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
            'Accept': 'application/json'
        }
    },
    enabledTransports: ['ws', 'wss'],
});
