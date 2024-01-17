<template>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">

        <a @click="sidebarToggler"
        href="#" class="sidebar-toggler flex-shrink-0">
            <i class="fa fa-bars"></i>
        </a>
        <RouterLink :to="{name: 'home'}" 
        title="Espace etudiant"
        href="#" class=" ms-2 sidebar-toggler flex-shrink-0">
            <i class="fa fa-users"></i>
        </RouterLink>
        <!-- <form class="d-none d-md-flex ms-4">
            <input class="form-control border-0" type="search" placeholder="Search">
        </form> -->
        <div class="navbar-nav align-items-center ms-auto">
            <!-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-envelope me-lg-2"></i>
                    <span class="d-none d-lg-inline-flex">Message</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item text-center">See all message</a>
                </div>
            </div> -->

            <NotificationHeader />
            
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img class="rounded-circle me-lg-2" 
                    :src="authStore.getUser.personne.photo?.url||'/img/user.jpg'"
                     alt="profile" style="width: 40px; height: 40px;">
                    <span class="d-none d-lg-inline-flex">{{ authStore.getUser.personne.nom }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <RouterLink class="dropdown-item" :to="{name:'profile'}">Mon Profil</RouterLink>
                    <!-- <a href="#" class="dropdown-item">Settings</a> -->
                    <a href="#" class="dropdown-item" @click="authStore.logout()">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

</template>

<script setup>
import NotificationHeader from '../../../components/section/notification/NotificationHeader.vue'

import { onBeforeMount, onMounted } from '@vue/runtime-core'
import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router';


const authStore=useAuthStore()

const sidebarToggler=()=>{
    //element dans slidebar component
    let sidebar=document.querySelector('div#sidebar.sidebar')
    sidebar?.classList?.toggle('open')
    //element dans layoutProf component
    let sidebar_content=document.querySelector('div.content-prof')
    sidebar_content.classList.toggle('open')
    
}

const removeOpen=()=>{
    let sidebar=document.querySelector('div#sidebar.sidebar')
    sidebar?.classList?.remove('open')

    let sidebar_content=document.querySelector('div.content-prof')
    sidebar_content?.classList?.remove('open')
}



onMounted(()=>{
    removeOpen()
})

</script>
