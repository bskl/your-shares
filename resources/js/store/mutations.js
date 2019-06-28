import ls from "local-storage";

export default {
  TOGGLE_LOADING(state) {
    state.isLoading = !state.isLoading;
  },
  LOGGED_IN(state, data) {
    if (_.has(data, 'access_token') && data.access_token) {
      ls.set('access_token', data.access_token);
      state.isLoggedIn = true;
    }
  },
  CHECK_AUTH(state, data) {
    state.isLoggedIn = (data) ? true : false;
  },
  LOGGED_OUT(state) {
    ls.remove('access_token');
    state.isLoggedIn = false;
  },
  SET_USER(state, data) {
    state.user = data;
    ls.set('locale', data.locale);
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
