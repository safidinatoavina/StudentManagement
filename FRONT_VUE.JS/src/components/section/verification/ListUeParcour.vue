<template>
    <div>
        <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="Verification.getUes" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >

            <Column header="UE avec ECUE">
            <template #body="{data}">
                <div>
                    <div>
                        <strong>ID: </strong> <span class="fw-bolder text-secondary">{{ data.id }}</span>,
                        <strong>SEMESTRE: </strong> <span class="fw-bolder text-secondary text-uppercase">{{ data.semestre.semestre }}</span>, 
                        <strong>UE: </strong> <span class="fw-bolder text-secondary text-uppercase">{{ data.ue }}</span>,
                        <strong>CREDIT: </strong> <span class="fw-bolder text-secondary text-uppercase">{{ data.credit }}</span>
                        <strong>, </strong> <span class="badge text-bg-info mx-1 text-uppercase">{{ data.option?'facultatif':'obligatoire' }}</span>
                    </div>

                    <div class="my-1">
                        <span style="cursor:pointer" class="my-1 text-decoration-underline text-primary" @click="toogleMatiere[data.id]=!toogleMatiere[data.id]">
                            {{ toogleMatiere[data.id]?'--':'++' }} ECUE
                        </span>
                        <ul v-if="toogleMatiere[data.id]">
                            <li class="border d-flex justify-content-between"  v-for="matiere in data.matieres" :key="`matiere-id-${matiere.id}`">
                                <div class="ms-2"> 
                                    <div>
                                        {{ matiere.matiere }} ({{ matiere.coefficient+'C' }})
                                    </div>
                                    <div class="text-decoration-underline">
                                        Note saisie en % : <strong class="text-danger ms-2">{{ Math.round((matiere.notes.filter(el=>el.is_set==1).length/matiere.notes.length)*100) }} %</strong>
                                    </div>
                                </div>   
                                <div v-if="matiere.tp" class="text-info me-3">
                                    <div>
                                        TP: {{ matiere.tp.tp }}
                                    </div>
                                    <div class="text-decoration-underline">
                                        Note saisie en % : <strong class="text-danger ms-2">{{ Math.round((matiere.tp.note_tps.filter(el=>el.is_set==1).length/matiere.tp.note_tps.length)*100) }} %</strong>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </template>
            </Column>


        </DataTable>
    </div>
</template>

<script setup>
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { ref } from 'vue'
import {useVerification} from '@/stores/verification'

const Verification=useVerification()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const toogleMatiere=ref({})


</script>
