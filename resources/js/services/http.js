import { ls } from './ls.js';

/**
 * Responsible for all HTTP requests.
 */
export const http = {
    request (method, url, data, successCb = null, errorCb = null) {
        axios.request({
            url,
            data,
            method: method.toLowerCase()
        }).then(successCb).catch(errorCb)
    },

    get (url, successCb = null, errorCb = null) {
        return this.request('get', url, {}, successCb, errorCb)
    },

    post (url, data, successCb = null, errorCb = null) {
        return this.request('post', url, data, successCb, errorCb)
    },

    put (url, data, successCb = null, errorCb = null) {
        return this.request('put', url, data, successCb, errorCb)
    },

    delete (url, data = {}, successCb = null, errorCb = null) {
        return this.request('delete', url, data, successCb, errorCb)
    },

    /**
     * Init the service.
     */
    init () {
        axios.defaults.baseURL = '/api'

        // Intercept the request to make sure the access token is injected into the header.
        axios.interceptors.request.use(config => {
            config.headers.Authorization = `Bearer ${ls.get('access_token')}`
            return config
        })

        // Intercept the response and…
        axios.interceptors.response.use(response => {
            NProgress.done()
      
            const access_token = response.headers['Authorization'] || response.data['access_token']
            if (access_token) {
                ls.set('access_token', access_token)
            }

            return response
        }, error => {
            NProgress.done()
      
            // Also, if we receive a Bad Request / Unauthorized error
            if (error.response.status === 400 || error.response.status === 401) {
                ls.remove('access_token')
                window.onbeforeunload = function () {}
                window.location.reload()
            }

            return Promise.reject(error)
        })
    }
}