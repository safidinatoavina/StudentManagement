<template>
  <!-- Sidebar Start -->
  <div class="sidebar pe-2 pb-3" id="sidebar" >
    <nav class="navbar bg-light navbar-light pb-5 mb-5">
      <a href="#" class="navbar-brand mx-4 mb-3">
        <h3 class="text-primary fs-5 text-decoration-underline" >
          Espace Admin
        </h3>
      </a>
      <div class="d-flex align-items-center ms-4 mb-4">
        <div class="position-relative">
          <img
            class="rounded-circle"
            :src="AuthStore.getUser?.personne.photo?.url||'/img/user.jpg'"
            alt=""
            style="width: 40px; height: 40px"
          />
          <div
            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"
          ></div>
        </div>
        <div class="ms-3">
          <h6 class="mb-0">{{ AuthStore.getUser?.personne.nom }}</h6>
          <span>{{ AuthStore.getUser?.personne.prenom }}</span>
        </div>
      </div>
      <div class="navbar-nav w-100">

        <RouterLink :to="{name:'home-prof'}" 
        class="nav-item nav-link"
        ><i class="fa fa-tachometer-alt me-2"></i>Dashboard</RouterLink
        >

        <RouterLink :to="{name:'suppression-notes'}" v-if="AuthStore.isAdmin"
        class="nav-item nav-link"
        ><i class="fa fa-cog me-2"></i>Configuration</RouterLink
        >

        <div class="nav-item dropdown" v-if="AuthStore.isProfesseur">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            ><i class="fa fa-th me-2"></i>Matieres</a
          >

          <div class="dropdown-menu bg-transparent border-0 ">
          

            <a v-for="(matiere,index) in AuthStore.getMatieres" :key="`matiere-note-${index}`"
            class="dropdown-item second-list-item">

            <RouterLink 
            :to="{name:'ajout-note',params:{ parcour:matiere.parcour.id,matiere:matiere.id },}" class="dropdown-item">
             ({{ matiere.parcour.parcour }}) {{ matiere.matiere }} 
            </RouterLink>

            </a>


            <a v-for="(tp,index) in AuthStore.getMatieresTP" :key="`matiere-note-${index}`"
            class="dropdown-item second-list-item">

            <RouterLink 
            :to="{name:'ajout-note-tp',params:{ parcour:tp.matiere.parcour.id,tp:tp.id },}" class="dropdown-item">
             TP: ({{ tp.matiere.parcour.parcour }}) {{ tp.tp }} 
            </RouterLink>

            </a>

            
          </div>

        </div>


        <!-- <a  class="nav-item nav-link"
          ><i class="fa fa-laptop me-2"></i>Affichage</a
        > -->
        <RouterLink v-if="AuthStore.isAdmin" 
        :to="{name:'gestion-admin'}"  class="nav-item nav-link"
          ><i class="fa fa-user-tie me-2"></i>Gestion admin</RouterLink
        >


        <RouterLink v-if="AuthStore.isAdmin" 
         :to="{name:'gestion-etudiant'}"   class="nav-item nav-link"
          ><i class="fa fa-users me-2"></i>Gestion étudiant</RouterLink
        >
    

        <RouterLink v-if="AuthStore.isAdmin" :to="{name: 'gestion-faculte'}" class="nav-item nav-link"
          ><i class="fa fa-key me-2"></i>Gestion faculté</RouterLink
        >

        <RouterLink :to="{name:'note-filter'}" v-if="AuthStore.isAdmin || AuthStore.isJury"  class="nav-item nav-link"
          >
          <i class="fa fa-filter me-2"></i>Filtre notes</RouterLink
        >


 

        <div class="nav-item dropdown" v-if="AuthStore.isJury" >
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            ><i class="fa fa-tasks"></i>Espace jury</a
          >

          <div class="dropdown-menu bg-transparent border-0 ">
          

            <a v-for="parcour in AuthStore.Parcours" :key="`list-parcour-jury-${parcour.id}`" class="dropdown-item">
              <RouterLink 
                :to="{name:'etudiant-filter-jury',
                params:{
                  parcour: parcour.id
                }
                }" class="dropdown-item second-list-item"
              >
               {{ parcour.abreviation }}
              </RouterLink>
            </a>
            
          </div>

        </div>


        <div class="nav-item dropdown" v-if="AuthStore.isJury" >
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            ><i class="fa fa-tasks"></i>Contrôle des saisies</a
          >

          <div class="dropdown-menu bg-transparent border-0 ">
          

            <a v-for="parcour in AuthStore.Parcours" :key="`list-parcour-jury-controle-${parcour.id}`" class="dropdown-item">
              <RouterLink 
                :to="{name:'controle-jury',
                params:{
                  parcour: parcour.id
                }
                }" class="dropdown-item second-list-item"
              >
               {{ parcour.abreviation }}
              </RouterLink>
            </a>
            
          </div>

        </div>

        <RouterLink :to="{name:'etudiant-filter-secretaire'}"  v-if="AuthStore.isSecretaire"  class="nav-item nav-link"
          >
          <i class="fa fa-pen me-2"></i>Espace secrétariat</RouterLink
        >

        <RouterLink :to="{name:'statistique-note'}"  v-if="AuthStore.isAdmin"  class="nav-item nav-link"
          >
          <i class="fa fa-database me-2"></i>Statistique notes</RouterLink
        >

        <RouterLink :to="{name:'operateur-siasie'}"  class="nav-item nav-link" v-if="AuthStore.isOperateur"
          >
          <i class="fa fa-pen-square me-2"></i>Opérateur de saisie</RouterLink
        >

        <RouterLink :to="{name:'etudiant-reinscription'}" v-if="AuthStore.isAdmin || AuthStore.isReinscription" class="nav-item nav-link"
          >
          <i class="fa fa-keyboard me-2"></i>Espace réinscription</RouterLink
        >

        <div class="nav-item dropdown" v-if="AuthStore.isResponsableParcour" >
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            ><i class="fa fa-user-cog"></i>Responsable parcour</a
          >

          <div class="dropdown-menu bg-transparent border-0 ">
          

            <a v-for="parcour in AuthStore.Responsable" :key="`list-parcour-responsable-${parcour.id}`" class="dropdown-item">
              <RouterLink 
                :to="{name:'responsable-parcour',
                params:{
                  parcour: parcour.id
                }
                }" class="dropdown-item second-list-item"
              >
               {{ parcour.abreviation }}
              </RouterLink>
            </a>
            
          </div>

        </div>


        <RouterLink v-if="AuthStore.isAdmin"
          :to="{name:'verification-donnee'}" class="nav-item nav-link">
          <i class="fa fa-user-cog"></i> Verification Données
        </RouterLink>



        <div class="nav-item dropdown d-none" >
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            ><i class="fa fa-laptop me-2"></i>Affichages</a
          >

          <div class="dropdown-menu bg-transparent border-0 ">
          

            <a class="dropdown-item">

            <RouterLink 
            :to="{name:'gestion-evenement'}" class="dropdown-item">
             Gestion évènement
            </RouterLink>
            </a>

            <a class="dropdown-item">

            <RouterLink 
            :to="{name:'gestion-evenement'}" class="dropdown-item">
             Gestion affichage
            </RouterLink>
            </a>

            <a class="dropdown-item">

            <RouterLink 
            :to="{name:'gestion-evenement'}" class="dropdown-item">
             Gestion de bannière
            </RouterLink>
            </a>

            
          </div>

        </div>

        <!-- <a href="chart.html" class="nav-item nav-link"
          ><i class="fa fa-chart-bar me-2"></i>Courbes</a
        > -->

      </div>
    </nav>
  </div>
  <!-- Sidebar End -->
</template>


<script setup>

import { useAuthStore } from '@/stores/auth'
import { RouterLink } from 'vue-router';

const AuthStore=useAuthStore()


</script>

<style scoped>
.second-list-item{
  overflow-x: auto;
  font-size: 12px;
}
</style>

