import axios from "axios";
import { has, upperFirst, trimEnd } from 'lodash';
import { parseErrorMessage, parseSuccessMessage } from '../utilities/helpers.js';

const http = axios.create({
  baseURL: '/api',
});

http.interceptors.request.use(function (config) {
  config.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  config.headers.common['Authorization'] = `Bearer ${JSON.parse(localStorage.getItem('access_token'))}`;
  return config;
});

http.interceptors.response.use(function (response) {
  return response;
}, function (error) {
  return Promise.reject(error);
});

export default {
  startLoadingBy({ commit }, payload) {
    commit('START_LOADING', payload);
  },

  stopLoadingBy({ commit }, payload) {
    commit('STOP_LOADING', payload);
  },

  setShowSnackbar({ commit }, data) {
    commit('SET_SHOW_SNACKBAR', data);
  },

  setSnackbar({ commit }, data) {
    commit('SET_SNACKBAR', data);
  },

  setShowModal({ commit }, data) {
    commit('SET_SHOW_MODAL', data);
  },

  register({ dispatch, commit }, data) {
    return http.post('/register', data)
      .then((res) => {
        commit('LOGGED_IN', res.data);

        return dispatch('fetchData')
          .then(() => {
            return Promise.resolve();
          });
      });
  },

  login({ dispatch, commit }, data) {
    return http.post('/login', data)
      .then((res) => {
        commit('LOGGED_IN', res.data);

        return dispatch('fetchData')
          .then(() => {
            return Promise.resolve();
          });
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

        return res.data;
    });
  },

  confirmUserMail({ commit }, data) {
    return http.get(`/confirm/${data}`)
      .then((res) => {
        parseSuccessMessage(res.data);

        return res.data;
      })
      .catch((error) => {
        parseErrorMessage(error);

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

  fetchData({ commit, getters }) {
    commit('START_LOADING', 'fetch_data');

    return http.get('/data')
      .then((res) => {
        commit('SET_USER', res.data.user);
        commit('SET_PORTFOLIOS', res.data.portfolios);

        return res.data;
      })
      .finally(() => {
        commit('STOP_LOADING', 'fetch_data');
      });
  },

  fetchSymbolsData({ commit }) {
    return http.get('/symbols/update')
      .then((res) => {
        commit('SET_PORTFOLIOS', res.data.data);

        return res.data;
      });
  },

  storePortfolio({ commit }, form) {
    return http.post('/portfolios', form)
      .then((res) => {
        commit('ADD_PORTFOLIO', res.data.data);

        return res.data;
      });
  },

  updatePortfolio({ commit, getters }, { id, form }) {
    return http.put(`/portfolios/${id}`, form)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        commit('UPDATE_PORTFOLIO', { index, portfolio: form });

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

  fetchPortfolio({ commit, getters }, id) {
    const portfolio = getters.getPortfolioById(id);

    if (portfolio) {
      return Promise.resolve(portfolio);
    }

    return http.get(`/portfolios/${id}`)
      .then((res) => {
        return res.data.data;
      });
  },

  storeTransaction({ commit, getters }, form) {
    return http.post('/transactions', form)
      .then((res) => {
        if (getters.portfoliosCount) {
          const data = res.data.data;
          const portfolioIndex = getters.getPortfolioIndexById(data.portfolio.id);
          const shareIndex = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, data.share.id);

          commit('UPDATE_PORTFOLIO', { index: portfolioIndex, portfolio: data.portfolio });
          commit('UPDATE_SHARE', { portfolioIndex, shareIndex, share: data.share });

          const transaction = getters.getLastTransaction(portfolioIndex, shareIndex);
          commit('DELETE_ITEM_DETAILS', { portfolioIndex, shareIndex, transaction });
        }

        return res.data;
      });
  },

  destroyTransaction({ commit, getters }, id) {
    return http.delete(`/transactions/${id}`)
      .then((res) => {
        if (getters.portfoliosCount) {
          const data = res.data.data;
          const portfolioIndex = getters.getPortfolioIndexById(data.portfolio.id);
          const shareIndex = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, data.share.id);
          const transaction = getters.getLastTransaction(portfolioIndex, shareIndex);

          commit('UPDATE_PORTFOLIO', { index: portfolioIndex, portfolio: data.portfolio });
          commit('UPDATE_SHARE', { portfolioIndex, shareIndex, share: data.share });
          commit('DELETE_ITEM_DETAILS', { portfolioIndex, shareIndex, transaction });
        }

        return res.data;
      });
  },

  fetchSymbols() {
    return http.get('/symbols')
      .then((res) => {
        return res.data;
      });
  },

  storeShare({ commit, getters }, form) {
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
        const index = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, data.id);
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

  fetchShareTransactions({ dispatch, commit, getters }, { path, id }) {
    commit('START_LOADING', 'fetch_share_transactions');
    const share = getters.getShareById(id);

    if (has(share, 'transactions')) {
      commit('STOP_LOADING', 'fetch_share_transactions');
      return Promise.resolve(share);
    }

    return http.get(path)
      .then((res) => {
        const transactions = res.data.data;
        const { portfolioIndex, index } = getters.getShareIndexById(id);
        commit('ADD_TRANSACTIONS', { portfolioIndex, index, transactions });
        commit('STOP_LOADING', 'fetch_share_transactions');
  
        return getters.getShareById(id);
      });
  },

  fetchTransactionsByParams({ dispatch, commit, getters }, path) {
    commit('START_LOADING', 'fetch_transactions_by_params');

    const [ model, id, unused, type, year ] = path.split('/').filter(item => item.trim().length);
    const key = trimEnd(upperFirst(model), 's');
    const collection = getters[`get${key}ById`](id);

    if (typeof year === 'undefined') {
      if (has(collection, type)) {
        commit('STOP_LOADING', 'fetch_transactions_by_params');

        return Promise.resolve(collection[type]);
      }
    } else if (has(collection[`${type}ByYear`], year)) {
      commit('STOP_LOADING', 'fetch_transactions_by_params');

      return Promise.resolve(collection[`${type}ByYear`][year]);
    }

    return http.get(path)
      .then((res) => {
        const data = res.data.data;
        const index = getters[`get${key}IndexById`](id);
        commit('ADD_ITEM_DETAILS', { index, type, year, data });
        commit('STOP_LOADING', 'fetch_transactions_by_params');

        return data;
      });
  },
}
