<template>
        <footer>
        <div class="footer-top text-center">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-4 col-lg-3">
                            <img src="/logo/universite.png"  class="rounded"  alt="izay adala no toa an-drainy">
                    </div>
                    <div class="col-sm-8">
                        <div class="row justify-content-center ">

                            <div class="col-lg-6 text-center ">

                                <div class="col-auto conditions-section">
                                    <a href="#">privacy</a>
                                    <a href="#">terms</a>
                                    <a href="#">disclaimer</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">Copyright vicpra 2022. All rights Reserved</p> Distributed By <a
                hrefs="https://themewagon.com">ThemeWagon</a>
                <div >
                    Made with <span id="safidy-herimalala">(❤️)</span> by <a target="__blank" class="text-white"  href="https://spynoja.vercel.app/">Safidy Herimalala</a>
                    <span class="ms-2">/</span> <strong class="text-white">Luc Rabefihavanana</strong>
                </div>
        </div>
    </footer>
</template>


<script setup>

import gsap from "gsap"
import { onMounted } from "vue"


onMounted(()=>{
    gsap.to("#safidy-herimalala", {
    duration: 0.5, // durée de l'animation (en secondes)
    repeat: -1, // répéter l'animation indéfiniment
    opacity: 0, // passer l'opacité de 1 à 0 et vice versa
    yoyo: true // jouer l'animation en sens inverse après chaque itération
    });
})




</script>
