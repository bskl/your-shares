import has from 'lodash/has';

export default {
  TOGGLE_LOADING(state) {
    state.isLoading = !state.isLoading;
  },
  TOGGLE_NAV_DRAWER(state) {
    state.navDrawer = !state.navDrawer;
  },
  LOGGED_IN(state, data) {
    if (has(data, 'access_token') && data.access_token) {
      localStorage.setItem('access_token', JSON.stringify(data.access_token));
      state.isLoggedIn = true;
    }
  },
  CHECK_AUTH(state, data) {
    state.isLoggedIn = (data) ? true : false;
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
  UPDATE_PORTFOLIO(state, { index, data }) {
    state.portfolios[index] = data;
  },
  DESTROY_PORTFOLIO(state, index) {
    state.portfolios.splice(index, 1);
  },
  ADD_SHARE(state, { index, data }) {
    state.portfolios[index].shares.push(data);
  },
  DESTROY_SHARE(state, { portfolioIndex, index }) {
    state.portfolios[portfolioIndex].shares.splice(index, 1);
  },
  SET_SNACKBAR(state, data) {
    state.snackbar.show = true;
    state.snackbar.color = data.color || 'success';
    state.snackbar.text = data.text;
  },
}
