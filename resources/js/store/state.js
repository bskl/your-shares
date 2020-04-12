import { DEFAULT_SNACKBAR } from './constants';

export default {
  loading: [],
  isLoggedIn: !!JSON.parse(localStorage.getItem('access_token')),
  showModal: false,
  user: [],
  portfolios: [],
  showSnackbar: false,
  snackbar: {
    ...DEFAULT_SNACKBAR,
  },
}
