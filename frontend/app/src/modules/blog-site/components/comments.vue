<template>
  <div>
    <div class="card">
      <div class="card-body">
        <div class="form-group row">
          <label for="exampleInputEmail1" class="col-sm-4 col-form-label"
            >Name:</label
          >
          <div class="col-sm-8">{{ comment.name }}</div>
        </div>
        <div class="form-group row">
          <label for="exampleInputEmail1" class="col-sm-4 col-form-label"
            >Comment:</label
          >
          <div class="col-sm-8">{{ comment.comment }}</div>
        </div>
        <div class="form-group row">
          <label for="exampleInputEmail1" class="col-sm-4 col-form-label"
            >Posted on:</label
          >
          <div class="col-sm-8">{{ comment.date_time }}</div>
        </div>
      </div>


      <div class="card-footer">
        Reply: <br />
        <comment-form :blogSlug="blogSlug" :path="path" :comments="comment.comments" />
      </div>
      <div class="comments-box" v-if="comment.comments.length > 0">
        <br />
        <comments v-for="(comment, idx) in comment.comments" :id="idx" :path="setPath(idx)" :blogSlug="blogSlug" :comment="comment" ></comments>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
html {
  @import "@/assets/scss/_style.scss";
}

.comments-box {
    padding-left: 2rem;    
}
</style>

<script >
import CommentForm from'./commentForm';

export default {
  name: 'comments',
  components: {
    'comment-form': CommentForm
  },
  props: ['id', 'blogSlug', 'comment', 'path', 'comments'],
  data() {
    return {

    }
  },
  mounted() {
    
  },
  methods: {
    setPath: function(id) {
      const path = Object.assign([], this.path);
      path.push(id);
      return path;
    }
    
  }
};
</script>