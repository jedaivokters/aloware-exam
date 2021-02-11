import Store from '@/services/store'
import Router from '@/services/router'
import appSetting from '../../app-setting/module'
import blogSiteModule from '../../blog-site/module'

export default {
	namespaced: true,
	
	state: {},
	
	getters: {},
	
	mutations: {},
	
	actions: {
		initialize ({ dispatch }) {
			console.info('System initializing...')
			console.info('System initialized.')
			dispatch('initializeModule', appSetting)
			dispatch('initializeModule', blogSiteModule)
		},
		initializeModule ({ dispatch }, module) {
			Store.registerModule(module.name, module.store)
			Router.addRoutes(module.routes)
			dispatch(module.name + '/initialize', null, { root: true })
		}
	}
}
