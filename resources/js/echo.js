import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'wmcg01pzuuofvcti7fct',
    wsHost: 'ws.merkezim.tr',
    wsPort: 80,
    wssPort: 80,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});
