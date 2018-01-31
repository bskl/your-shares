export const portfolioStore = {
    state: {
        portfolios: [{
            id: 0,
            name: '',
            order: '',
            currency: '',
            shares: [],
            total_amount: '',
            total_average_amount: '',
            total_commission_amount: '',
            total_dividend_gain: '',
            total_bonus_issue_share: '',
            total_gain: '',
        }]
    },

    /**
     * Set the portfolios data.
     *
     * @param {Array.<Object>}  portfolios  The auth user's portfolios
     */
    init(portfolios) {
        this.all = portfolios
    },

    /**
     * Get all portfolios.
     *
     * @return {Array.<Object>} portfolios
     */
    get all() {
        return this.state.portfolios
    },

    /**
     * Set all portfolios.
     *
     * @param  {Array.<Object>} portfolios
     */
    set all(portfolios) {
        this.state.portfolios = portfolios
    },
}