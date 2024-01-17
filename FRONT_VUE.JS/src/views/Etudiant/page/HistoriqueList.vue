<template>
<section class="m-3">
    <div v-if="!FilterEtudiant.getPending.historique">
        <DataTable filterDisplay="menu" v-if="FilterEtudiant.getHistoriques.length"
                :paginator="true" :rows="10" :value="FilterEtudiant.getHistoriques" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
            >


                <Column :sortable="true" field="numeroInscription" header="N°INSCRIPTION"></Column>
                <Column :sortable="true" field="etudiant.personne.nom" header="NOM"></Column>
                <Column :sortable="true" field="etudiant.personne.prenom" header="PRENOM(S)"></Column>
                <Column :sortable="true" field="parcour.parcour" header="Parcours"></Column>
                <Column :sortable="true" field="annee_universitaire.valeur" header="ANNEE UNNIVERSITAIRE"></Column>
                <Column :sortable="true" field="status.valeur" header="STATUT"></Column>
                <Column header="VALIDATIONS">
                <template #body="{data}">
                    <div class="btn btn-success" @click="setValidation(data.id)">
                        Résultats
                    </div>
                </template>
                </Column>


        </DataTable>
        <div class="m-5" v-else>
            <h5 class="fs-1 text-center text-danger">404</h5>
            <h1 class="text-center text-brand text-decoration-underline" >
                Page introuvable 
            </h1>
        </div >
    </div>
    <div v-else class="m-5">
        <Loading/>
    </div>
</section>
</template>

<script setup>
import Loading from '@/components/annimate/Loading.vue'
import Spinner from '@/components/annimate/Spinner.vue'
import DataTable from 'primevue/datatable'
import InputText from 'primevue/inputtext'
import Column from 'primevue/column'
import { useData } from '@/stores/data'
import { onBeforeMount, onMounted, ref, watchEffect } from 'vue'
import { usePublicFilterEtudiant } from '@/stores/public_etudiant.js'
import { useRoute, useRouter } from 'vue-router'

const FilterEtudiant=usePublicFilterEtudiant()
const route=useRoute()
const router=useRouter()

const setValidation=(historique)=>{
    router.push({
        name:"resulat-validation",
        params:{historique:historique}
    })
}

onBeforeMount(()=>{
    FilterEtudiant.getHistoriqueEtudiant(route.params.id)
})

onMounted(()=>{
    window.scrollTo(0,0);
})

</script>

