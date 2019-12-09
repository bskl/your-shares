import axios from "axios";
import router from "../router";
import i18n from  "../lang/";

const http = axios.create({
  baseURL: '/api',
});

http.interceptors.request.use(function (config) {
  NProgress.start()
  config.headers.Authorization = `Bearer ${JSON.parse(localStorage.getItem('access_token'))}`;
  return config;
});

http.interceptors.response.use(function (response) {
  NProgress.done();
  return response;
}, function (error) {
  NProgress.done();

  if (error.response.status === 400 || error.response.status === 401) {
    localStorage.removeItem('access_token');
    router.push({ name: 'Login' });
  } else if (error.response.status === 403) {
    router.push({ name: 'Forbidden' });
  } else if (error.response.status === 404) {
    router.push({ name: 'NotFound' });
  }

  return Promise.reject(error);
});

export default {
  toggleLoading({ commit }) {
    commit('TOGGLE_LOADING');
  },

  setSnackbar({ commit }, data) {
    commit('SET_SNACKBAR', data);
  },

  toggleNavDrawer({ commit }) {
    commit('TOGGLE_NAV_DRAWER');
  },

  register({ dispatch, commit }, data) {
    return http.post('/register', data)
      .then((res) => {
        commit('LOGGED_IN', res.data);

        return res.data;
      });
  },

  login({ commit }, data) {
    return http.post('/login', data)
      .then((res) => {
        commit('LOGGED_IN', res.data);

        return res.data;
      });
  },

  checkAuth({ commit }, data) {
    return new Promise((resolve, reject) => {
      commit('CHECK_AUTH', data);

      resolve();
    });
  },

  checkState({ dispatch, getters }) {
    return new Promise((resolve, reject) => {
      if (getters.portfoliosCount == 0) {
        return dispatch('fetchData')
          .then(() => resolve());
      } else {
        resolve();
      }
    });
  },

  setLocale({ commit }, locale) {
    return http.get(`/locale/${locale}`)
      .then((res) => {
        commit('SET_LOCALE', locale);

        return res.data;
      })
      .catch((error) => {
        commit('SET_SNACKBAR', { color: 'error', text: i18n.t('An error occured when the locale updated. Please try again later.') });

        return error;
      });
  },

  logout({ commit }) {
    return http.post('/logout')
      .then((res) => {
        commit('LOGGED_OUT');
        commit('TOGGLE_NAV_DRAWER');

        return res.data;
    });
  },

  confirmUserMail({ commit }, data) {
    return http.get(`/confirm/${data}`)
      .then((res) => {
        commit('SET_SNACKBAR', { text: i18n.t('Your email account has been verified.') });

        return res.data;
      })
      .catch((error) => {
        commit('SET_SNACKBAR', { color: 'error', text: i18n.t('Your activation code is invalid or your e-mail address verified before.') });

        return error;
      });
  },

  sendPasswordResetEmail(_, data) {
    return http.post('/password/email', data)
      .then((res) => {
        return res.data;
      });
  },

  passwordReset(_, data) {
    return http.post('/password/reset', data)
      .then((res) => {
        return res.data;
      });
  },

  fetchData({ commit }) {
    return http.get('/data')
      .then((res) => {
        commit('SET_USER', res.data.user);
        commit('SET_PORTFOLIOS', res.data.portfolios);

        return res.data;
      });
  },

  fetchSymbolsData({ commit }) {
    return http.get('/symbol/data')
      .then((res) => {
        commit('SET_PORTFOLIOS', res.data);

        return res.data;
      });
  },

  createPortfolio({ commit }, data) {
    return http.post('/portfolio', data)
      .then((res) => {
        commit('ADD_PORTFOLIO', res.data);

        return res.data;
      });
  },

  updatePortfolio({ commit, getters }, { id, data }) {
    return http.put(`/portfolio/${id}`, data)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        commit('UPDATE_PORTFOLIO', { index, res });

        return res.data;
      });
  },

  destroyPortfolio({ commit, getters }, id) {
    return http.delete(`/portfolio/${id}`)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        commit('DESTROY_PORTFOLIO', index);

        return res.data;
      });
  },

  fetchPortfolio(_, id) {
    return http.get(`/portfolio/${id}`)
      .then((res) => {
        return res.data;
      });
  },

  createTransaction({ commit, getters }, data) {
    return http.post('/transaction', data)
      .then((res) => {
        const index = getters.getPortfolioIndexById(res.data.id);
        commit('UPDATE_PORTFOLIO', { index, res }); 

        return res.data;
      });
  },

  fetchSymbols(_, data) {
    return http.get('/symbol')
      .then((res) => {
        return res.data;
      });
  },

  addShare({ commit, getters }, data) {
    return http.post('/share', data)
      .then((res) => {
        const index = getters.getPortfolioIndexById(res.data.portfolio_id);
        const data = res.data;
        commit('ADD_SHARE', { index, data });     

        return res.data;
      });
  },

  destroyShare({ commit, getters }, data) {
    return http.delete(`/share/${data.id}`)
      .then((res) => {
        const portfolioIndex = getters.getPortfolioIndexById(data.portfolio_id);
        const index = getters.getShareIndexById(data.portfolio_id, data.id);
        commit('DESTROY_SHARE', { portfolioIndex, index });

        return res.data;
      });
  },

  fetchShare(_, id) {
    return http.get(`/share/${id}`)
      .then((res) => {
        return res.data;
      });
  },

  fetchTransactionsByParams(_, path) {
    return http.get(path)
      .then((res) => {
        return res.data;
      });
  },

  destroyTransaction({ commit, getters }, id) {
    return http.delete(`/transaction/${id}`)
      .then((res) => {
        const index = getters.getPortfolioIndexById(res.data.id);
        commit('UPDATE_PORTFOLIO', { index, res });

        return res.data;
      });
  },
}
