export const portfolioStore = {
    state: {
        portfolios: [{
            id: 0,
            name: '',
            currency: '',
            order: '',
            total_bonus_share: '',
            total_sale_amount: '',
            total_purchase_amount: '',
            paid_amount: '',
            gain_loss: '',
            total_commission_amount: '',
            total_dividend_gain: '',
            total_gain: '',
            shares: [],
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