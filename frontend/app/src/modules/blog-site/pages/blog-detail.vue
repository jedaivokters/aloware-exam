<template>
  <div id="blog_details">
    <Header />

    <div class="container">
      <div class="card">
        <div class="card-body">
          <h1 align="center">{{ blog.title }}</h1>
          <br />
          <comments v-for="(comment, idx) in blog.comments.comments" :id="idx" :blogSlug="blog.slug" :comment="comment" :level=0 />
          <hr />
          <b>New Comment</b> <br />
          <comment-form :blogSlug="blog.slug" :level=0 :id=0 />
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
import Comments from'../components/comments';
import CommentForm from'../components/commentForm';

export default {
  components: {
    Header,
    'comments': Comments,
    'comment-form': CommentForm
  },
  data() {
    return {
      blog: {
        slug: '',
        comments: {
          comments: []
        }
      }
    };
  },
  async mounted() {
    const slug = this.$route.params.slug;

    this.blog = await this.$api.get('blog/comments',{
      params: { slug }
      }).then(r => r.data.data[0]);
  },
  methods: {},
};
</script>