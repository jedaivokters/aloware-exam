<template>
	<div>
  		<router-view />
  		<loading :active.sync="cs.isLoading" 
        	:can-cancel="false" 
        	:is-full-page="true"
        	loader="bars"
        	:width="70"
        	:height="70"
        	color="#50a4f2" />
  	</div>
</template>

<script>
	import { mapState } from 'vuex';
	// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

	export default {
		components: {
            Loading
    },
	computed: {
		...mapState('blog-site', {
			cs : state => state
		})
	}
	}
</script>