<template>
    <section class="m-2" v-if="!Data.pendings.get_jury_parcour">

<h3 class="p-3 text-center">
    Espace Pour le Jury
</h3>
<h5 class="text-primary text-center">
    <span class="text-decoration-underline">Parcours</span> : {{ Auth.getUser?.parcours?.find((el)=>el.id==route.params.parcour)?.parcour||'aucun' }}
</h5>
        
        <h4 class="text-primary text-decoration-underline ">
            Que voulez vous ?
        </h4>


        <ul class="nav nav-tabs ">

            <li class="nav-item">
                <a @click="showAction('liste_etudiant')"
                :class="[action.liste_etudiant?'active':'']"
                class="nav-link text-capitalize" >étudiants</a>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" 
                :class="[(action.liste_validation_ue||action.liste_ue_moyenne||action.liste_moyenne_semestre||action.liste_validation_semestre||action.resultat_base)?'active':'']"
                data-bs-toggle="dropdown" 
                role="button" aria-expanded="false">
                    Résultats
                </a>
                <ul class="dropdown-menu" id="dropdown-show">
                    <li>
                        <a class="dropdown-item" 
                        @click="showAction('resultat_base')"
                        :class="[action.resultat_base?'active':'']"
                        >
                            Resultat de base
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                        @click="showAction('liste_ue_moyenne')"
                        :class="[action.liste_ue_moyenne?'active':'']"
                        >
                            Moyennes par Ue
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                        @click="showAction('liste_validation_ue')"
                        :class="[action.liste_validation_ue?'active':'']"
                        >
                            Validations par Ue
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                        @click="showAction('liste_moyenne_semestre')"
                        :class="[action.liste_moyenne_semestre?'active':'']"
                        >
                            Moyennes par semestre
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                        @click="showAction('liste_validation_semestre')"
                        :class="[action.liste_validation_semestre?'active':'']"
                        >
                            Validation par semestre
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item">
                <a @click="showAction('publication_resultat')"
                :class="[action.publication_resultat?'active':'']"
                class="nav-link">Publication resultat/UE</a>
            </li>

            <li class="nav-item" v-if="false" >
                <a @click="showAction('critere_validation')"
                :class="[action.critere_validation?'active':'']"
                class="nav-link">Critère de validation et publication/semestre</a>
            </li>

            <li class="nav-item">
                <a @click="showAction('passage_niveau')"
                :class="[action.passage_niveau?'active':'']"
                class="nav-link">Passage à niveau/publication</a>
            </li>

            <li class="nav-item">
                <a @click="showAction('resultat_passage')"
                :class="[action.resultat_passage?'active':'']"
                class="nav-link">Liste passant/redoublant</a>
            </li>



        </ul>


    <div v-if="action.liste_etudiant">
        <div class="mt-3" v-if="!EtudiantNoteFilter.getPending.result_jury">
            <ListeEtudiantJury />
        </div>
        <div class="mt-5" v-else >
            <Loading />
        </div>
    </div>

    <div v-else-if="action.liste_validation">
        <div class="mt-3" v-if="EtudiantNoteFilter.getShowResult">

            <DataTable filterDisplay="menu"
                v-if="EtudiantNoteFilter.getResultsJury.length"
                :filters="filters"
                :paginator="true" :rows="10" :value="EtudiantNoteFilter.getResultsJury" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >
    
                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Recherche" />
                        </span>
                        <a class="btn btn-primary" style="cursor:pointer" @click="EtudiantNoteFilter.generatePdf()">
                            <span>Téléchargé pdf</span>
                            <Spinner v-if="EtudiantNoteFilter.getPending.pdf" class-color="text-white"/>
                        </a>
                    </div>
                </template>


                <Column :sortable="true" field="historique.numeroInscription" header="N° Inscription"></Column>
                <Column :sortable="true" field="historique.etudiant.personne.nom" header="Nom"></Column>
                <Column :sortable="true" field="historique.etudiant.personne.prenom" header="Prénom(s)"></Column>
                <Column :sortable="true" field="historique.annee_universitaire.valeur" header="Année universitaire"></Column>
                <Column :sortable="true" field="historique.parcour.parcour" header="Parcours"></Column>
                <Column :sortable="true" field="matiere.matiere" header="Matiere"></Column>
                <Column :sortable="true" field="historique.status.abreviation" header="Status"></Column>
                <Column :sortable="true" field="semestre.semestre" header="Semestre"></Column>
                <Column :sortable="true" field="session.session" header="Session"></Column>
                <Column  header="Note">
                <template #body="{data}">

                    <div v-if="!Auth.isAdmin">
                        {{ data.valeur }}
                    </div>

                    <div v-else>
                        <div>
                            {{ action.liste_validation?(data.valeur>=10?'V':'NV'):data.valeur }}
                        </div>
                    </div>
                </template>
                </Column>

            </DataTable>
            <h2 v-else class="text-center text-secondary my-5">
                Aucun résultat
            </h2>
        </div>

    </div>
    <div v-else-if="action.passage_niveau">
        <PassageANiveau />
    </div>

    <div v-else-if="action.liste_ue_moyenne">
        <ListValidationParUe type="Moyenne"/>
    </div>
    <div v-else-if="action.liste_validation_ue">
        <ListValidationParUe type="Validation" />
    </div>


    <div v-else-if="action.resultat_base">
        <BaseExcel/>
    </div>
    
    <div v-else-if="action.critere_validation && false">
        <CritereValidation />
    </div>

    <div v-else-if="action.liste_moyenne_semestre">
        <ListValidationParSemestre type="moyenne" />
    </div>
    <div v-else-if="action.liste_validation_semestre">
        <ListValidationParSemestre type="validation" />
    </div>
    <div v-else-if="action.publication_resultat">
        <PublicationResultat />
    </div>
    <div v-else-if="action.resultat_passage">
        <ListePassageNiveau />
    </div>
    </section>
    <div v-else class="mt-5">
        <Loading/>
    </div>
</template>

<script setup>
import ListePassageNiveau from '../../components/section/jury/ListePassageNiveau.vue'
import ListeEtudiantJury from '../../components/section/jury/ListeEtudiantJury.vue'
import CritereValidation from '../../components/section/tools/CritereValidation.vue'
import BaseExcel from '../../components/section/tools/BaseExcel.vue'
import PublicationResultat from '../../components/section/tools/PublicationResultat.vue'
import PassageANiveau from '../../components/section/tools/PassageANiveau.vue'
import ListValidationParSemestre from '../../components/section/tools/ListValidationParSemestre.vue'
import ListValidationParUe from '../../components/section/tools/ListValidationParUe.vue'
import Loading from '../../components/annimate/Loading.vue'
import Spinner from '../../components/annimate/Spinner.vue'
import { ref } from "@vue/reactivity";
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useData } from '@/stores/data'
import { useEtudiantNoteFilter } from '@/stores/filtre_etudiant'
import {  onBeforeMount, onMounted } from "@vue/runtime-core";
import { useAuthStore } from '@/stores/auth'
import { useRoute,onBeforeRouteUpdate } from 'vue-router'


const route=useRoute()
const Auth=useAuthStore()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


const action=ref({
    add_note:false,
    liste_etudiant: false,
    liste_validation:false,
    liste_note:false,
    resultat_base:false,
    liste_ue_moyenne:false,
    liste_validation_ue:false,
    passage_niveau:false,
    statistique: false,
    liste_moyenne_semestre:false,
    liste_validation_semestre:false,
    publication_resultat:false,
    critere_validation: false,
    resultat_passage: false
})



const data_moddif=ref([])

const Data=useData()
const EtudiantNoteFilter=useEtudiantNoteFilter()



const modif=(id,valeur)=>{
    data_moddif.value[id]={id,valeur}
}

const cancel_modif=(id)=>{
    data_moddif.value[id]={}
}


const showAction=(action_val)=>{
        action.value={}
        action.value[action_val]=true
        if(action_val=='liste_etudiant'){
            EtudiantNoteFilter.setListeEtudiantJury()
        }else{
            //EtudiantNoteFilter.setResultsJury()
        }

        document.querySelector('#dropdown-show').classList.remove('show')
    }



onBeforeRouteUpdate((from,to)=>{

    if (from.params.parcour != to.params.parcour) {        
        action.value={
            add_note:false,
            liste_etudiant: false,
            liste_validation:false,
            liste_note:false,
            resultat_base:false,
            liste_ue_moyenne:false,
            liste_validation_ue:false,
            passage_niveau:false,
            statistique: false,
            liste_moyenne_semestre:false,
            liste_validation_semestre:false,
            publication_resultat:false,
            critere_validation: false
        }
    }

})


onBeforeMount(()=>{
    Data.getJuryParcour()
})

onMounted(()=>{
    window.scrollTo(0,0)
})

</script>

<style scoped>
li.nav-item {
    cursor: pointer;
}
</style>