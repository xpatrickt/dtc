<template>
  <div class="app-content content" :style="'background-image: url('+$baseUrlVue('img/otros/backgroundLogin.jpeg')+'); background-size: cover;'">
    <div class="content-wrapper">
      <div class="content-header row"></div>
      <div class="content-body">
        <section class="flexbox-container ">
          <router-view></router-view>
        </section>
      </div>
    </div>
  </div>
</template>

<script>

export default {

};
</script>


<style >

</style>
