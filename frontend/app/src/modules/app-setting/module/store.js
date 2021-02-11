import './client'
import AppMixin from './mixin'
import Vue from 'vue'
Vue.mixin(AppMixin);

export default {
	namespaced: true,
	state: {},
	getters: {},
	mutations: {},
	actions: {
		initialize ({ dispatch }) {
			console.info('Site initializing...')
			console.info('Site initialized.')
		}
	}
}