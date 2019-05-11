import axios from "axios";
import ls from "local-storage";
import i18n from  "../lang/";

axios.defaults.baseURL = "/api";

axios.interceptors.request.use(function (config) {
    NProgress.start()
    config.headers.Authorization = `Bearer ${ls.get("access_token")}`;
    return config;
});

axios.interceptors.response.use(function (response) {
        NProgress.done();
        return response;
    }, function (error) {
        NProgress.done();

        if (error.response.status === 400 || error.response.status === 401) {
            ls.remove("access_token");
            window.onbeforeunload = function() {};
            window.location.reload();
        }

        return Promise.reject(error);
    }
);

export default {
    register({ dispatch, commit }, data) {
        return axios.post('/register', data)
            .then((res) => {
                commit('LOGGED_IN', res.data);

                return dispatch('fetchData')
                    .then((res) => {
                        return res.data;
                    })
            })
            .catch((error) => {
                return error;
            });
    },

    login({ dispatch, commit }, data) {
        return axios.post('/login', data)
            .then((res) => {
                commit('LOGGED_IN', res.data);

                return dispatch('fetchData')
                    .then((res) => {
                        return res.data;
                    })
            })
            .catch((error) => {
                return error;
            });
    },

    logout({ commit }) {
        return axios.post('/logout')
            .then((res) => {
                commit('LOGGED_OUT');
            })
            .catch((error) => {
                return error;
            });
    },

    confirmUserMail({ commit }, data) {
        return axios.get(`/confirm/${data}`)
            .then((res) => {
                commit('SET_SNACKBAR', { text: i18n.t('Your email account has been verified.') });
            })
            .catch((error) => {
                commit('SET_SNACKBAR', { color: 'error', text: i18n.t('Your activation code is invalid or your e-mail address verified before.') });
            });
    },

    sendPasswordResetEmail({ commit }, data) {
        return axios.post('/password/email', data)
            .then((res) => {
                commit('SET_SNACKBAR', { text: res.data });
            })
            .catch((error) => {
                commit('SET_SNACKBAR', { color: 'error', text: error.data });
            });
    },

    passwordReset({ commit }, data) {
        return axios.post('/password/reset', data)
            .then((res) => {
                commit('SET_SNACKBAR', { text: res.data });
            })
            .catch((error) => {
                commit('SET_SNACKBAR', { color: 'error', text: error.data });
            });
    },

    fetchData({ commit }) {
        return axios.get('/data')
            .then((res) => {
                commit('SET_DATA', res.data);
                //portfolios length 0 ise addPortfolio ya yönlendir.

                return res.data;
            })
            .catch((error) => {
                return error;
            });
    },

    createPortfolio({ commit }, data) {
        return axios.post('/portfolio', data)
            .then((res) => {
                commit('ADD_PORTFOLIO', res.data);
            })
            .catch((error) => {
                return error;
            });
    },

    updatePortfolio({ commit, getters }, data) {
        return axios.put(`/portfolio/${data.id}`, data)
            .then((res) => {
                const index = getters.getPortfolioIndexById(data.id);
                commit('UPDATE_PORTFOLIO', { index, data });

                return res;
            })
            .catch((error) => {
                return error;
            });
    },

    destroyPortfolio({ commit, getters }, portfolioId) {
        return axios.delete(`/portfolio/${portfolioId}`)
            .then((res) => {
                const index = getters.getPortfolioIndexById(portfolioId);
                commit('DESTROY_PORTFOLIO', index);

                return res;
            })
            .catch((error) => {
                return error;
            });
    },

    createTransaction({ commit, getters }, data) {
        return axios.post('/transaction', data)
            .then((res) => {
                const index = getters.getPortfolioIndexById(res.data.id);
                const data = res.data;
                commit('UPDATE_PORTFOLIO', { index, data });        
            })
            .catch((error) => {
                return error;
            });
    },

    searchSymbol({ commit }, data) {
        return axios.get('/symbol/search', {
                params: {
                    q: data
                }
            });
    },

    addShare({ commit, getters }, data) {
        return axios.post('/share', data)
            .then((res) => {
                const index = getters.getPortfolioIndexById(res.data.portfolio_id);
                const data = res.data;
                commit('ADD_SHARE', { index, data });        
            })
            .catch((error) => {
                return error;
            });
    },

    destroyShare({ commit, getters }, data) {
        return axios.delete(`/share/${data.id}`)
            .then((res) => {
                const portfolioIndex = getters.getPortfolioIndexById(data.portfolio_id);
                const index = getters.getShareIndexById(data.portfolio_id, data.id);
                commit('DESTROY_SHARE', { portfolioIndex, index });    
            })
    },

    fetchByShare({ commit }, shareId) {
        return axios.get(`/share/${shareId}/transactions`)
            .then((res) => {
                return res.data
            });
    },
}
