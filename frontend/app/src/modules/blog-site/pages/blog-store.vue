<template>
  <div id="blog_details">
    <Header />

    <div class="container">
      <h1>Add New Blog</h1>
      <div class="card">
        <div class="card-body">
          <form class="p-4" @submit="submitForm">

            <div class="form-group row">
              <label for="exampleInputEmail1" class="col-sm-4 col-form-label">Title:</label>
              <div class="col-sm-8">
                <input v-model="blog.title" type="text" required class="form-control" placeholder="Title">
              </div>
            </div>

            <div class="form-group row">
              <label for="exampleInputEmail1" class="col-sm-4 col-form-label">Slug:</label>
              <div class="col-sm-8">
                <input v-model="blog.slug" type="text" required class="form-control" placeholder="Personalized URL">
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12" align="center">
                  <button class="btn btn-primary ml-3">Add</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
html {
  @import "@/assets/scss/_style.scss";
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
      blog: {}
	  };
  },
  methods: {
    async submitForm(e) {
      e.preventDefault();

      await this.$api.post('blog/store', this.blog);

      this.$router.push({name: 'blogs'});
    }
  },
};
</script>