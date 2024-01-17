import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Etudiant/Home.vue'
import PublicFiltreEtudiant from '../views/Etudiant/PublicFiltreEtudiant.vue'
import HistoriqueList from '../views/Etudiant/page/HistoriqueList.vue'
import ResulatValidation from '../views/Etudiant/page/ResulatValidation.vue'
import EtudiantReinscription from '../views/Professeur/EtudiantReinscription.vue'
import About from '../views/Etudiant/About.vue'
import EvenementPublic from "../views/Etudiant/EvenementPublic.vue"
import HomeProf from '../views/Professeur/HomeProf.vue'
import ResponsableParcour from "../views/Professeur/ResponsableParcour.vue";
import VerificationDonnee from "../views/Professeur/VerificationDonnee.vue";
import Configuration   from "../views/Professeur/Configuration.vue"
import GestionProf from '../views/Professeur/GestionProf.vue'
import GestionFaculte from '../views/Professeur/GestionFaculte.vue'
import GestionEtudiant from '../views/Professeur/GestionEtudiant.vue'
import NotificationListe from '../views/Professeur/NotificationListe.vue'
import NotificationDetails from '../views/Professeur/NotificationDetails.vue'
import NoteFilter from '../views/Professeur/NoteFilter.vue'
import EtudiantFilterJury from '../views/Professeur/EtudiantFilterJury.vue'
import StatistiqueNote from '../views/Professeur/StatistiqueNote.vue'
import EtudiantFilterBySecretaire from '../views/Professeur/EtudiantFilterBySecretaire.vue'
import OperateurSaisie from '../views/Professeur/OperateurSaisie.vue'
import VerificationAjoutNoteByJury from '../views/Professeur/VerificationAjoutNoteByJury.vue'
import Layout from '@/layout/Etudiant/Layout.vue'
import LayoutProf from '@/layout/professeur/LayoutProf.vue'
import AddEtudiant from '@/views/pages/AddEtudiant.vue'
import EditEtudiant from '@/views/pages/EditEtudiant.vue'
import AddAdmin from '@/views/pages/AddAdmin.vue'
import Profile from '@/views/pages/Profile.vue'
import EditAdmin from '@/views/pages/EditAdmin.vue'
import GestionNote from '@/views/Professeur/GestionNote.vue'
import GestionNoteTP from '@/views/Professeur/GestionNoteTP.vue'
import GestionEvenement from '@/views/Professeur/GestionEvenement.vue'
import CreateEvenement from '@/components/section/pages/CreateEvenement.vue'
import EditEvenement from '@/components/section/pages/EditEvenement.vue'
import Error404 from '@/views/error/Error404.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Layout,
      children: [
        {
          path: '/',
          name: 'home',
          component: Home
        }
        ,{
          path: '/student/public-filter',
          name: 'public-filter',
          component : PublicFiltreEtudiant
        },
        {
          path:'/about',
          name: 'about',
          component: About
        },
        {
          path:'/evenement',
          name: 'evenement',
          component: EvenementPublic
        },
        {
          path:'/historique-liste/:id',
          name: 'historique-liste',
          component:HistoriqueList
        },
        {
          path:'/resulat-validation/:historique',
          name: 'resulat-validation',
          component: ResulatValidation
        },
        { 
          path: '/:pathMatch(.*)*', 
          name: 'not-found', 
          component: Error404 
        },

      ]
    },

    /*
    Professeur route
    */
    {
      path: '/admin',
      component:LayoutProf,
      children:[
        {
          path:'/admin',
          name:'home-prof',
          component: HomeProf
         },
         {
          path:'/admin/gestion-admin',
          name:'gestion-admin',
          component: GestionProf
         },
         {
          path: '/admin/add-admin',
          name:'add-admin',
          component: AddAdmin
         },
         {
          path: '/admin/edit-admin/:id',
          name:'edit-admin',
          component: EditAdmin
         }
         ,
         {
          path:'/admin/gestion-etudiant',
          name: 'gestion-etudiant',
          component : GestionEtudiant
         },
         {
          path:'/admin/gestion-faculte',
          name: 'gestion-faculte',
          component : GestionFaculte
         },
         {
          path:'/admin/profile',
          name: 'profile',
          component : Profile
         },
         {
          path:'/admin/ajout-etudiant',
          name: 'ajout-etudiant',
          component : AddEtudiant
         }, 
         {
          path:'/admin/edit-etudiant/:id',
          name: 'edit-etudiant',
          component : EditEtudiant
         },
         {
          path:'/admin/note-filter',
          name: 'note-filter',
          component : NoteFilter
         },
         {
          path:'/admin/etudiant-filter-jury/:parcour',
          name: 'etudiant-filter-jury',
          component : EtudiantFilterJury
         },
         {
          path:'/admin/controle-jury/:parcour',
          name: 'controle-jury',
          component : VerificationAjoutNoteByJury
         },
         {
          path:'/admin/etudiant-filter-secretaire',
          name: 'etudiant-filter-secretaire',
          component : EtudiantFilterBySecretaire
         },
         {
          path:'/admin/ajout-note/:parcour/:matiere',
          name: 'ajout-note',
          component : GestionNote
         },
         {
          path:'/admin/ajout-note-tp/:parcour/:tp',
          name: 'ajout-note-tp',
          component : GestionNoteTP
         },
         {
          path:'/admin/etudiant-reinscription',
          name: 'etudiant-reinscription',
          component : EtudiantReinscription
         },
         {
          path:'/admin/gestion-evenement',
          name: 'gestion-evenement',
          component : GestionEvenement
         },
         {
          path:'/admin/create-evenement',
          name: 'create-evenement',
          component : CreateEvenement
         },
         {
          path: '/admin/edit-evenement/:evenement',
          name: 'edit-evenement',
          component: EditEvenement
         },
         {
          path: '/admin/responsable-parcour/:parcour',
          name: 'responsable-parcour',
          component: ResponsableParcour
         },
         {
          path: '/admin/operateur-siasie',
          name: 'operateur-siasie',
          component: OperateurSaisie
         },
         {
          path: '/admin/verification-donnee',
          name: "verification-donnee",
          component: VerificationDonnee
        },
        {
          path: '/admin/suppression-notes',
          name: "suppression-notes",
          component: Configuration
        },
        {
          path: '/admin/statistique-note',
          name: "statistique-note",
          component: StatistiqueNote
        },
        {
          path:'/admin/notifications',
          name: 'notifications',
          component: NotificationListe
        },
        {
          path:'/admin/detail-notifications/:notification',
          name: 'detail-notifications',
          component: NotificationDetails
        },
        {
          path:'/admin/page-introuvable',
          name: 'error-404',
          component : Error404
        }
      ]
    },


  ]
})

export default router

