<template>
  <div>
    <form class="p-4" @submit="submitForm">
          <div class="form-group row">
            <label for="exampleInputEmail1" class="col-sm-4 col-form-label"
              >Name:</label
            >
            <div class="col-sm-8">
              <input
                v-model="comment.name"
                type="text"
                required
                class="form-control"
                placeholder="Your Name"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="exampleInputEmail1" class="col-sm-4 col-form-label"
              >Comment:</label
            >
            <div class="col-sm-8">
              <input
                v-model="comment.comment"
                type="text"
                required
                class="form-control"
                placeholder="Comment"
              />
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12" align="center">
              <button class="btn btn-primary ml-3">Add Comment</button>
            </div>
          </div>
    </form>
  </div>
</template>

<style lang="scss">
html {
  @import "@/assets/scss/_style.scss";
}
</style>

<script >

export default {
  name: 'comment-form',
  props: ['path', 'blogSlug', 'comments'],
  data() {
    return {
      comment: {}
    };
  },
  mounted() {
    this.comment.path = this.path;
    this.comment.slug = this.blogSlug;
  },
  methods: {
    async submitForm(e) {
      e.preventDefault();

      const comment = await this.$api.post('blog/comment-store', this.comment);
      if (comment.status == 200) {
        this.comments.unshift(comment.data.data);
      }
    }
  }
};
</script>