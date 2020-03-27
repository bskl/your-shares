import store from '../store';

export default [
  {
    path: "/",
    name: 'Home',
    component: () => lazyLoadView(import('../components/pages/Home')),
    meta: { requiresAuth: true, skipScrollBehavior: true },
  },
  {
    path: "/portfolios/create",
    name: 'CreatePortfolio',
    component: () => lazyLoadView(import('../components/pages/CreatePortfolio')),
    meta: { requiresAuth: true },
  },
  {
    path: "/portfolios/:id(\\d+)/edit",
    name: 'EditPortfolio',
    component: () => lazyLoadView(import('../components/pages/EditPortfolio')),
    meta: { requiresAuth: true },
  },
  {
    path: "/shares/:id(\\d+)/transactions",
    name: 'Share',
    component: () => lazyLoadView(import('../components/pages/Share')),
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: "/shares/:id(\\d+)/transaction/add",
    name: 'AddTransaction',
    component: () => lazyLoadView(import('../components/pages/AddTransaction')),
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: "/portfolios/:id(\\d+)/transactions/:type",
    name: 'ListPortfolioTransactionByType',
    component: () => lazyLoadView(import('../components/pages/ListTransactionByType')),
    meta: { requiresAuth: true },
  },
  {
    path: "/portfolios/:id(\\d+)/transactions/:type/:year(\\d+)",
    name: 'ListPortfolioTransactionByTypeAndYear',
    component: () => lazyLoadView(import('../components/pages/ListTransactionByTypeAndYear')),
    meta: { requiresAuth: true },
  },
  {
    path: "/shares/:id(\\d+)/transactions/:type",
    name: 'ListShareTransactionByType',
    component: () => lazyLoadView(import('../components/pages/ListTransactionByType')),
    meta: { requiresAuth: true },
  },
  {
    path: "/shares/:id(\\d+)/transactions/:type/:year(\\d+)",
    name: 'ListTransactionByTypeYearAndShare',
    component: () => lazyLoadView(import('../components/pages/ListTransactionByTypeYearAndShare')),
    meta: { requiresAuth: true },
  },
  {
    path: "/confirm/:confirmation_code",
    meta: {
      requiresAuth: true,
      beforeResolve(to, from, next) {
        if (to.params.confirmation_code) {
          store.dispatch('confirmUserMail', to.params.confirmation_code);
          next({
            name: 'Home',
            query: { redirect: to.fullPath }
          })
        } else {
          next();
        }
      },
    },
  },
  {
    path: "/register",
    name: 'Register',
    component: () => lazyLoadView(import('../components/pages/Register')),
    meta: {
      beforeResolve(to, from, next) {
        if (store.getters.isLoggedIn) {
          next({ name: 'Home' })
        } else {
          next()
        }
      },
    },
  },
  {
    path: "/login",
    name: 'Login',
    component: () => lazyLoadView(import('../components/pages/Login')),
    meta: {
      beforeResolve(to, from, next) {
        if (store.getters.isLoggedIn) {
          next({ name: 'Home' })
        } else {
          next()
        }
      },
    },
  },
  {
    path: '/logout',
    name: 'Logout',
    meta: {
      requiresAuth: true,
      beforeResolve(routeTo, routeFrom, next) {
        store.dispatch('logout')
          .then(res => {
            next({ name: 'Login' })
          })
      },
    },
  },
  {
    path: "/password/reset",
    name: 'Reset',
    component: () => lazyLoadView(import('../components/pages/ForgotPassword')),
    meta: {
      beforeResolve(to, from, next) {
        if (store.getters.isLoggedIn) {
          next({ name: 'Home' })
        } else {
          next()
        }
      },
    },
  },
  {
    path: "/password/reset/:reset_password_code",
    name: 'PasswordReset',
    component: () => lazyLoadView(import('../components/pages/PasswordReset')),
    meta: {
      beforeResolve(to, from, next) {
        if (store.getters.isLoggedIn) {
          next({ name: 'Home' })
        } else {
          next()
        }
      },
    },
  },
  {
    path: '/403',
    name: 'Forbidden',
    component: () => lazyLoadView(import('../components/pages/Forbidden')),
  },
  {
    path: '/404',
    name: 'NotFound',
    component: () => lazyLoadView(import('../components/pages/NotFound')),
  },
  {
    path: '*',
    redirect: '/404',
  },
];

function lazyLoadView(AsyncView) {
  const AsyncHandler = () => ({
    component: AsyncView,

    loading: require('../components/partials/Spinner'),

    delay: 400,

    error: require('../components/pages/Timeout'),

    timeout: 10000,
  });

  return Promise.resolve({
    functional: true,
    render(h, { data, children }) {
      return h(AsyncHandler, data, children)
    },
  });
}