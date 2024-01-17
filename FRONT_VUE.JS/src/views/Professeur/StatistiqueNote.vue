<template>
    <section class="my-3 px-3">
        
        <h4 class="text-decoration-underline text-center text-primary">
            Statistique des ECUE avec notes
        </h4>

        <div v-if="!Statistique.getpending.get_stat_ajout_note">


            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="non-public-tab" data-bs-toggle="tab" data-bs-target="#non-public" type="button" role="tab" aria-controls="non-public" aria-selected="true">
                        Non public
                    </button>
                    <button class="nav-link" id="public-tab" data-bs-toggle="tab" data-bs-target="#public" type="button" role="tab" aria-controls="public" aria-selected="false">
                        Public
                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="non-public" role="tabpanel" aria-labelledby="non-public-tab" tabindex="0">
                    <DataTable filterDisplay="menu"
                    :filters="filters"
                    v-model:expandedRows="expandedNonPublic"
                    :paginator="true" :rows="10" :value="Statistique.getStatNotes.non_public" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[10,20,50]"
                    >

                        <template #header>
                            <div class="d-flex justify-content-between">
                                <span class="p-input-icon-left">
                                    <i class="pi pi-search" />
                                    <InputText v-model="filters['global'].value" placeholder="Recherche" />
                                </span>
                            </div>
                        </template>

                        <Column expander style="width: 5rem" />
                        <Column :sortable="true" field="id"       header="ID"></Column>
                        <Column :sortable="true" field="ue"  header="UE"></Column>
                        <Column :sortable="true" field="parcour"  header="PARCOURS"></Column>
                        <Column :sortable="true" filter-field="option" sort-field="option"  header="TYPE">
                        <template #body="{data}">
                            <div>
                                <span v-if="data.option" class="badge text-bg-secondary">optionel</span>
                                <span v-else class="badge text-bg-info">obligatoire</span>
                            </div>
                        </template>
                        </Column>
                        <Column :sortable="true" field="pourcent" header="POURCENTAGE" class="text-primary" ></Column>
                        <Column :header="`${Data.en_cours?.session.session }`" class="text-uppercase">
                        <template #body="{data}">
                            <div class="d-flex" v-if="Data.en_cours?.session.id==2">
                                <button class="btn btn-primary me-2" @click="Operation.publicResult({ue:data.id,parcour:data.parcour_id})" >Publier <Spinner v-if="Operation.getPending.public_result?.[data.id]" /></button>
                            </div>
                            <div class="d-flex" v-else>
                                <button class="btn btn-primary me-2" @click="Operation.publicResultRattrapage({ue:data.id,parcour:data.parcour_id})" >Publier <Spinner v-if="Operation.getPending.public_result_rattrapage?.[data.id]" /></button>
                            </div>
                        </template>
                        </Column>

                        <template #expansion="slotProps">
                            <div class="p-3 px-5 ecue-detail rounded">
                                <h5 class="text-center text-info text-decoration-underline">Liste ECUE {{ slotProps.data.ue }}</h5>
                                <DataTable :value="slotProps.data.matieres" class="p-datatable-sm " >

                                    <Column field="id" header="ID" sortable></Column>
                                    <Column field="matiere" header="ECUE" sortable></Column>
                                    <Column field="pourcent_matiere" header="POURCENTAGE NOTE ECUE" sortable></Column>
                                    <Column   header="TP" >
                                    <template #body="{data}">
                                        <div v-if="data.has_tp">
                                            {{ data.tp }}
                                        </div>
                                        <div v-else>
                                            X
                                        </div>
                                    </template>
                                    </Column>
                                    <Column  field="pourcent_tp" header="POURCENTAGE NOTE TP" >
                                    <template #body="{data}">
                                        <div v-if="data.has_tp">
                                            {{ data.pourcent_tp }}
                                        </div>
                                        <div v-else>
                                            X
                                        </div>
                                    </template>
                                    </Column>

                                </DataTable>
                            </div>
                        </template>

                    </DataTable>
                </div>
                <div class="tab-pane fade" id="public" role="tabpanel" aria-labelledby="public-tab" tabindex="0">
                    <DataTable filterDisplay="menu"
                    v-model:expandedRows="expandedPublic"
                    :filters="filters"
                    :paginator="true" :rows="10" :value="Statistique.getStatNotes.public" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[10,20,50]"
                    >

                        <template #header>
                            <div class="d-flex justify-content-between">
                                <span class="p-input-icon-left">
                                    <i class="pi pi-search" />
                                    <InputText v-model="filters['global'].value" placeholder="Recherche" />
                                </span>
                            </div>
                        </template>

                        <Column expander style="width: 5rem" />
                        <Column :sortable="true" field="id"       header="ID"></Column>
                        <Column :sortable="true" field="ue"  header="UE"></Column>
                        <Column :sortable="true" field="parcour"  header="PARCOURS"></Column>
                        <Column :sortable="true" filter-field="option" sort-field="option"  header="TYPE">
                        <template #body="{data}">
                            <div>
                                <span v-if="data.option" class="badge text-bg-secondary">optionel</span>
                                <span v-else class="badge text-bg-info">obligatoire</span>
                            </div>
                        </template>
                        </Column>
                        <Column :sortable="true" field="pourcent" header="POURCENTAGE" class="text-primary" ></Column>

                        <Column :header="`${Data.en_cours?.session.session }`" class="text-uppercase">
                            <template #body="{data}">
                                <div class="d-flex" v-if="Data.en_cours?.session.id==2">
                                    <button class="btn btn-secondary" @click="Operation.cancelResult({ue:data.id,parcour:data.parcour_id})" >Annuler publication <Spinner v-if="Operation.getPending.cancel_result?.[data.id]" /> </button>
                                </div>
                                <div class="d-flex" v-else>
                                    <button class="btn btn-secondary" @click="Operation.cancelResultRattrapage({ue:data.id,parcour:data.parcour_id})" >Annuler publication <Spinner v-if="Operation.getPending.cancel_result_rattrapage?.[data.id]" /> </button>
                                </div>
                            </template>
                        </Column>
                        
                        <template #expansion="slotProps">
                            <div class="p-3 ecue-detail px-5 rounded">
                                <h5 class="text-center text-info text-decoration-underline">Liste ECUE {{ slotProps.data.ue }}</h5>
                                <DataTable :value="slotProps.data.matieres" class="p-datatable-sm " >

                                    <Column field="id" header="ID" sortable></Column>
                                    <Column field="matiere" header="ECUE" sortable></Column>
                                    <Column field="pourcent_matiere" header="POURCENTAGE NOTE ECUE" sortable></Column>
                                    <Column   header="TP" >
                                    <template #body="{data}">
                                        <div v-if="data.has_tp">
                                            {{ data.tp }}
                                        </div>
                                        <div v-else>
                                            X
                                        </div>
                                    </template>
                                    </Column>
                                    <Column  field="pourcent_tp" header="POURCENTAGE NOTE TP" >
                                    <template #body="{data}">
                                        <div v-if="data.has_tp">
                                            {{ data.pourcent_tp }}
                                        </div>
                                        <div v-else>
                                            X
                                        </div>
                                    </template>
                                    </Column>

                                </DataTable>
                            </div>
                        </template>


                    </DataTable>
                </div>
            </div>

        </div>
        <div v-else class="my-5">
            <Loading />
        </div>

    </section>
</template>

<script setup>
import Spinner from '../../components/annimate/Spinner.vue'
import Loading from '../../components/annimate/Loading.vue'
import { onBeforeMount, onMounted, ref } from "vue";
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useStatistique } from '@/stores/statistique'
import { useData } from '@/stores/data'
import { useOperation } from '@/stores/operation'


const Data=useData()
const Statistique = useStatistique();
const Operation=useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
});

const expandedPublic = ref([]);
const expandedNonPublic = ref([]);

onBeforeMount(() => {
    Statistique.getStatistiqueAjoutNote()
})

onMounted(() => {
    window.scrollTo(0,0)
})
    
</script>

<style scoped>

.p-datatable .p-datatable-thead > tr > th{
    background: cyan!important;

}


</style>
