import { has, lowerFirst } from 'lodash';
import { TRANSACTION_TYPES, DEFAULT_SNACKBAR } from './constants.js';

export default {
  START_LOADING(state, payload) {
    state.loading.push(payload);
  },
  STOP_LOADING(state, payload) {
    state.loading = state.loading.filter(l => l != payload);
  },
  TOGGLE_NAV_DRAWER(state) {
    state.navDrawer = !state.navDrawer;
  },
  SET_SHOW_MODAL(state, data) {
    state.showModal = data;
  },
  LOGGED_IN(state, data) {
    if (has(data, 'access_token') && data.access_token) {
      localStorage.setItem('access_token', JSON.stringify(data.access_token));
      state.isLoggedIn = true;
    }
  },
  LOGGED_OUT(state) {
    localStorage.removeItem('access_token');
    state.isLoggedIn = false;
  },
  SET_USER(state, data) {
    state.user = data;
    localStorage.setItem('locale', JSON.stringify(data.locale));
  },
  SET_LOCALE(state, locale) {
    state.user.locale = locale;
    localStorage.setItem('locale', JSON.stringify(locale));
  },
  SET_PORTFOLIOS(state, data) {
    state.portfolios = data;
  },
  ADD_PORTFOLIO(state, portfolio) {
    state.portfolios.push(portfolio);
  },
  UPDATE_PORTFOLIO(state, { index, portfolio }) {
    for (const key in portfolio) {
      state.portfolios[index][key] = portfolio[key];
    }
  },
  DESTROY_PORTFOLIO(state, index) {
    state.portfolios.splice(index, 1);
  },
  ADD_SHARE(state, { index, data }) {
    state.portfolios[index].shares.push(data);
  },
  UPDATE_SHARE(state, { portfolioIndex, shareIndex, share }) {
    for (const key in share) {
      state.portfolios[portfolioIndex].shares[shareIndex][key] = share[key];
    }
  },
  DESTROY_SHARE(state, { portfolioIndex, index }) {
    state.portfolios[portfolioIndex].shares.splice(index, 1);
  },
  ADD_TRANSACTIONS(state, { portfolioIndex, shareIndex, transactions }) {
    state.portfolios[portfolioIndex].shares[shareIndex]['transactions'] = transactions;
  },
  ADD_TRANSACTION(state, { portfolioIndex, shareIndex, transaction }) {
    state.portfolios[portfolioIndex].shares[shareIndex].transactions.push(transaction);
  },
  DESTROY_LAST_TRANSACTION(state, { portfolioIndex, shareIndex }) {
    state.portfolios[portfolioIndex].shares[shareIndex].transactions.pop();
  },
  ADD_ITEM_DETAILS(state, { index, type, year, data }) {
    if (typeof index === 'object') {
      if (typeof year === 'undefined') {
        state.portfolios[index.portfolioIndex].shares[index.index][type] = data;
      } else {
        if (!has(state.portfolios[index.portfolioIndex].shares[index.index], `${type}ByYear`)) {
          state.portfolios[index.portfolioIndex].shares[index.index][`${type}ByYear`] = {};
        }
        state.portfolios[index.portfolioIndex].shares[index.index][`${type}ByYear`][year] = data;
      }
    }
    if (typeof index === 'number') {
      if (typeof year === 'undefined') {
        state.portfolios[index][type] = data;
      } else {
        if (!has(state.portfolios[index], `${type}ByYear`)) {
          state.portfolios[index][`${type}ByYear`] = {};
        }
        state.portfolios[index][`${type}ByYear`][year] = data;
      }
    }
  },
  DELETE_ITEM_DETAILS(state, { portfolioIndex, shareIndex, transaction }) {
    const type = lowerFirst(TRANSACTION_TYPES[transaction.type]);
    const year = transaction.date_at.split('.')[2];

    delete state.portfolios[portfolioIndex][type];
    delete state.portfolios[portfolioIndex].shares[shareIndex][type];
    if (has(state.portfolios[portfolioIndex], `${type}ByYear`)) {
      delete state.portfolios[portfolioIndex][`${type}ByYear`][year];
    }
    if (has(state.portfolios[portfolioIndex].shares[shareIndex], `${type}ByYear`)) {
      delete state.portfolios[portfolioIndex].shares[shareIndex][`${type}ByYear`][year];
    }
  },
  SET_SHOW_SNACKBAR(state, data) {
    state.showSnackbar = data;
  },
  SET_SNACKBAR(state, data) {
    state.snackbar = Object.assign({}, {
        ...DEFAULT_SNACKBAR,
      }, data);
  },
}
