window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import ApexCharts from 'apexcharts';

window.io = require('socket.io-client');

/**
 * Echo Server connection:
 * - Development: connects directly to Echo Server on port 8443 (exposed in docker-compose.yml)
 * - Production: Nginx proxies /socket.io/ to the internal Echo Server (port 8443 NOT exposed)
 *   so we use the same origin (port 443) and let Nginx handle the proxy.
 */
const echoHost = process.env.MIX_ECHO_HOST || (
    window.location.protocol === 'https:'
        ? window.location.origin
        : window.location.hostname + ':8443'
);

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: echoHost,
    transports: ['websocket']
});

window.ApexCharts = ApexCharts;
