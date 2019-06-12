import Errors from "./Errors";

class Form {
  /**
   * Create a new Form instance.
   *
   * @param {object} data
   */
  constructor(data) {
    this.originalData = data;

    for (let field in data) {
      this[field] = data[field];
    }

    this.errors = new Errors();
  }

  /**
   * Fetch all relevant data for the form.
   */
  data() {
    let data = {};

    for (let property in this.originalData) {
      data[property] = this[property];
    }

    return data;
  }

  /**
   * Reset the form fields.
   */
  reset() {
    for (let field in this.originalData) {
      this[field] = null;
    }

    this.errors.clear();
  }

  /**
   * Handle a failed form submission.
   *
   * @param {object} data
   */
  onFail(data) {
    this.errors.record(data.errors);
  }
}

export default Form;
