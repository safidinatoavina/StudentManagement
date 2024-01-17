<template>
    <section class="m-2">
        
        <h4 class="text-primary text-decoration-underline text-center">
            Filtre avec impression pdf du resultat
        </h4>

        <div class="container-fluid my-5">

            <FilterTools/>

        </div>

        <div class="my-3 d-flex justify-content-between">

            <button class="btn btn-primary ms-5" @click="EtudiantNoteFilter.setResults">
                 <span>Chercher</span> 
                 <Spinner v-if="EtudiantNoteFilter.getPending.result"/>
            </button>


        </div>


        <div class="mt-3" v-if="EtudiantNoteFilter.getShowResult">
            <DataTable filterDisplay="menu"
                v-if="EtudiantNoteFilter.getResults.length"
                :filters="filters"
                :paginator="true" :rows="10" :value="EtudiantNoteFilter.getResults" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >
    
                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Recherche" />
                        </span>
                        <a class="text-white btn btn-primary" style="cursor:pointer" @click="EtudiantNoteFilter.generatePdf()">
                            <span>Telecharger pdf</span>
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

                    <div >
                        {{ data.is_set?data.valeur:'X' }}
                    </div>

                </template>
                </Column>
                <Column header="Action">
                    <template #body="{data}">
                        <note-confirm-update :dataNote="{
                            id:data.id,
                            valeur:data.valeur
                        }" 
                        :historique="data.historique">
                        <div class="text-primary" style="cursor:pointer">
                            <i class="fa fa-edit fs-5"></i>
                        </div>
                        </note-confirm-update>
                    </template>
                </Column>

            </DataTable>
            <h2 v-else class="text-center text-secondary my-5">
                Aucun résultat
            </h2>
        </div>
    </section>
</template>

<script setup>
import NoteConfirmUpdate from '../../components/section/tools/NoteConfirmUpdate.vue'
import Spinner from '../../components/annimate/Spinner.vue'
import FilterTools from '../../components/section/tools/FilterTools.vue'
import { ref } from "@vue/reactivity";
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useData } from '@/stores/data'
import { useEtudiantNoteFilter } from '@/stores/filtre_etudiant'
import { useToast } from "primevue/usetoast";
import { useAuthStore } from '@/stores/auth'


const Auth=useAuthStore()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


const EtudiantNoteFilter=useEtudiantNoteFilter()


</script>
