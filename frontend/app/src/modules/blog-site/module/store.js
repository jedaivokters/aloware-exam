import i18n from '@/i18n'

export default {
	namespaced: true,
	state: {
	    isLoading: false
  	},
	getters: {},
	mutations: {
		loading(state, isLoading) {
			state.isLoading = isLoading;
		}
    },
	actions: {
		initialize ({ dispatch }) {
			console.info('Site initializing...')
			console.info('Site initialized.')
		}
	}
}