export default {
  isLoggedIn (state) {
    return state.isLoggedIn;
  },
  isAdmin (state) {
    return state.user.role == 'admin';
  },
  portfoliosCount (state) {
    return state.portfolios.length;
  },
  getPortfolioByIndex: (state) => (index) => {
    return state.portfolios[index];
  },
  getPortfolioIndexById: (state) => (id) => {
    return state.portfolios.findIndex(portfolio => portfolio.id === id);
  },
  getPortfolioById: (state) => (id) => {
    return state.portfolios.find(portfolio => portfolio.id === id);
  },
  getPortfolioWithKey: (state, getters) => (portfolio, keys) => {
    const arr = [];

    keys.forEach(function (key) {
      arr[key] = portfolio[key];
    });

    return arr;
  },
  getSharesByPortfolio: (state, getters) => (id) => {
    const portfolio = getters.getPortfolioById(id);
    return portfolio.shares;
  },
  getShareIndexById: (state, getters) => (portfolioId, shareId) => {
    return getters.getSharesByPortfolio(portfolioId).findIndex(share => share.id === shareId);
  },
}
