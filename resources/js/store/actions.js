import axios from "axios";
import { has, upperFirst, trimEnd } from 'lodash';
import { parseErrorMessage, parseSuccessMessage } from '../utilities/helpers.js';

const http = axios.create({
  withCredentials: true,
});

http.interceptors.request.use(function (config) {
  config.headers.common['X-Requested-With'] = 'XMLHttpRequest';
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

  setErrors({ commit }, data) {
    commit('SET_ERRORS', data);
  },

  unsetError({ commit }, data) {
    commit('UNSET_ERROR', data);
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
    return http.get('/sanctum/csrf-cookie')
      .then((res) => {

        return http.post('/login', data)
          .then((res) => {
            commit('LOGGED_IN');

            return dispatch('fetchData')
              .then(() => {
                return Promise.resolve();
            });
          });
      });
  },

  setLocale({ commit }, locale) {
    return http.get(`/api/locale/${locale}`)
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

  confirmUserMail({ commit }, token) {
    return http.get(`/api/confirm/${token}`)
      .then((res) => {
        commit('LOGGED_IN');
        parseSuccessMessage(res.data);

        return res;
      })
      .catch((error) => {
        parseErrorMessage(error);

        return Promise.reject(error);
      });
  },

  sendResetPasswordEmail(_, data) {
    return http.post('/forgot-password', data)
      .then((res) => {
        return res.data;
      });
  },

  resetPassword(_, data) {
    return http.post('/reset-password', data)
      .then((res) => {
        return res.data;
      });
  },

  fetchData({ commit, getters }) {
    commit('START_LOADING', 'fetch_data');

    return http.get('/api/data')
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
    return http.get('/api/symbols/update')
      .then((res) => {
        commit('SET_PORTFOLIOS', res.data.data);

        return res.data;
      });
  },

  storePortfolio({ commit }, form) {
    return http.post('/api/portfolios', form)
      .then((res) => {
        commit('ADD_PORTFOLIO', res.data.data);

        return res.data;
      });
  },

  updatePortfolio({ commit, getters }, { id, form }) {
    return http.put(`/api/portfolios/${id}`, form)
      .then((res) => {
        const index = getters.getPortfolioIndexById(id);
        commit('UPDATE_PORTFOLIO_BY_KEY', { index, portfolio: form });

        return res.data;
      });
  },

  destroyPortfolio({ commit, getters }, id) {
    return http.delete(`/api/portfolios/${id}`)
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

    return http.get(`/api/portfolios/${id}`)
      .then((res) => {
        return res.data.data;
      });
  },

  storeTransaction({ commit, getters }, form) {
    return http.post('/api/transactions', form)
      .then((res) => {
        if (getters.portfoliosCount) {
          const data = res.data.data;
          const portfolioIndex = getters.getPortfolioIndexById(data.portfolio.id);
          commit('UPDATE_PORTFOLIO', { index: portfolioIndex, portfolio: data.portfolio });

          const shareIndex = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, form.share_id);
          commit('ADD_TRANSACTIONS', { portfolioIndex, index: shareIndex, transactions: data.transactions });
        }

        return res.data;
      });
  },

  destroyTransaction({ commit, getters }, id) {
    return http.delete(`/api/transactions/${id}`)
      .then((res) => {
        if (getters.portfoliosCount) {
          const data = res.data.data;
          const portfolioIndex = getters.getPortfolioIndexById(data.portfolio.id);
          const shareIndex = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, data.share.id);
          const transaction = getters.getLastTransaction(portfolioIndex, shareIndex);
          
          commit('UPDATE_PORTFOLIO_BY_KEY', { index: portfolioIndex, portfolio: data.portfolio });
          commit('DELETE_ITEM_DETAILS', { portfolioIndex, shareIndex, transaction });
          commit('UPDATE_SHARE', { portfolioIndex, shareIndex, share: data.share });
        }

        return res.data;
      });
  },

  fetchSymbols() {
    return http.get('/api/symbols')
      .then((res) => {
        return res.data;
      });
  },

  storeShare({ commit, getters }, form) {
    return http.post('/api/shares', form)
      .then((res) => {
        const index = getters.getPortfolioIndexById(form.portfolio_id);
        const data = res.data.data;
        commit('ADD_SHARE', { index, data });     

        return res.data;
      });
  },

  destroyShare({ commit, getters }, data) {
    return http.delete(`/api/shares/${data.id}`)
      .then((res) => {
        const portfolioIndex = getters.getPortfolioIndexById(data.portfolio_id);
        const index = getters.getShareIndexByPortfolioIndexAndId(portfolioIndex, data.id);
        commit('DESTROY_SHARE', { portfolioIndex, index });

        return res.data;
      });
  },

  fetchShare(_, id) {
    return http.get(`/api/shares/${id}`)
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

    return http.get(`/api${path}`)
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

    return http.get(`/api${path}`)
      .then((res) => {
        const data = res.data.data;
        const index = getters[`get${key}IndexById`](id);
        commit('ADD_ITEM_DETAILS', { index, type, year, data });
        commit('STOP_LOADING', 'fetch_transactions_by_params');

        return data;
      });
  },
}
