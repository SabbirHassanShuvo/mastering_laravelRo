// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https' || (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'wss',
//     enabledTransports: ['ws', 'wss'],
// });
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 443, // ← 80 ছিল, 443 হবে
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: true, // ← হার্ডকোড true
    enabledTransports: ["ws", "wss"],
    disableStats: true, // ← NEW: pusher ping বন্ধ
    activityTimeout: 120000, // ← NEW: 2 মিনিট idle ok
    pongTimeout: 30000, // ← NEW: 30s pong wait
});
