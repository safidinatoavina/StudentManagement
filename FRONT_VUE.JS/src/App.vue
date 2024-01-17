
<template>

<div v-if="!pending">

  <Toast />

  <RouterView />

</div>


</template>


<script setup>
import Blog from './components/blog/Blog.vue'
import { RouterLink, RouterView,useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { onBeforeMount, onMounted, ref } from '@vue/runtime-core';
import Toast from 'primevue/toast';
import axios from 'axios'
import { usePublicFilterEtudiant } from '@/stores/public_etudiant.js'
import { useData } from '@/stores/data.js'

const Public=usePublicFilterEtudiant()
const authStore=useAuthStore()
const pending=ref(true)
const router=useRouter()
const Data=useData()

const updateAuth=()=>{
  let local_token=window.localStorage.getItem('token')
  if(local_token){

    authStore.setToken(local_token)
    pending.value=true
    axios.get('/auth')
    .then((response)=>{
      authStore.setUser(response.data)
      pending.value=false
    })
    .catch((error)=>{
      authStore.logout()
      pending.value=false

    })

  }
  else{
    pending.value=false

  }
}

onMounted(()=>{
  updateAuth()
})

onBeforeMount(()=>{
  Public.setAnnee()
})

</script>

<style>

  nav a.router-link-exact-active {
    color: var(--color-text);
  }

  nav a.router-link-exact-active:hover {
    background-color: transparent;
  }
  
  ul.navbar-nav a.router-link-exact-active{
    color: var(--brand)!important;
  }

</style>
