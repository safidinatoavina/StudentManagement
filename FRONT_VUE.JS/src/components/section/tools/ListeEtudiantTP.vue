<template>
    <section class="form-group m-2 content-tab">
        <DataTable filterDisplay="menu"
            :filters="filters"
            currentPageReportTemplate="({currentPage} sur {totalPages})"
            :paginator="true" :rows="4" 
            :value="Note.getNotesTP?.[route.params.parcour || params.parcour_id]" 
            responsiveLayout="scroll" 
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[4,10,20,50]"
        >

            <template #header>
                <div class="d-flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Recherche" />
                    </span>
                    <div class="dropdown" v-if="note||validation">
                        <a class="dropdown-toggle btn btn-primary"  data-bs-toggle="dropdown" aria-expanded="false">
                            Téléchargé
                        </a>

                        <ul class="dropdown-menu">
                            <li v-for="file_type in file_types" :key="`file-type-tp-${file_type.type}`" @click="DataIO.exportNoteTP({
                                parcour:parcour_name,
                                tp_id: params.tp,
                                is_validation: validation
                                },file_type.type)" style="cursor:pointer">
                                <a class="dropdown-item" >{{ file_type.name }}</a>
                            </li>
                        </ul>

                        <Spinner class-color="text-primary" v-if="DataIO.getPending.export_note_tp"/>

                    </div>
                </div>
            </template>


            <Column :sortable="true" field="historique.numeroInscription" header="N° d'inscription"></Column>
            <Column :sortable="true" field="historique.etudiant.personne.nom" header="Nom"></Column>
            <Column :sortable="true" field="historique.etudiant.personne.prenom" header="Prénom(s)"></Column>
            <Column :sortable="note" sortField="valeur" v-if="note" header="Note">
                <template #body="{data}">
                    
                    {{ data.is_set?data.valeur:'X' }}

                </template>
            </Column>
            <Column v-if="validation" :sortable="true" header="Validation">
            <template #body="{data}">
                <div class="text-uppercase">
                    <span v-if="!data.is_set" class="text-uppercase">
                        X
                    </span>
                    <span v-else class="text-uppercase">
                        {{ data.valeur>=10?'V':'NV' }}
                    </span>
                </div>
            </template>
            </Column>

        </DataTable>

    </section>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'

import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { useNote } from '@/stores/note'
import { useDataIO } from '@/stores/data_io'
import { useData } from '@/stores/data'
import { ref } from '@vue/reactivity'
import { useRoute } from 'vue-router'
import { onMounted, watchEffect } from 'vue'

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const Note = useNote()
const DataIO = useDataIO()
const Data = useData()
const route=useRoute()
const parcour_name = ref('')

const file_types = ref([
    {
        name:'Excel',
        type:'xlsx'
    },
    {
        name:'CSV',
        type:'csv'
    },
    {
        name:'PDF',
        type:'pdf'
    }
])


const props=defineProps({
    note:{
        type:Boolean,
        default: false
    },
    validation:{
        type:Boolean,
        default: false
    },
    params:Object

})

watchEffect(() => {
    
    if (route.params.parcour) {
        parcour_name.value = Data.parcours.find(el => el.id == (route.params.parcour))?.parcour
    } else {
        parcour_name.value = Data.parcours.find(el => el.id == (props.params?.parcour_id))?.parcour
    }
    
})


</script>

