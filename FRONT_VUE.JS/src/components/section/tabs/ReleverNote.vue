<template>
    <section >
        <div class="border rounded border-primary my-4 p-4">
            <h6 class="text-center text-secondary ">Filtre Etudiant</h6>
            <div class="row my-2">
                <div class="col">
                    <label >Année universitaire</label><br>
                    <AutoComplete v-model="annees" 
                        :multiple="false"
                        :suggestions="suggestionAnnee" 
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

            <button class="btn btn-primary mt-3" @click="filter_send">
                Chercher <Spinner v-if="Secretaire.getPending.filter" /> 
            </button>
        </div>


    <div v-if="show_result">

        <div v-if="!Secretaire.getPending.filter">
            <DataTable filterDisplay="menu" v-if="Secretaire.getEtudiants.length"
                    :paginator="true" :rows="10" :value="Secretaire.getEtudiants" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[10,20,50]"
                >

                    <Column :sortable="true" field="numeroInscription" header="N°INSCRIPTION"></Column>
                    <Column :sortable="true" field="etudiant.personne.nom" header="NOM"></Column>
                    <Column :sortable="true" field="etudiant.personne.prenom" header="PRENOM(S)"></Column>
                    <Column :sortable="true" field="parcour.parcour" header="PARCOURS"></Column>
                    <Column :sortable="true" field="status.valeur" header="STATUT"></Column>
                    <Column header="ACTION">
                    <template #body="{data}">
                        <div class="btn btn-primary" @click="Secretaire.getNoteStatement(data.id)">
                            Relevé des notes pdf
                            <Spinner v-if="Secretaire.getPending.releve[data.id]"/>
                        </div>
                    </template>
                    </Column>


            </DataTable>
        </div>
        <div v-else>
            <Loading />
        </div>

    </div>


    </section>
</template>

<script setup>
import Loading from '../../annimate/Loading.vue'
import Spinner from '../../annimate/Spinner.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { useData } from '@/stores/data'
import { useSecretaire } from '@/stores/secretaire'
import { ref, watchEffect } from 'vue'


const annees=ref('')
const suggestionAnnee=ref([])
const Data=useData()
const Secretaire=useSecretaire()

const show_result=ref(false)

const filters=ref({})

watchEffect(()=>{
    filters.value.annee_universitaire_id=annees.value?.id
    show_result.value=false
})

const filter_send=()=>{
    Secretaire.secretaireFilterEtudiant(filters.value)
    show_result.value=true
}

const searchAnnee=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionAnnee.value = [...Data.annees];
            }
            else {
                suggestionAnnee.value = Data.annees.filter((annee) => {
                    return annee.valeur.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
}

</script>
