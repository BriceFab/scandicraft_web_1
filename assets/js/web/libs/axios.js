import axios from 'axios';
import config from '../config/config';
import { toast } from 'react-toastify';

const instance = axios.create({
    baseURL: config.BASE_PATH,
    headers: { 'X-Custom-Header': 'foobar' }
});

// Override timeout default for the library
// Now all requests using this instance will wait 10 seconds before timing out
instance.defaults.timeout = 10000;

// Add a request interceptor
instance.interceptors.request.use(function (config) {
    // Do something before request is sent
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor
instance.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger

    //Si erreur authorisation
    if (error.response.status === 401) {
        toast.error("Vous n'êtes pas autoriser à effectuer cette action !");
    }

    return Promise.reject(error);
});

export default instance;