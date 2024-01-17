<template>
<div v-if="!Data.pendings.loading_data" class="container-fluid position-relative bg-white d-flex p-0">

    <Slidebar></Slidebar>

            <!-- Content Start -->
        <div class="content-prof">

            <Navbar></Navbar>

            <main class="content-view">
                <RouterView />
            </main>
            
            <Footer></Footer>
        </div>
        <!-- Content End -->


        <!-- Back to Top
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
     -->
  
</div>
<main class="content-view d-flex align-items-center justify-content-center" v-else>
    <Loading img-size="170px" spinner-size="200px" />
</main>
</template>

<script setup>
import Loading from '../../components/annimate/Loading.vue'
import { onBeforeMount } from '@vue/runtime-core'
import { useUser } from '@/stores/user'
import { useData } from '@/stores/data'
import { useAuthStore } from '@/stores/auth'
import { useEtudiant } from '@/stores/etudiant'
import { useAdmin } from '@/stores/admin'
import { useRole } from '@/stores/role'
import { useNotification } from '@/stores/notification'
import Footer from './element/Footer.vue'
import Navbar from './element/Navbar.vue'
import Slidebar from './element/Slidebar.vue'

const User=useUser()
const Etudiant=useEtudiant()
const Data=useData()
const Auth=useAuthStore()
const Role=useRole()
const Admin = useAdmin()
const Notification=useNotification()

onBeforeMount(()=>{
    User.getListUsers()
    Auth.setMatieres()
    Auth.setMatieresTP()
    Etudiant.getListEtudiants()
    Data.setDataFaculte()
    Role.setRoles()
    Admin.setAdmins()
    setInterval(()=>Notification.fetchNotifications(),40*1000)
})

</script>

<style >
@import url('@/assets/style-prof.css');
</style>