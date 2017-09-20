import { http } from '../services/http.js';
import { userStore } from './userStore.js';
import { portfolioStore } from './portfolioStore.js';
import { symbolStore } from './symbolStore.js';

export const sharedStore = {
    state: {
        user: null,
        portfolios: [],
        symbols: [],
    },

    /**
     * Get the all data.
     *
     * @return {Array.<Object>}
     */
    getData() {
        NProgress.start();

        return new Promise((resolve, reject) => {
            http.get('/data', ({ data }) => {
                _.assign(this.state, data)

                userStore.init(this.state.user)
                portfolioStore.init(this.state.portfolios)
                symbolStore.init(this.state.symbols)

                resolve(this.state)
            }, error => reject(error))
        })
    }
}