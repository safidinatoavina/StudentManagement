<template>
    <section>
        <div v-if="!Note.pendingObj.validation_ue_ecue">
            <DataTable filterDisplay="menu"
                :filters="filters"
                currentPageReportTemplate="({currentPage} sur {totalPages})"
                :paginator="true" :rows="4" 
                v-model:expandedRows="expanded"
                :value="Note.getValidationUeEcue?.[matiere_id]||[]" 
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
                        <a class="text-white btn btn-primary" style="cursor:pointer" @click="Note.downloadExcelValidationUeEcue(matiere_id)">
                            <span>Telecharger</span>
                            <Spinner v-if="Note.pendingObj.dowload_validation_ue_ecue" class-color="text-white"/>
                        </a>
                    </div>
                </template>


                <Column expander style="width: 5rem" />
                <Column field="numeroInscription" header="N°INSCRIPTION"></Column>
                <Column field="nom" header="NOM"></Column>
                <Column field="prenom" header="PRENOM(S)"></Column>
                <Column field="ue" header="UE"></Column>
                <Column field="semestre" header="SEMESTRE"></Column>
                <Column field="valeur_session_normal" header="SESSION NORMAL"></Column>
                <Column field="validation" v-if="Note.getValidationUeEcue?.[matiere_id]?.[0].validation" header="APRES RATTRAPAGE"></Column>

                <template #expansion="slotProps">
                    <div class="p-3 px-5 ecue-detail rounded">
                        <h5 class="text-center text-info text-decoration-underline">Liste ECUE {{ slotProps.data.ue }}</h5>
                        <DataTable :value="slotProps.data.matieres" class="p-datatable-sm " >

                            <Column field="matiere" header="ECUE" sortable></Column>
                            <Column field="validation" header="VALIDATION" sortable></Column>

                        </DataTable>
                    </div>
                </template>

            </DataTable>
        </div>
        <div v-else class="mt-5">
            <Loading />
        </div>
    </section>
</template>


<script setup>
import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import { onMounted, ref } from "vue"
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useNote } from '@/stores/note'


const props = defineProps({
    matiere_id: Number
})

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
});


const expanded = ref([])

const Note=useNote()

onMounted(() => {
    Note.fetchValidationMatiereUe(props.matiere_id)
})

</script>
