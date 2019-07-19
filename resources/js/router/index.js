import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import Home from '../components/pages/Home';
import CreatePortfolio from '../components/pages/CreatePortfolio';
import EditPortfolio from '../components/pages/EditPortfolio';
import AddTransaction from '../components/pages/AddTransaction';
import Share from '../components/pages/Share';
import TransactionListingByType from '../components/pages/TransactionListingByType';
import Login from '../components/pages/Login';
import Register from '../components/pages/Register';
import ForgotPassword from '../components/pages/ForgotPassword';
import PasswordReset from '../components/pages/PasswordReset';
import NotFound from '../components/pages/NotFound';
import Forbidden from '../components/pages/Forbidden';

Vue.use(Router);

const confirmUser = function(to, from, next) {
  if (to.params.confirmation_code) {
    store.dispatch('confirmUserMail', to.params.confirmation_code);
    next({
      name: 'Home',
      query: { redirect: to.fullPath }
    })
  } else {
    next();
  }
}

const router = new Router({
  mode: "history",
  base: __dirname,
  routes: [
    {
      path: "/",
      name: 'Home',
      component: Home,
      meta: { requiresAuth: true, transitionName: 'slide-left' },
    },
    {
      path: "/portfolio/create",
      name: 'CreatePortfolio',
      component: CreatePortfolio,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/portfolio/:id(\\d+)/edit",
      name: 'EditPortfolio',
      component: EditPortfolio,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/share/:id(\\d+)/transaction/add",
      name: 'AddTransaction',
      component: AddTransaction,
      props: true,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/share/:id(\\d+)/transactions",
      name: 'Share',
      component: Share,
      props: true,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/portfolio/:id(\\d+)/transactions/:type/:year(\\d+)?",
      name: 'TransactionListingByType',
      component: TransactionListingByType,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/confirm/:confirmation_code",
      meta: { requiresAuth: true },
      beforeEnter: confirmUser
    },
    {
      path: "/login",
      name: 'Login',
      component: Login,
      meta: { redirectIfAuth: true, transitionName: 'slide-left' },
    },
    {
      path: "/register",
      name: 'Register',
      component: Register,
      meta: { redirectIfAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/password/reset",
      name: 'Reset',
      component: ForgotPassword,
      meta: { redirectIfAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/password/reset/:reset_password_code",
      name: 'PasswordReset',
      component: PasswordReset,
      meta: { redirectIfAuth: true, transitionName: 'fade' },
    },
    {
      path: '/404',
      name: 'NotFound',
      component: NotFound,
    },
    {
      path: '/403',
      name: 'Forbidden',
      component: Forbidden,
    },
    {
      path: '*',
      redirect: '/404',
    },
  ],
  scrollBehavior () {
    return { x: 0, y: 0 }
  }
});

router.beforeEach((to, from, next) => {
  store.dispatch('checkAuth', JSON.parse(localStorage.getItem('access_token')))
    .then(() => {
      if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
          return next();
        } else {
          return next({ name: 'Login', query: { redirect: to.fullPath } });
        }
      }
      if (to.matched.some(record => record.meta.redirectIfAuth)) {
        if (store.getters.isLoggedIn) {
          return next({ name: 'Home' });
        } else {
          return next();
        }
      }

      return next();
    })
})

export default router;
