<template>
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container-fluid px-3">
            <router-link :to="{path:'/'}" class="navbar-brand" >
                <img src="/logo/logo-science.jpg" class="logo-faculte"  />
                <span class="text-uppercase text-brand fs-6 fs-sm-5 ">
                    faculté des sciences
                </span>
            </router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" v-if="false">Accueil</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name:'about'}" v-if="false" class="nav-link">A propos</router-link>
                    </li>
                    <li class="nav-item" v-if="false">
                        <a class="nav-link" href="#services">Affichage</a>
                    </li>
                    <li class="nav-item d-none">
                        <router-link :to="{name:'evenement'}" class="nav-link text-capitalize">évènement</router-link>
                    </li>
                </ul>

                    <div class="d-flex justify-content-end">
                        <div class="btn btn-etudiant text-white ms-1 ms-lg-4" @click="$router.push({name:'public-filter'})">
                            Etudiant
                        </div>
                        <ModalBtn v-if="!authStore.isAuth"
                        id-modal="login-prof">
                            <a href="#" @click="authStore.refreshCaptcha()" class="btn btn-brand ms-1 ms-lg-3">Professeur</a>
                        </ModalBtn>
                        <RouterLink v-else to="/admin"
                        href="#" class="btn btn-brand ms-1 ms-lg-3">
                            Professeur
                        </RouterLink>
                    </div>

            </div>
        </div>
    </nav>
</template>

<script setup>
import ModalBtn from '@/components/modal/ModalBtn.vue'
import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router'

const authStore=useAuthStore()

</script>


<style scoped>
 .btn-etudiant{
    background-color: #ef9320;
 }

 .logo-faculte{
    height:50px;
    width:50px;
 }

 @media  (max-width:320px) {
    .logo-faculte{
        height:30px;
        width:30px;
    }

    .fs-6{
        font-size: 16px;
    }

  }
</style>
