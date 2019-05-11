import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import Home from '../components/pages/Home';
import CreatePortfolio from '../components/pages/CreatePortfolio';
import EditPortfolio from '../components/pages/EditPortfolio';
import AddTransaction from '../components/pages/AddTransaction';
import Share from '../components/pages/Share';
import Login from '../components/pages/Login';
import Register from '../components/pages/Register';
import ForgotPassword from '../components/pages/ForgotPassword';
import PasswordReset from '../components/pages/PasswordReset';

Vue.use(Router);

const confirmUser = function(to, from, next) {
    if (to.params.confirmation_code) {
        store.dispatch('confirmUserMail', to.params.confirmation_code);
        next({
            path: '/',
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
      path: "/portfolio/:portfolioId(\\d+)/edit",
      name: 'EditPortfolio',
      component: EditPortfolio,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/:portfolioId(\\d+)/:shareId(\\d+)/transaction/add",
      name: 'AddTransaction',
      component: AddTransaction,
      props: true,
      meta: { requiresAuth: true, transitionName: 'slide-right' },
    },
    {
      path: "/share/:shareId(\\d+)/transactions",
      name: 'Share',
      component: Share,
      props: true,
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
      path: '*',
      redirect: '/'
    }
  ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            if (store.getters.portfoliosCount == 0) {
                store.dispatch('fetchData')
                     .then(() => {
                         return next();
                     });
            }
            return next();
        } else {
            return next({ name: 'Login', query: { redirect: to.fullPath } });
        }
    } else if (to.matched.some(record => record.meta.redirectIfAuth)) {
        if (store.getters.isLoggedIn) {
            if (store.getters.portfoliosCount == 0) {
                store.dispatch('fetchData')
                     .then(() => {
                         return next();
                     });
            }
            return next({ name: 'Home' })
        } else {
            return next();
        }
    } else {
        return next();
    }
})

router.afterEach((to, from) => {
    // Set window location.
    window.scrollTo(0, 0);
})

export default router;