export const portfolioStore = {
    state: {
        portfolios: {
            id: 0,
            name: '',
            order: '',
            symbols: {

            }
        }
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