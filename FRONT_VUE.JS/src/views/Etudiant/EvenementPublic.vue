<template>
    <div class="container-fluid">
        <h6 class="text-center my-3 text-secondary">Bienvenue ! Pour changer, faites glisser votre doigt de gauche à droite ou de droite à gauche</h6>
        <Carousel v-if="PublicEvenements.getEvenements.length"
        :wrap-around="true"
        :autoplay="10000"
        >

        <Slide v-for="evenement in PublicEvenements.getEvenements" :key="`evenement-for-${evenement.id}`">
            <div class="carousel__item ckeditor my-4">
                <h1 class="text-decoration-underline">{{ evenement.titre }}</h1>
                <div class="ck-content" v-html="evenement.contenu">
                </div>
            </div>
        </Slide>

        <template #addons>
            <Pagination />
            <Navigation class="text-brand" />

        </template>

        </Carousel>
    </div>
</template>


<script setup>
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import { usePublicEvenement } from "@/stores/public_show"

import 'vue3-carousel/dist/carousel.css'

const PublicEvenements=usePublicEvenement()

</script>

<style scoped>

@import '@/assets/ckeditor.css';


</style>

