import Vue from 'vue';
import Router from 'vue-router';
import NProgress from "nprogress";
import store from '../store';
import routes from './routes';
import { redirectError } from '../utilities/helpers.js';

Vue.use(Router);

const router = new Router({
  mode: "history",
  base: __dirname,
  routes,
  scrollBehavior (to, from, savedPosition) {
    if (to.meta.skipScrollBehavior) {
      return {};
    } else {
      return savedPosition || { x: 0, y: 0 };
    }
  }
});

router.beforeEach((to, from, next) => {
  if (from.name !== null) {
    NProgress.start();
  }

  const requiresAuth = to.matched.some((route) => route.meta.requiresAuth);

  if (requiresAuth) {
    if (to.name !== 'Login' && !store.getters.isLoggedIn) {
      next({ name: 'Login', query: { redirect: to.fullPath } });
    } else {
      if (!store.getters.portfoliosCount) {
        store.dispatch('fetchData')
          .then((res) => {
            next();
          })
          .catch((error) => {
            next({ name: redirectError(error) });
          });
      } else {
        next();
      }
    }
  } else {
    next();
  }
});

router.beforeResolve(async (to, from, next) => {
  try {
    for (const route of to.matched) {
      await new Promise((resolve, reject) => {
        if (route.meta && route.meta.beforeResolve) {
          route.meta.beforeResolve(to, from, (...args) => {
            if (args.length) {
              if (from.name === args[0].name) {
                NProgress.done();
              }

              next(...args);
              reject(new Error('Redirected'));
            } else {
              resolve();
            }
          });
        } else {
          resolve();
        }
      })
    }
  } catch (error) {
    return;
  }

  next();
});

router.afterEach((to, from) => {
  NProgress.done();
})

export default router;
