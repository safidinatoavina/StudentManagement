<template>
    <div class="my-1">

        <div class="mt-3" v-if="!Operation.getPending.liste_admis">
            <DataTable filterDisplay="menu" v-if="items.length"
                :filters="filters"
                :paginator="true" :rows="10" :value="items" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >

                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Recherche" />
                        </span>

                        <slot name="export"></slot>

                    </div>
                </template>


                <Column :sortable="true" field="numeroInscription" header="N°INSCRIPTION"></Column>
                <Column :sortable="true" field="etudiant.personne.nom" header="NOM"></Column>
                <Column :sortable="true" field="etudiant.personne.prenom" header="PRENOM(S)"></Column>
                <Column :sortable="true" field="moyenne_annee.total_ue_valide" header="UE OBTENUE"></Column>
                <Column :sortable="true" field="moyenne_annee.total_credit" header="CREDIT OBTENUE"></Column>
                <Column :sortable="true" field="moyenne_annee.valeur" header="MOYENNE"></Column>

            </DataTable>

            <div v-else class="text-center h3 text-text-secondary my-5">
                Liste vide
            </div>

        </div>
        <div class="d-flex justify-content-center my-5" v-else>
            <Loading/>
        </div>
    </div>
</template>

<script setup>
import Modal from '../modal/Modal.vue'

import Spinner from '../annimate/Spinner.vue'
import Loading from '../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useOperation } from '@/stores/operation'
import { useToasting } from '@/stores/toasting'
import { onMounted, ref } from 'vue'
import { useData } from '@/stores/data'
import { useAuthStore } from '@/stores/auth'

const Data=useData()
const Auth=useAuthStore()
const parcour=ref('')
const suggestionParcour=ref([])

const Toasting=useToasting()

const Operation=useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

defineProps({
    items:Object,
    type: String
})


</script>

