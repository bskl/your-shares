import axios from "axios";
import NProgress from "nprogress";
import router from "../router";
import { parseErrors } from '../utilities/helpers.js';

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
        commit('SET_SNACKBAR', { text: res.data.data });

        return res.data;
      })
      .catch((error) => {
        commit('SET_SNACKBAR', { color: 'error', text: parseErrors(error) });

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
    return http.get('/symbols/data')
      .then((res) => {
        commit('SET_PORTFOLIOS', res.data.data);

        return res.data;
      });
  },

  createPortfolio({ commit }, data) {
    return http.post('/portfolios', data)
      .then((res) => {
        commit('ADD_PORTFOLIO', res.data.data);

        return res.data;
      });
  },

  updatePortfolio({ commit, getters }, { id, form}) {
    return http.put(`/portfolios/${id}`, form)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        const data = res.data.data;
        commit('UPDATE_PORTFOLIO', { index, data });

        return res.data;
      });
  },

  destroyPortfolio({ commit, getters }, id) {
    return http.delete(`/portfolios/${id}`)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        commit('DESTROY_PORTFOLIO', index);

        return res.data;
      });
  },

  fetchPortfolio(_, id) {
    return http.get(`/portfolios/${id}`)
      .then((res) => {
        return res.data;
      });
  },

  createTransaction({ commit, getters }, form) {
    return http.post('/transactions', form)
      .then((res) => {
        const data = res.data.data;
        const index = getters.getPortfolioIndexById(data.id);
        commit('UPDATE_PORTFOLIO', { index, data }); 

        return res.data;
      });
  },

  fetchSymbols() {
    return http.get('/symbols')
      .then((res) => {
        return res.data;
      });
  },

  addShare({ commit, getters }, form) {
    return http.post('/shares', form)
      .then((res) => {
        const index = getters.getPortfolioIndexById(form.portfolio_id);
        const data = res.data.data;
        commit('ADD_SHARE', { index, data });     

        return res.data;
      });
  },

  destroyShare({ commit, getters }, data) {
    return http.delete(`/shares/${data.id}`)
      .then((res) => {
        const portfolioIndex = getters.getPortfolioIndexById(data.portfolio_id);
        const index = getters.getShareIndexById(data.portfolio_id, data.id);
        commit('DESTROY_SHARE', { portfolioIndex, index });

        return res.data;
      });
  },

  fetchShare(_, id) {
    return http.get(`/shares/${id}`)
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
    return http.delete(`/transactions/${id}`)
      .then((res) => {
        const data = res.data.data;
        const index = getters.getPortfolioIndexById(data.id);
        commit('UPDATE_PORTFOLIO', { index, data });

        return res.data;
      });
  },
}
