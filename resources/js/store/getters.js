
import { last } from 'lodash';
import { USER_TYPES } from './constants';

export default {
  isLoggedIn (state) {
    return state.isLoggedIn;
  },
  isAdmin (state) {
    return state.user.role == USER_TYPES.admin;
  },
  isInLoading: (state) => (payload) => {
    return state.loading.includes(payload);
  },
  portfoliosCount (state) {
    return state.portfolios.length;
  },
  getPortfolioByIndex: (state) => (index) => {
    return state.portfolios[index];
  },
  getPortfolioById: (state) => (id) => {
    return state.portfolios.find(portfolio => portfolio.id == id);
  },
  getPortfolioIndexById: (state) => (id) => {
    return state.portfolios.findIndex(portfolio => portfolio.id == id);
  },
  getShareById: (state, getters) => (id) => {
    for (const portfolio of state.portfolios) {
      const share = portfolio.shares.find(share => share.id == id);
      if (share !== undefined) {
        return share;
      }
    }
  },
  getShareIndexById: (state, getters) => (id) => {
    for (const portfolioIndex in state.portfolios) {
      const index = getters.getPortfolioByIndex(portfolioIndex).shares.findIndex(share => share.id == id);
      if (index !== -1) {
        return { portfolioIndex: portfolioIndex, index: index };
      }
    }
  },
  getShareIndexByPortfolioIndexAndId: (state, getters) => (portfolioIndex, shareId) => {
    const portfolio = getters.getPortfolioByIndex(portfolioIndex);
    return portfolio.shares.findIndex(share => share.id === shareId);
  },
  getLastTransaction: (state, getters) => (portfolioIndex, shareIndex) => {
    const portfolio = getters.getPortfolioByIndex(portfolioIndex);
    return last(portfolio.shares[shareIndex].transactions);
  },
}
