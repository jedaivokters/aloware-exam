<template>
  <div id="blog_home">
	<Header />
    <div class="container">
		<h1>Blogs</h1>
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Slug</th>
                <th scope="col">Operation</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="b in blogs">
                <th scope="row">{{ b.id }}</th>
                <td>{{ b.title }}</td>
                <td>{{ b.slug }}</td>
				<td>
					<router-link :to="{ name: 'blog-details', params: { slug: b.slug }}" >View</router-link>
				</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
html {
  @import "@/assets/scss/_style.scss";
}
#header {
  background-color: rgb(27, 18, 109);
}
</style>

<script >
import Header from'../components/header';

export default {
  components: {
	Header
  },	
  data() {
    return {
		blogs: []
	};
  },
  async mounted() {
	  this.blogs = await this.$api.get('blog/list').then(r => r.data.data);
  },
  methods: {},
};
</script>