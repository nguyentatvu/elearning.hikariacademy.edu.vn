window._ = require('lodash');

import '@popperjs/core';
import jQuery from 'jquery';
import axios from 'axios';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

try {
    window.$ = window.jQuery = jQuery;
} catch (e) {}

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';