import { DEFAULT_SNACKBAR } from './constants';

export default {
  loading: [],
  isLoggedIn: localStorage.getItem('is_logged_in'),
  showModal: false,
  user: [],
  portfolios: [],
  errors: {},
  showSnackbar: false,
  snackbar: {
    ...DEFAULT_SNACKBAR,
  },
}
