window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');

    // require('bootstrap-sass');
} catch (e) {
    console.log('Cannot load jQuery')
}


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = window.baseUrl;
window.axios.interceptors.response.use(function (response) {
    // Do something with response data
    return response;
}, function (error, a, b) {
    if(error.response.status === 401) {
        window.location = '/login'
    }
    // Do something with response error
    return Promise.reject(error);
});

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
