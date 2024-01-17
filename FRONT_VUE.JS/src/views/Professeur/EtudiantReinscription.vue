<template>
    <div class="m-3">

        <h3 class="text-center text-decoration-underline text-primary">Réinscription des étudiants</h3>

        <div class="my-4">
            <div class="row">
                <div class="mb-1">Parcours (optionel)</div>
                <AutoComplete v-model="search.parcour_id" 
                    :suggestions="suggestionParcour" 
                    @complete="searchParcour($event)" 
                    placeholder="Parcours"
                    :dropdown="true" optionLabel="parcour" forceSelection>

                        <template #item="slotProps">
                            <div class="country-item">
                                <div class="ml-2">{{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}</div>
                            </div>
                        </template>

                </AutoComplete>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <label class="me-1">Nom</label>
                    <input v-model="search.nom" type="text" class="form-control" placeholder="nom" />
                </div>
                <div class="col">
                    <label class="me-1">Prenom(s)</label>
                    <input v-model="search.prenom" type="text" class="form-control"  placeholder="prenom(s)"/>
                </div>
            </div>

            <button class="btn btn-primary mt-3" @click="Reinscription.fetchResults(search)">Chercher</button>
        </div>

        <div class="mt-4">
            <div class="text-center fw-bolder">20 premier resultat seulement sera afficher</div>
            <DataTable filterDisplay="menu"
                v-if="Reinscription.getResults?.length"
                :filters="filters"
                v-model:expandedRows="expandedHistorique"
                :paginator="true" :rows="10" :value="Reinscription.getResults" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >
                <Column expander style="width: 5rem" />
                <Column :sortable="true" field="nom" header="NOM"></Column>
                <Column :sortable="true" field="prenom" header="PRENOM(S)"></Column>
                <Column :sortable="false" header="ACTION">
                    <template #body={data}>
                        <ConfirmReinscription  :data="data" />
                    </template>
                </Column>

                <template #expansion="slotProps">
                    <div class="p-3 ecue-detail px-5 rounded">
                        <h5 class="text-center text-primary text-decoration-underline">Historique de  {{ slotProps.data.nom_complet }}</h5>
                        <DataTable :value="slotProps.data.historiques" class="p-datatable-sm " >

                            <Column field="numeroInscription" header="N°INSCRIPTION" sortable></Column>
                            <Column field="annee_universitaire" header="ANNEE UNNIVERSITAIRE" sortable></Column>
                            <Column field="parcour" header="PARCOURS" sortable></Column>
                            <Column field="statut" header="STATUT" sortable></Column>

                        </DataTable>
                    </div>
                </template>

            </DataTable>
            <div v-if="Reinscription.getPending.result" class="mt-5">
                <Loading />
            </div>
            <div v-else-if="Reinscription.getResults!==null && !Reinscription.getResults?.length">
                <h1 class="text-center">Aucun resultat</h1>
            </div>
        </div>

    </div>
</template>

<script setup>
import ConfirmReinscription from '../../components/section/tools/ConfirmReinscription.vue'
import Modal from '../../components/modal/Modal.vue'
import Spinner from '../../components/annimate/Spinner.vue'
import Loading from '../../components/annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useOperation } from '@/stores/operation'
import { useReinscription } from '@/stores/reinscription'
import { useToasting } from '@/stores/toasting'
import { onBeforeMount, onMounted, ref, watch, watchEffect } from 'vue'
import { useData } from '@/stores/data'

const Reinscription=useReinscription()
const Data=useData()
const parcour=ref('')
const suggestionParcour=ref([])
const expandedHistorique = ref([]);

const search=ref({
    nom : '',
    prenom : '',
    parcour_id : null
})

const Toasting=useToasting()

const Operation=useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const action=ref({
    passant:true,
    redoublant: false,
})



const searchParcour=(event)=>{
    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionParcour.value = [...Data.parcours];
        }
        else {
            suggestionParcour.value = Data.parcours.filter((parcour) => {
                return `${parcour.parcour} ${parcour.abreviation}`.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 150);
}


onMounted(()=>{
    window.scrollTo(0,0)
})

</script>

