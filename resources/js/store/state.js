export default {
  isLoading: false,
  isLoggedIn: !!JSON.parse(localStorage.getItem('access_token')),
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
