<template>
    <section class="mt-3">
        <h4 class="text-center">
            Pour  consulter les informations qui vous concernent, <br>
            Veuillez completer les formulaires ci dessous.
        </h4>
        <div class="border rounded m-4 p-4">
            <div class="row my-2">
                <div class="col">
                    <label >Année universitaire</label><br>
                    <AutoComplete v-model="annees" 
                        :multiple="false"
                        :suggestions="suggestionAnnee||[]" 
                        @complete="searchAnnee($event)" 
                        placeholder="Année"
                        :dropdown="true" optionLabel="valeur" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">
                                        {{slotProps.item.valeur}} 
                                    </div>
                                </div>
                            </template>
                    </AutoComplete>
                </div>
                <div class="col">
                    <label for="num_inscription">N°Inscription:</label>
                    <input v-model="filters.numeroInscription" id="num_inscription" type="text" class="form-control" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="nom">Nom:</label>
                    <input v-model="filters.nom" id="nom" type="text" class="form-control" autocomplete="off">
                </div>
                <div class="col">
                    <label for="prenom">Prenom(s):</label>
                    <input v-model="filters.prenom" id="prenom" type="text" class="form-control" autocomplete="off">
                </div>
            </div>

            <div class="row mt-2" v-if="error_message">
                <h5 class="text-center text-danger">{{ error_message }}</h5>
            </div>

            <button class="btn btn-primary mt-3" @click="filter_send">
                Chercher <Spinner v-if="FilterEtudiant.getPending.filter" /> 
            </button>
        </div>


        <div v-if="show_result" class="m-4">

            <div v-if="!FilterEtudiant.getPending.filter">
                <DataTable filterDisplay="menu" v-if="FilterEtudiant.getEtudiants.length"
                        :paginator="true" :rows="10" :value="FilterEtudiant.getEtudiants" responsiveLayout="scroll" 
                        :filters="filtersG"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        :rowsPerPageOptions="[10,20,50]"
                    >

                    <template #header>
                        <div class="d-flex justify-content-between">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filtersG['global'].value" placeholder="Recherche" />
                            </span>
                        </div>
                    </template>

                        <Column :sortable="true" field="numeroInscription" header="N°INSCRIPTION"></Column>
                        <Column :sortable="true" field="etudiant.personne.nom" header="NOM"></Column>
                        <Column :sortable="true" field="etudiant.personne.prenom" header="PRENOM(S)"></Column>
                        <Column :sortable="true" field="parcour.parcour" header="PARCOURS"></Column>
                        <Column :sortable="true" field="status.valeur" header="STATUT"></Column>
                        <Column  header="ACTION">
                        <template #body="{data}">
                            <div @click="$router.push(
                                {
                                    name:'historique-liste',params:{id:data.etudiant.id}
                                })"
                                 class="btn btn-primary">
                                Détails
                            </div>
                        </template>
                        </Column>


                </DataTable>
                <h2 v-else class="text-center text-secondary my-5">
                    Aucun résultat
                </h2>
            </div>
            <div v-else>
                <Loading />
            </div>

        </div>

    </section>
</template>

<script setup>

import Loading from '@/components/annimate/Loading.vue'
import Spinner from '@/components/annimate/Spinner.vue'
import DataTable from 'primevue/datatable'
import InputText from 'primevue/inputtext'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { FilterMatchMode } from 'primevue/api'
import { useData } from '@/stores/data'
import { onMounted, ref, watchEffect } from 'vue'
import { usePublicFilterEtudiant } from '@/stores/public_etudiant'

const annees=ref('')
const suggestionAnnee=ref([])
const Data=useData()
const FilterEtudiant=usePublicFilterEtudiant()
const show_result=ref(false)
const error_message=ref('')

const filters=ref({})
const filtersG = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

watchEffect(()=>{
    
    if(!annees.value)
        annees.value=Data.annees.find(el=>el.statut==1)

    filters.value.annee_universitaire_id=annees.value?.id
    show_result.value=false
})

const filter_send=()=>{

    if(!filters.value.nom && !filters.value.prenom && !filters.value.numeroInscription ){
        error_message.value="Veuillez saisir au moins l'une de vos informations."
        return 
    }

    error_message.value=""

    FilterEtudiant.getFilterResult(filters.value)
    show_result.value=true

}

const searchAnnee=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionAnnee.value = [...Data.annees||[]];
            }
            else {
                suggestionAnnee.value = Data.annees?.filter((annee) => {
                    return annee.valeur.toLowerCase().includes(event.query.toLowerCase());
                })||[];
            }
        }, 150);
}

onMounted(()=>{
    window.scrollTo(0,0);
})

</script>
