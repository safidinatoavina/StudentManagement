<template>
    <div>
        <DataTable filterDisplay="menu"
            v-if="EtudiantNoteFilter.getListeEtudiantJury.length"
            :filters="filters"
            :paginator="true" :rows="10" :value="EtudiantNoteFilter.getListeEtudiantJury" responsiveLayout="scroll" 
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[10,20,50]"
        >

            <template #header>
                <div class="d-flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Recherche" />
                    </span>
                    <a class="btn btn-primary" style="cursor:pointer" @click="Operation.getListEtudinatPdf()">
                        <span>Téléchargé pdf</span>
                        <Spinner v-if="Operation.getPending.pdf" class-color="text-white"/>
                    </a>
                </div>
            </template>


            <Column :sortable="true" field="numeroInscription" header="N° Inscription"></Column>
            <Column :sortable="true" field="etudiant.personne.nom" header="Nom"></Column>
            <Column :sortable="true" field="etudiant.personne.prenom" header="Prénom(s)"></Column>
            <Column :sortable="true" field="annee_universitaire.valeur" header="Année universitaire"></Column>
            <Column :sortable="true" field="parcour.parcour" header="Parcours"></Column>
            <Column :sortable="true" field="status.abreviation" header="Status"></Column>

        </DataTable>
        <div class="h1 my-5 text-center" v-else>
            Liste vide
        </div>
    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useOperation } from '@/stores/operation'
import { ref } from 'vue'
import { useEtudiantNoteFilter } from '@/stores/filtre_etudiant'


const Operation = useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const EtudiantNoteFilter=useEtudiantNoteFilter()

</script>
