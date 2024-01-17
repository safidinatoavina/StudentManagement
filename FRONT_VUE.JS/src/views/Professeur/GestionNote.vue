<template>
    <div class="m-3" v-if="!Note.getPending">


        <InformationExam :NoteInfo="matiereSelected" v-if="matiereSelected" class="mt-3"/>

        <div class="m-3">

            <h4 class="my-4">Que voulez vous faire ?</h4>


            <ul class="nav nav-tabs ">
                <li class="nav-item">
                    <a @click="showAction('add_note')"
                    :class="[action.add_note?'active':'']"
                    class="nav-link" >Ajout Note</a>
                </li>

                <li class="nav-item">
                    <a @click="showAction('liste_etudiant')"
                    :class="[action.liste_etudiant?'active':'']"
                    class="nav-link" >Liste des étudiants</a>
                </li>

                <li class="nav-item" v-if="false">
                    <a @click="showAction('liste_validation')"
                    :class="[action.liste_validation?'active':'']"
                    class="nav-link" >Liste des Validations</a>
                </li>

                <li class="nav-item">
                    <a @click="showAction('liste_note')"
                    :class="[action.liste_note?'active':'']"
                    class="nav-link">Liste des Notes</a>
                </li>

                <li class="nav-item">
                    <a @click="showAction('validation_ue')"
                    :class="[action.validation_ue?'active':'']"
                    class="nav-link">Validation UE</a>
                </li>

                <li class="nav-item">
                    <a @click="showAction('statistique')"
                    :class="[action.statistique?'active':'']"
                    class="nav-link">Statistiques</a>
                </li>

            </ul>

        </div>

        <div v-if="action.add_note">


            <div class="d-flex justify-content-end me-4">
                <label class="btn btn-primary me-1">
                    <IconUpload class="me-1"/> 
                    Importer
                    <Spinner v-if="DataIO.getPending.import_note" />
                    <input type="file" class="d-none" @change="importNote" ref="fileInput" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                </label>
                <button class="btn btn-primary" 
                @click="DataIO.exportTemplateNote({parcour_id,matiere_id})"
                >
                    Template <Spinner v-if="DataIO.getPending.export_template_note" />
                </button>
            </div>

            <Etudiant-search :matiere="parseInt(matiere_id)" :parcour="parseInt(parcour_id)"></Etudiant-search>


            <h5 class="text-info text-decoration-underline">
                Statistique des étudiants
            </h5>

            <div class="ms-3">
                <h5> 
                    <span class="text-decoration-underline">Etudiant Noté</span> : 
                    <strong class="text-primary">{{ countEtudiantNote || 0 }}</strong>
                </h5>
                <h5>
                    <span class="text-decoration-underline">Fffectif des étudiants</span> : 
                    <strong>{{ Note.getEtudiants?.[parcour_id]?.length }}</strong>
                </h5>
            </div>



            <section class="form-group m-2">
                <DataTable filterDisplay="menu"
                    :filters="filters"
                    :paginator="true" :rows="4" :value="Note.getNotes?.[parcour_id]?.filter(el=>el.is_set==1)||[]" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[4,10,20,50]"
                >
        
                    <template #header>
                        <div class="flex justify-content-between">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Recherche" />
                            </span>
                        </div>
                    </template>


                    <Column :sortable="true" field="historique.numeroInscription" header="N° d'inscription"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.nom" header="Nom"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.prenom" header="Prénom(s)"></Column>
                    <Column :sortable="true" field="valeur" header="Note"></Column>

                </DataTable>

            </section>

        </div>

        <div class="my-2">
            <ListeEtudiant v-if="action.liste_etudiant" 
                :params="{
                    header:[] ,
                    parcour_id:matiereSelected?.parcour?.id||parcour_id,
                    matiere_id:matiereSelected?.id||matiere_id,
                    session_id:Data.en_cours?.session.id,
                    semestre_id:Data.en_cours?.semestre.id,
                    annee_universitaire_id:Data.annees?.id
                }"
            />
        </div>

        <div class="my-2">
            <ListeEtudiant v-if="action.liste_validation" :validation="true"
            :params="{
                    header:['validation'] ,
                    parcour_id:matiereSelected?.parcour?.id || parcour_id,
                    matiere_id:matiereSelected?.id || matiere_id,
                    session_id:Data.en_cours?.session.id,
                    semestre_id:Data.en_cours?.semestre.id,
                    annee_universitaire_id:Data.annees?.id
                }"
            />
        </div>

        <div class="my-2">
            <ListeEtudiant v-if="action.liste_note" :note="true"
            :params="{
                    header:['note'] ,
                    parcour_id:matiereSelected?.parcour?.id || parcour_id,
                    matiere_id:matiereSelected?.id || matiere_id,
                    session_id:Data.en_cours?.session.id,
                    semestre_id:Data.en_cours?.semestre.id,
                    annee_universitaire_id:Data.annees?.id
                }"
            />
        </div>

        <div class="my-2">

            <div v-if="action.validation_ue">
                <ValidationUeMatiere :matiere_id="parseInt(matiere_id)" />
            </div>

        </div>

        <div class="my-2">
            <StatistiqueNoteProf :parcour="$route.params.parcour||parcour" v-if="action.statistique" />
        </div>

    </div>
    <div v-else class="text-center my-5">
        <Loading />
    </div>
</template>

<script setup>
import ValidationUeMatiere from '../../components/section/tools/ValidationUeMatiere.vue'
import IconUpload from '../../components/icons/IconUpload.vue'

import StatistiqueNoteProf from '../../components/section/tools/StatistiqueNoteProf.vue'
import Loading from '../../components/annimate/Loading.vue'
import ListeEtudiant from '../../components/section/tools/ListeEtudiant.vue'
import InformationExam from '../../components/section/tools/InformationExam.vue'
import EtudiantSearch from '../../components/section/tools/EtudiantSearch.vue'
import Spinner from '../../components/annimate/Spinner.vue'
import Error from '../../components/section/error/Error.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { useToast } from "primevue/usetoast";
import { useData } from '@/stores/data'
import { useEtudiant } from '@/stores/etudiant'
import { useNote } from '@/stores/note'
import { ref } from '@vue/reactivity'
import {  computed, onBeforeMount, onBeforeUnmount, onMounted, watch, watchEffect } from '@vue/runtime-core'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useDataIO } from '@/stores/data_io'


const Etudiant=useEtudiant()
const Data=useData()
const Note=useNote()
const toast=useToast()
const AuthStore=useAuthStore()
const DataIO=useDataIO()
const fileInput=ref('')
const parcour_id=ref('')
const matiere_id=ref('')


const action=ref({
    add_note:false,
    liste_etudiant: false,
    liste_validation:false,
    liste_note: false,
    validation_ue: false,
    statistique: false
})

const route=useRoute()
const router=useRouter()

const props=defineProps({
  parcour:Number,
  matiere: Number  
})

const countEtudiantNote=computed(()=>Note.getNotes?.[parcour_id.value]?.filter(el=>el.is_set==1).length)


const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const matiereSelected=computed(()=> AuthStore.getMatieres.find((element)=>element.id==matiere_id.value))

const importNote=(event)=>{

    const file = event.target.files[0]
    if (!file) {
        alert('fichier invalide')
        return
    }

    var formData = new FormData()
    formData.append('file',file)

    DataIO.importNote(formData,props.matiere,props.parcour)

    fileInput.value.value=''

}

watchEffect(()=>{
    Note.getListEtudiantByParcours()
})



watchEffect(()=>{
    if(matiereSelected.value?.id)
        Note.setNotes(matiereSelected.value?.id || matiere_id.value)
})

const showAction=(action_val)=>{
    action.value={}
    action.value[action_val]=true
}


watchEffect(()=>{
    parcour_id.value=route.params.parcour || props.parcour
    matiere_id.value=route.params.matiere || props.matiere
    window.scrollTo(0,0);
})


</script>

<style scoped>
li.nav-item {
    cursor: pointer;
}
</style>
