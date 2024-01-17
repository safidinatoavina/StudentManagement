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

        <div class="my-3 ms-2">
            <button @click="editEtudiant"
             class="btn btn-primary">
             <span>Enregistrer</span>
             <spinner v-if="Etudiant.pending"></spinner>

             </button>
        </div>

        <h5 class="text-info text-decoration-underline">
            Historiques Dans l'universite
        </h5>

        <section v-for="(_historique,i) in listeHistoriques"
        :key="`historique_etudiant_${_historique.id}`" 
        class="form-group m-2">
            <div class="row border my-2">
                <div class="col-12 col-md-4">
                    <label for="parcour">Parcours</label><br>
                    <div>
                        {{ listeHistoriques[i].parcour.parcour }}
                    </div>

                </div>
                <div class="col-12 col-md-4">
                    <label for="num-inscription">Numéro d'inscription</label>
                    <input v-model="listeHistoriques[i].numeroInscription" id="num-inscription" type="text" class="form-control" readonly>
                </div>

                <div class="col-12 col-md-4">
                    <label for="annee-univ">Année univérsitaire</label>
                    <div>
                        
                        {{ listeHistoriques[i].annee_universitaire.valeur }}

                    </div>


                </div>

                <div class="col-12 col-md-4">
                    <label for="num-inscription">Status</label>
                    <div>
                        {{ listeHistoriques[i].status.valeur }}
                    </div>

                </div>

                <div class="col-12 col-md-6">
                    <div class="d-flex mt-3">
                        <div
                        class="btn btn-danger me-2" @click="deleteHistorique(_historique.id)">
                            <i class="fa fa-trash fs-4 "></i>
                            <Spinner classColor="text-white" v-if="Etudiant.getPendingAction?.delete_historique?.[_historique.id]" />
                        </div>
                        <!-- <div>
                            <button class="btn btn-primary me-2">Enregistrer</button>
                        </div> -->
                        <!-- <div>
                            <button @click="cancelEdit(i)" class="btn btn-secondary">Annuler moddif</button>
                        </div> -->
                    </div>

                </div>

            </div>

        </section>

        <h4 class="text-info text-center text-decoration-underline">
            Créer historique  (Les notes de l'année précédente ne sont pas conservées)
        </h4>

        <div class="row border m-2 p-2">
                <div class="col-12 col-md-4">
                    <label for="parcour">Parcours</label><br>
                    <AutoComplete v-model="new_historique.parcour" 
                    :suggestions="suggestionParcour" 
                    @complete="searchParcour($event)" 
                    placeholder=""
                    :dropdown="true" optionLabel="parcour" forceSelection>

                        <template #item="slotProps">
                            <div class="country-item">
                                <div class="ml-2">{{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}</div>
                            </div>
                        </template>

                    </AutoComplete>

                </div>
                <div class="col-12 col-md-4">
                    <label for="num-inscription">Numéro d'inscription</label>
                    <input v-model="new_historique.numeroInscription" placeholder="Numério d'inscription" id="num-inscription" type="text" class="form-control" >
                </div>

                <div class="col-12 col-md-4">
                    <label for="annee-univ">Année univérsitaire</label>
                    <div>
                        <AutoComplete v-model="new_historique.annee_universitaire" 
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


                </div>

                <div class="col-12 col-md-4">
                    <label for="num-inscription">Status</label>
                    <div>
                        <select   class="rounded p-2"
                    v-model="new_historique.status_id"
                    id="annee-univ" readonly>
                        <option v-for="_status in Data.all_data.status" :key="`status-list-${_status.id}`"
                         :value="_status.id">{{ _status.valeur }}</option>
                    </select>
                    </div>

                </div>

                <div class="col-12 col-md-6">
                    <div class="d-flex mt-3">

                        <div>
                            <button @click="saveNewHistorique" class="btn btn-primary me-2">
                                Enregistrer
                                <Spinner classColor="text-white" v-if="Etudiant.getPendingAction?.create_historique" />
                            </button>
                        </div>
                        <div>
                            <button @click="new_historique={}" class="btn btn-secondary">
                                Annuler moddif
                            </button>
                        </div>
                    </div>

                </div>
            </div>

    </div>
</template>

<script setup>

import Spinner from '../../components/annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import Toast from 'primevue/toast';
import { ref } from "@vue/reactivity"
import { useData } from '@/stores/data'
import { useEtudiant } from '@/stores/etudiant'
import { computed, watchEffect } from '@vue/runtime-core'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from "primevue/usetoast";

const Data=useData()
const Etudiant=useEtudiant()
const router=useRouter()
const route=useRoute()

const etudiant=ref({})
const parcour=ref([])
const suggestionParcour=ref([])
const suggestionAnnee=ref([])
const new_historique=ref({})
const listeParcour=Data.parcours
const toast=useToast()

const listeHistoriques=ref([])

const editEtudiant=()=>{


    //etudiant.value.parcour_id=parcour.value.id
    
    Etudiant.editEtudiant(etudiant.value,showSuccessUpdate)

}

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

const showSuccessUpdate = () => {
    toast.add({
    severity:'success', summary: 'Modification étudiant', detail:`Modification succès`, life: 3000
    })
}

const cancelEdit=(i)=>{
    listeHistoriques.value[i]=JSON.parse(JSON.stringify(Etudiant.getEtudiant(route.params.id).historiques[i]))
}

const deleteHistorique=(id)=>{
    Etudiant.deleteHistorique(id,listeHistoriques)
}

const saveNewHistorique=()=>{

    if(!new_historique.value.status_id){
        alert('le champ status est requis')
        return
    }
    if(!new_historique.value.numeroInscription){
        alert('le champ N°inscription est requis')
        return
    }
    if(!new_historique.value.parcour){
        alert('le champ parcour est requis')
        return
    }
    if(!new_historique.value.annee_universitaire){
        alert('le champ annee universitaire est requis')
        return
    }


    Etudiant.saveNewHistorique(new_historique,listeHistoriques,new_historique)


}

watchEffect(()=>{

    
    if(!Etudiant.getEtudiants?.length)
        return;

    let edit=Etudiant.getEtudiant(route.params.id)


    if(edit){
        if(!etudiant.value.date_naissance){
            etudiant.value=edit?.personne
            etudiant.value.date_naissance=edit.personne?.date_naissance?.split('/').reverse().join('-')
            etudiant.value.numeroInscription=edit?.numeroInscription
            listeHistoriques.value=JSON.parse(JSON.stringify(Etudiant.getEtudiant(route.params.id).historiques))
        }
    }
})

</script>

<style>

 h5 > .back:hover{

    color: red!important;

 }

</style>


