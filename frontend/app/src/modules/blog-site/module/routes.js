import Home from '../pages/home.vue'
import Details from '../pages/blog-detail.vue'
import Add from '../pages/blog-store.vue'

export default [
	{ path: '', name: 'blogs', component: Home },
	{  path: '/b/:slug/', name: 'blog-details', component: Details },
	{  path: '/blog/add', name: 'blog-add', component: Add },
]