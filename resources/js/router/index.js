import Vue from 'vue';
import Router from 'vue-router';
import store from '../store';
import routes from './routes';

Vue.use(Router);

const router = new Router({
  mode: "history",
  base: __dirname,
  routes,
  scrollBehavior (to, from, savedPosition) {
    if (to.meta.skipScrollBehavior) {
      return {};
    }

    return savedPosition || { x: 0, y: 0 };
  }
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some((route) => route.meta.requiresAuth);

  if (!requiresAuth) {
    next();
  } else {
    if (store.getters.isLoggedIn) {
      next();
    } else {
      next({ name: 'Login', query: { redirect: to.fullPath } });
    }
  }
});

router.beforeResolve(async (to, from, next) => {
  try {
    for (const route of to.matched) {
      await new Promise((resolve, reject) => {
        if (route.meta && route.meta.beforeResolve) {
          route.meta.beforeResolve(to, from, (...args) => {
            if (args.length) {
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

export default router;
