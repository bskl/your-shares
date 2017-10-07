class Errors {
    /**
     * Create a new Errors instance.
     */
    constructor() {
        this.errors = {};
        this.message = null;
    }


    /**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     */
    has(field) {
        return this.any() && this.errors.hasOwnProperty(field);
    }


    /**
     * Determine if we have any errors.
     */
    any() {
        return ! _.isEmpty(this.errors);
    }


    /**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */
    get(field) {
        if (this.any() && this.errors[field]) {
            return this.errors[field][0];
        }
    }

    /**
     * Record the new errors and message.
     *
     * @param {object} data
     */
    record(data) {
        this.errors = data.errors;
        this.message = data.message;
    }


    /**
     * Clear one or all error fields.
     *
     * @param {string|null} field
     */
    clear(field) {
        if (field) {
            delete this.errors[field];
            if (! this.any()) {
                this.message = null;
            }

            return;
        }

        this.errors = {};
        this.message = null;
    }
}

export default Errors;