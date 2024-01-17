<template>
    <section class="form-group m-2 content-tab">

        <DataTable filterDisplay="menu"
            :filters="filters"
            currentPageReportTemplate="({currentPage} sur {totalPages})"
            :paginator="true" :rows="4" 
            :value="Note.getNotes?.[params.parcour_id]||[]" 
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
                    <a class="text-white btn btn-primary" style="cursor:pointer" @click="Note.GetPdf(params)">
                        <span>Telecharger pdf</span>
                        <Spinner v-if="Note.getPendingPdf" class-color="text-white"/>
                    </a>
                </div>
            </template>


            <Column :sortable="true" field="historique.numeroInscription" header="N° d'inscription"></Column>
            <Column :sortable="true" field="historique.etudiant.personne.nom" header="Nom"></Column>
            <Column :sortable="true" field="historique.etudiant.personne.prenom" header="Prénom(s)"></Column>
            <Column v-if="note" header="Note">
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
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { useNote } from '@/stores/note'
import { ref } from '@vue/reactivity'
import gsap from 'gsap'
import { onMounted } from '@vue/runtime-core'

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const Note=useNote()


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


onMounted(()=>{

    gsap.from('section.content-tab',{ duration: 0.5,ease: "power4.out",y:500})

})

</script>

