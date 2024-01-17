<template>
      <div class="loading-page position-relative d-flex align-items-center justify-content-center" >
        <img src="/logo/logo-science.jpg" alt="" :style="{height: imgSize,width:imgSize}">

        <div class="spinner-border text-primary position-absolute" :style="{height: spinnerSize,width:spinnerSize}" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>

      </div>
</template>

<script setup>

const props=defineProps({
  imgSize: {
    type:String,
    default: '70px'
  },
  spinnerSize:{
    type:String,
    default: '100px'
  }

})

</script>

<style scoped>


</style>
