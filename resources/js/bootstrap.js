import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '7d864599959ff024ffd5',
    cluster: 'eu',
    forceTLS: true
})

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
