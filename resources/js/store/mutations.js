import ls from "local-storage";

export default {
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
    SET_DATA(state, data) {
        state.user = data.user;
        state.portfolios = data.portfolios;
    },
    ADD_PORTFOLIO(state, portfolio) {
        state.portfolios.push(portfolio);
    },
    UPDATE_PORTFOLIO(state, { index, data }) {
        _.forEach(data, function(value, key) {
            if(_.has(state.portfolios[index], key)) {
                state.portfolios[index][key] = value;
            }
        });
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