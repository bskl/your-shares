import { http } from "../services/http.js";
import { userStore } from "./userStore.js";
import { portfolioStore } from "./portfolioStore.js";

export const sharedStore = {
  state: {
    user: null,
    portfolios: []
  },

  /**
   * Get the all data.
   *
   * @return {Array.<Object>}
   */
  getData() {
    NProgress.start();

    return new Promise((resolve, reject) => {
      http.get(
        "/data",
        ({ data }) => {
          _.assign(this.state, data);

          userStore.init(this.state.user);
          portfolioStore.init(this.state.portfolios);

          resolve(this.state);
        },
        error => reject(error)
      );
    });
  }
};
