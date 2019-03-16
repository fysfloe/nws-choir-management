import axios from 'axios';

// Add a 401 response interceptor
axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (401 === error.response.status) {
        window.location = '/login';
    } else {
        return Promise.reject(error);
    }
});

export default axios;