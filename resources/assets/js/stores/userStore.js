import { http } from '../services/http.js';
import { ls } from '../services/ls.js';
import router from '../router/';

export const userStore = {
    state: {
        user: {
            id: 0,
            name: '',
            email: '',
        }
    },

    /**
     * Set the user data.
     *
     * @param {Array.<Object>}  user    The auth user in the system
     * @param {Object}          user    The auth user.
     */
    init(user) {
        this.user = user
    },

    /**
     * Set the current user.
     *
     * @param  {Object} user
     * @return {Object}
     */
    set user(user) {
        this.state.user = user
        return this.state.user
    },

    /**
     * Get the user data.
     *
     * @return {Object}
     */
    get user() {
        return this.state.user
    },

    /**
     * Check is user logged in.
     *
     * @return boolen
     */
    isAuthenticated() {
        const access_token = ls.get('access_token');

        if (!access_token) {
            return false;
        }

        return true;
    },

    /**
     * Log the current user out.
     */
    logout () {
        return new Promise((resolve, reject) => {
            http.post('logout', {}, ({ data }) => {
                ls.remove('access_token')
                window.onbeforeunload = function () {}

                resolve(data)
 
                window.location.reload()
            }, error => reject(error))
        })
    }
}