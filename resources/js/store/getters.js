export default {
    isLoggedIn (state) {
        return state.isLoggedIn;
    },
    portfoliosCount (state) {
        return state.portfolios.length;
    },
    getPortfolioByIndex: (state) => (index) => {
        return state.portfolios[index];
    },
    getPortfolioIndexById: (state) => (portfolioId) => {
        return _.findIndex(state.portfolios, function(portfolio) { return portfolio.id == portfolioId; });
    },
    getPortfolioById: (state) => (portfolioId) => {
        return _.find(state.portfolios, function(portfolio) { return portfolio.id == portfolioId; });
    },
    getPortfolioWithKey: (state, getters) => (portfolio, keys) => {
        const arr = [];

        keys.forEach(function (key) {
            arr[key] = portfolio[key];
        });

        return arr;
    },
    getSharesByPortfolio: (state, getters) => (portfolioId) => {
        const portfolio = getters.getPortfolioById(portfolioId);
        return portfolio.shares;
    },
    getShareIndexById: (state, getters) => (portfolioId, shareId) => {
        return _.findIndex(getters.getSharesByPortfolio(portfolioId), ['id', shareId]);

    },
    getShareByPortfolio: (state, getters) => (portfolioId, shareId) => {
        const portfolio = getters.getPortfolioById(portfolioId);
        return _.find(portfolio.shares, function(share) { return share.id == shareId; });
    }
}
