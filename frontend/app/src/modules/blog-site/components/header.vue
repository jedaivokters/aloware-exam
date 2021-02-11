<template>
  <div >
    <!-- ======= Header ======= -->
    <header
      id="header"
      class="mb-3 d-flex justify-content-center align-items-center header-fix"
    >
      <nav class="nav-menu">
        <ul>
          <li >
            <router-link :to="{ name: 'blogs'}" >Blogs</router-link>
          </li>
		  <li>
			<router-link :to="{ name: 'blog-add'}" >Add Blog</router-link>
          </li>
        </ul>
      </nav>
      <!-- .nav-menu -->
    </header>
    <!-- End Header -->
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
export default {
  
  data() {
    return {}
  },
};
</script>