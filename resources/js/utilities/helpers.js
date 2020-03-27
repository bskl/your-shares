import store from '../store';
import router from "../router";

export function redirectError(error) {
  if (error.response) {
    if (error.response.status === 400 || error.response.status === 401) {
      store.commit('LOGGED_OUT');
      return 'Login';
    } else if (error.response.status === 403) {
      return 'Forbidden';
    } else if (error.response.status === 404) {
      return 'NotFound';
    }
  } else {
    return 'NotFound';
  }
}

export function parseSuccessMessage(res) {
  const message = res.message || [];

  if (message.length == 0) return '';

  store.dispatch('setSnackbar', { msg: message });
}

export function parseErrorMessage(error) {
  if (error.response) {
    if (error.response.status === 400 || error.response.status === 401) {
      store.commit('LOGGED_OUT');
      router.push({ name: 'Login' });
    } else if (error.response.status === 403) {
      router.push({ name: 'Forbidden' });
    } else if (error.response.status === 404) {
      router.push({ name: 'NotFound' });
    }

    const errors = error.response.data.errors || [];

    if (errors.length == 0) return '';

    store.dispatch('setSnackbar', { color: 'error', msg: errors[0][0] });
  } else {
    console.log(error);
  }
}
