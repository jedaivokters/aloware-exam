import { BootstrapVue } from 'bootstrap-vue'
import {Vue2Storage} from 'vue2-storage'
import Vue from 'vue'
import vueDebounce from 'vue-debounce'
import Pagination from 'vue-pagination-2'

Vue.use(BootstrapVue)
Vue.use(vueDebounce)
Vue.use(Vue2Storage)

Vue.component('pagination', Pagination);

import './assets/scss/custom.scss'

import Router from './services/router'
import Store from './services/store'
import App from './App.vue'
import system from './modules/system/module'
import i18n from './i18n'

/* Initialize System Module */
Store.registerModule('system', system.store)
Router.addRoutes(system.routes)
Store.dispatch('system/initialize', null, { root: true })

Vue.config.productionTip = false

export const eventBus = new Vue()
new Vue({
    // eslint-disable-line no-new
    el: '#app',
    template: '<App/>',
    router: Router,
    store: Store,
    i18n,
    render: h => h(App)
})
