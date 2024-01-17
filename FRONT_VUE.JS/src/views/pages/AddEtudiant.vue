<template>
    <div class="m-3">


        <h5 class="text-info text-decoration-underline">
            Identité de l'etudiant
        </h5>

        <section class="form-group m-2">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="nom">Nom</label>
                    <input v-model="etudiant.nom" id="nom" type="text" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <label for="prenom">Prénom(s)</label>
                    <input v-model="etudiant.prenom" id="prenom" type="text" class="form-control">
                </div>

                <div class="col-12 col-md-6">
                    <label for="date">Date de  naissance</label>
                    <input v-model="etudiant.date_naissance" id="date" type="date" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <label for="naissance">Lieu de naissance</label>
                    <input v-model="etudiant.lieu_naissance" id="naissance" type="text" class="form-control">
                </div>

                <div class="col-12 col-md-6">
                    <label for="address">Address</label>
                    <input v-model="etudiant.address" id="address" type="text" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <label for="cin">N° CIN</label>
                    <input v-model="etudiant.cin" id="cin" type="text" class="form-control">
                </div>
            </div>
        </section>

        <h5 class="text-info text-decoration-underline">
            Information dans l'université
        </h5>

        <section class="form-group m-2">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="parcour">Parcours</label><br>
                    <AutoComplete v-model="parcour" 
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
                <div class="col-12 col-md-6">
                    <label for="num-inscription">Numério d'inscription</label>
                    <input v-model="etudiant.numeroInscription" id="num-inscription" type="text" class="form-control">
                </div>

            </div>
        </section>

        <div class="mt-3">
            <button @click="addEtudiant"
             class="btn btn-primary">
             <span>Enregistrer</span>
             <spinner v-if="Etudiant.pending"></spinner>

             </button>
        </div>

    </div>
</template>

<script setup>

import Spinner from '../../components/annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { ref } from "@vue/reactivity"
import { useData } from '@/stores/data'
import { useEtudiant } from '@/stores/etudiant'
import { computed } from '@vue/runtime-core'
import { useRouter } from 'vue-router'

const Data=useData()
const Etudiant=useEtudiant()
const router=useRouter()

const etudiant=ref({})
const parcour=ref([])
const suggestionParcour=ref([])
const listeParcour=Data.parcours


const addEtudiant=()=>{


    etudiant.value.parcour_id=parcour.value.id
    
    Etudiant.setEtudiant(etudiant.value,showSuccessAdd)

}

const searchParcour=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionParcour.value = [...Data.parcours];
            }
            else {
                suggestionParcour.value = Data.parcours.filter((parcour) => {
                    return parcour.parcour.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
    }


const showSuccessAdd = () => {
        Etudiant.setPrintToast('created',true)
        router.push({name:'gestion-etudiant'})
    }
    

</script>

<style>

 h5 > .back:hover{

    color: red!important;

 }

</style>
