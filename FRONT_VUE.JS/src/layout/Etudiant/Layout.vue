<template>
<div>

    <Header></Header>

    <ModalBody id-modal="login-prof">
        <Login />
    </ModalBody>


    <main style="min-height:100vh;">
        <RouterView />
    </main>

    <FooterEtudiant></FooterEtudiant>


</div>
</template>

<script setup>
import Header from './element/Header.vue'
import FooterEtudiant from './element/FooterEtudiant.vue'
import Login from '../../components/login/professeur/Login.vue'
import ModalBody from '../../components/modal/ModalBody.vue'
import { usePublicEvenement } from "@/stores/public_show"
import { onBeforeMount } from 'vue'

const PublicEvenements=usePublicEvenement()

onBeforeMount(()=>{
    PublicEvenements.fetch_active_evenements()
})

</script>
