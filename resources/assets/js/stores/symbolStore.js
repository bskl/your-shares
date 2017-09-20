export const symbolStore = {
    state: {
        symbols: {
            id: 0,
            code: '',
            name: '',
            title: '',
        }
    },

    /**
     * Set the symbols data.
     *
     * @param {Array.<Object>}  symbols
     */
    init(symbols) {
        this.all = symbols
    },

    /**
     * Get all symbols.
     *
     * @return {Array.<Object>} symbols
     */
    get all() {
        return this.state.symbols
    },

    /**
     * Set all symbols.
     *
     * @param  {Array.<Object>} symbols
     */
    set all(symbols) {
        this.state.symbols = symbols
    },
}