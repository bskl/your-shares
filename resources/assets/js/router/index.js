import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: require('../components/pages/Home.vue'),
        },
        {
            path: '/login',
            component: require('../components/auth/LoginForm.vue'),
        },
        {
            path: '/register',
            component: require('../components/auth/RegisterForm.vue'),
        },
        {
            path: '/password/reset',
            component: require('../components/auth/EmailForm.vue'),
        },
    ],
})