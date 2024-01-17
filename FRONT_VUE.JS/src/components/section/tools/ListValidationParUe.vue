<template>
    <section>
        <div class="mt-3">
            <AutoComplete v-model="semestre" 
                :multiple="false"
                :suggestions="suggestionSemestre" 
                @complete="searchSemestre($event)" 
                placeholder="Semestre"
                :dropdown="true" optionLabel="semestre" forceSelection>

                    <template #item="slotProps">
                        <div class="country-item">
                            <div class="ml-2 text-capitalize">
                                {{slotProps.item.semestre}} 
                            </div>
                        </div>
                    </template>
            </AutoComplete>
        </div>
        <div class="mt-3" v-if="!Operation.getPending.validation_ue">
            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="10" :value="Operation.validations" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >

                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Recherche" />
                        </span>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn btn-primary"  data-bs-toggle="dropdown" aria-expanded="false">
                                Téléchargé
                            </a>

                            <ul class="dropdown-menu">
                                <li @click="DataIO.exportMoyenneUe('xlsx',type)" style="cursor:pointer" >
                                    <a class="dropdown-item" >Excel</a>
                                </li>
                                <li @click="DataIO.exportMoyenneUe('csv',type)" style="cursor:pointer" >
                                    <a class="dropdown-item" >CSV</a>
                                </li>
                                <li @click="DataIO.exportMoyenneUe('pdf',type)" style="cursor:pointer" >
                                    <a class="dropdown-item" >PDF</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.export_moyenne_ue"/>

                        </div>
                    </div>
                </template>

                <Column :sortable="true" field="numeroInscription" header="N°INSCRIPTION"></Column>
                <Column :sortable="true" field="nom" header="NOM"></Column>
                <Column :sortable="true" field="prenom" header="PRENOM(S)"></Column>
                <Column :sortable="true" field="ue" header="UE"></Column>
                <Column :header="type.toUpperCase()" >
                    <template #body="{data}">
                        <div v-if="type.toLowerCase()=='moyenne'">
                            {{ data.moyenne }}
                        </div>
                        <div v-else>
                            {{ data.moyenne>=10?'V':'NV' }}
                        </div>
                    </template>
                </Column>


            </DataTable>

        </div>
        <div class="d-flex justify-content-center my-5" v-else>
            <Loading/>
        </div>
    </section>
</template>

<script setup>

import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useOperation } from '@/stores/operation'
import { onMounted, ref, watchEffect } from 'vue'
import { useData } from '@/stores/data'
import { useDataIO } from '@/stores/data_io'

const Operation=useOperation()
const Data=useData()
const DataIO=useDataIO()


const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const type_result=defineProps({
    type:String
})

const semestre=ref('')
const suggestionSemestre=ref([])


const searchSemestre=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionSemestre.value = [...Data.all_data.semestres];
            }
            else {
                suggestionSemestre.value = Data.all_data.semestres.filter((st) => {
                    return st.valeur.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
}

onMounted(()=>{
    const payload={}
    payload.type=type_result.type.toLocaleLowerCase()
    Operation.setTools(payload)
    Operation.setValidationParUe()
})

watchEffect(()=>{

    const payload={}


    if(semestre.value){

        payload.semestre_id=semestre.value.id
        payload.type=type_result.type.toLocaleLowerCase()
        Operation.setTools(payload)
        Operation.setValidationParUe()

    }


})

</script>

