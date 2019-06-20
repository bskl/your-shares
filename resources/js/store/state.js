import ls from "local-storage";

export default {
  isLoading: false,
  isLoggedIn: !!ls.get('access_token'),
  user: [],
  portfolios: [],
  snackbar: {
    show: false,
    timeout: 3000,
    position_y: 'top',
    position_x: null,
    mode: '',
    color: '',
    text: '',
  },
}
