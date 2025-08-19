import axios from "axios";
window.axios = axios;

import Echo from "laravel-echo";
import { io } from "socket.io-client";
window.io = io;

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":6001",
});

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
