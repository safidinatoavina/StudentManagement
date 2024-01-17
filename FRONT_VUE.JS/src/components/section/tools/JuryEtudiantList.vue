<template>
    <section class="form-group m-2 content-tab">

        <DataTable filterDisplay="menu"
            :filters="filters"
            currentPageReportTemplate="({currentPage} sur {totalPages})"
            :paginator="true" :rows="4" 
            :value="listes" 
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


            <Column v-for="head in header" :field="head.field" :header="head.head" :key="'row_'+head.head"></Column>


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
import { useRoute } from 'vue-router'
import gsap from 'gsap'
import { onMounted, watchEffect } from '@vue/runtime-core'
import { useEtudiant } from '@/stores/etudiant'

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const route=useRoute()
const Etudiant=useEtudiant()
const Note=useNote()

const listes=ref([])
const header=ref([])
const type =defineProps({

    get:{
        type:String, //'liste'||'validation'||'note'
    },

})


watchEffect(()=>{
    if(type.get=='liste'){
        header.value=[
            {head:"N° d'inscription",field:'numeroInscription'},
            {head:"Nom",field:'etudiant.personne.nom'},
            {head:"Prenom",field:'etudiant.personne.prenom'}
        ]
        Etudiant.getEtudiantJury()
    }

    listes.value=Etudiant.EtudiantJury
})

</script>