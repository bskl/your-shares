import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import Home from '../components/pages/Home';
import CreatePortfolio from '../components/pages/CreatePortfolio';
import EditPortfolio from '../components/pages/EditPortfolio';
import AddTransaction from '../components/pages/AddTransaction';
import Share from '../components/pages/Share';
import ListTransactionByTypeYearAndShare from '../components/pages/ListTransactionByTypeYearAndShare';
import ListTransactionByTypeAndYear from '../components/pages/ListTransactionByTypeAndYear';
import ListTransactionByType from '../components/pages/ListTransactionByType';
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
      meta: { requiresAuth: true, skipScrollBehavior: true },
    },
    {
      path: "/portfolio/create",
      name: 'CreatePortfolio',
      component: CreatePortfolio,
      meta: { requiresAuth: true },
    },
    {
      path: "/portfolio/:id(\\d+)/edit",
      name: 'EditPortfolio',
      component: EditPortfolio,
      meta: { requiresAuth: true },
    },
    {
      path: "/share/:id(\\d+)/transaction/add",
      name: 'AddTransaction',
      component: AddTransaction,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: "/share/:id(\\d+)/transactions",
      name: 'Share',
      component: Share,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: "/portfolio/:id(\\d+)/transactions/:type",
      name: 'ListPortfolioTransactionByType',
      component: ListTransactionByType,
      meta: { requiresAuth: true },
    },
    {
      path: "/portfolio/:id(\\d+)/transactions/:type/:year(\\d+)",
      name: 'ListPortfolioTransactionByTypeAndYear',
      component: ListTransactionByTypeAndYear,
      meta: { requiresAuth: true },
    },
    {
      path: "/share/:id(\\d+)/transactions/:type",
      name: 'ListShareTransactionByType',
      component: ListTransactionByType,
      meta: { requiresAuth: true },
    },
    {
      path: "/share/:id(\\d+)/transactions/:type/:year(\\d+)",
      name: 'ListTransactionByTypeYearAndShare',
      component: ListTransactionByTypeYearAndShare,
      meta: { requiresAuth: true },
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
      meta: { redirectIfAuth: true },
    },
    {
      path: "/register",
      name: 'Register',
      component: Register,
      meta: { redirectIfAuth: true },
    },
    {
      path: "/password/reset",
      name: 'Reset',
      component: ForgotPassword,
      meta: { redirectIfAuth: true },
    },
    {
      path: "/password/reset/:reset_password_code",
      name: 'PasswordReset',
      component: PasswordReset,
      meta: { redirectIfAuth: true },
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
  scrollBehavior (to, from, savedPosition) {
    if (to.meta.skipScrollBehavior) {
      return {};
    }

    return { x: 0, y: 0 }
  }
});

router.beforeEach((to, from, next) => {
  store.dispatch('checkAuth', JSON.parse(localStorage.getItem('access_token')))
    .then(() => {
      if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
          next();
        } else {
          next({ name: 'Login', query: { redirect: to.fullPath } });
        }
      } else if (to.matched.some(record => record.meta.redirectIfAuth)) {
        if (store.getters.isLoggedIn) {
          next({ name: 'Home' });
        } else {
          next();
        }
      } else {
        next();
      }
    })
})

export default router;
