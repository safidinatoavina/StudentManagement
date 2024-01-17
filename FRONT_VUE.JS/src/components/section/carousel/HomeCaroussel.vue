<template>
  <!--   :settings="settings"   :breakpoints="breakpoints"   -->

  <div class="position-relative">
    <div id="overlay"></div>
    <div class="position-absolute text-banner fw-bolder" style="z-index:3">
      <div class="text-center">
        <h1 class="fw-bolder  fs-2 text-white ">
          Bienvenue sur le site de la Faculté des Sciences d'Antananarivo !
        </h1>
        <h3 class="fs-5 d-none">
          La Faculté des Sciences d'Antananarivo est l'une des principales institutions universitaires de Madagascar,
           offrant une formation de qualité en mathématiques, physique, chimie, biologie, informatique et plus encore.
        </h3>
      </div>
    </div>
    <Carousel 
    :wrap-around="true"
    :autoplay="5000"
    >

      <Slide v-for="slide in 3" :key="`image-home-slide-${slide}`">
        <div class="carousel__item">
          <img :src="`/img/ankatso/ankatso${slide}.jpg`" class="rounded" />
        </div>
      </Slide>

      <template #addons>
        <Pagination />
        <Navigation class="text-white" />

      </template>

    </Carousel>
  </div>
</template>
  
  <script setup>
  import { ref } from 'vue'
  import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
  
  import 'vue3-carousel/dist/carousel.css'
  
  const currentSlide=ref(0)

  const slideTo=(val)=>{
    currentSlide.value=val
  }

  </script>

  
  <style scoped>
  .carousel__item {
    width: 100%;
  }


  h1 {
    text-shadow: 2px 2px var(--brand);
  }
  .text-banner{
    top: 5rem;
    left: 2rem;
    right: 2rem;
  }

  @media  (max-width:450px) {
    .fs-5{
      font-size:18px!important;
    }

    .text-banner{
      top: 1rem;
      left: 2rem;
      right: 2rem;
    }
  }

  @media  (max-width:360px) {
    .fs-5{
      font-size:16px!important;
    }

  }

  @media  (max-width:320px) {
    .fs-3{
      font-size:17px!important;
    }

    .fs-5{
      font-size:15px!important;
    }

  }
  

  #overlay {
    position: absolute; /* Sit on top of the page content */
    display: block; /* Hidden by default */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
  }

  </style>
  
  