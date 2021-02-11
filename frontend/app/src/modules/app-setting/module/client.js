import axios from 'axios'
import Vue from 'vue'
import Store from '@/services/store'

Vue.use(Vue2Storage)

const api = axios.create({
    baseURL: `${process.env.VUE_APP_API_URL}/api`
})

api.interceptors.request.use(
    (config) => {
        Store.commit('blog-site/loading', true);

        return config;
    },

    (error) => {
        return Promise.reject(error);
    }
)

// Response
api.interceptors.response.use(
    (response) => {
        Store.commit('blog-site/loading', false);
        return response;
    },
    (error) => {
        if (error.response.status === 401) return window.location = '/blogs';

        Store.commit('blog-site/loading', false)
        return Promise.reject(error)
    }
)

Vue.prototype.$api = api;
export { api }