<template>

<Modal :is-center="false" :idModal="`modal-reinscription-id-${data.etudiant_id}`" size-class="modal-lg" class="text-start">

    <template #btn>   
        <button class="btn btn-primary text-uppercase" >réinscrire</button>
    </template>

    <template #body>
        <div class="p-2 ms-5">
            <div class="modal-body">
                <h1 class="h4 text-center text-uppercase text-primary fw-bolder text-decoration-underline">
                    Réinscription
                </h1>
                <div><strong>Nom: </strong>{{ data.nom }}</div>
                <div> <strong>Prenom(s):</strong> {{ data.prenom }}</div>
                <div class="my-2">
                    <AutoComplete v-model="Reinscription.selectedParcour" class="w-100"
                        :suggestions="suggestionParcour" 
                        @complete="searchParcour($event)" 
                        placeholder="Parcours pour la reinscription"
                        :dropdown="true" optionLabel="parcour" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}</div>
                                </div>
                            </template>

                    </AutoComplete>
                    <div class="text-danger my-1" v-if="validation.parcour">{{ validation.parcour }}</div>
                    <div class="my-2">
                        <input v-model="numeroInscription" placeholder="Nouveau numéro d'inscription" class="form-control">
                        <span v-if="validation.numeroInscription" class="text-danger" >{{ validation.numeroInscription }}</span>
                    </div>
                </div>
                <div class="mt-2" v-if="Reinscription.selectedParcour">
                    <div v-if="Reinscription.selectedParcour.grade">
                    
                        <strong>Réinscription en :</strong> 

                        <div>
                            <strong class="text-primary">Parcours : </strong> {{ Reinscription.selectedParcour.parcour }}
                        </div>

                        <div>
                            <strong class="text-primary">Grade : </strong> {{ Reinscription.selectedParcour.grade.grade }}
                        </div>

                        <div>
                            <strong class="text-primary">Niveau : </strong> {{ Reinscription.selectedParcour.grade.niveau }}
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button @click="postReinscription()"
                type="button" class="btn btn-primary"
                >
                    Confirmer
                    <Spinner v-if="Reinscription.getPending.reinscription" />
                </button>
            </div>
        </div>
    </template>


</Modal>
</template>

<script setup>
import Modal from '../../modal/Modal.vue'
import Spinner from '../../annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { useReinscription } from '@/stores/reinscription'
import { useData } from '@/stores/data'
import { ref, watchEffect } from 'vue'

const props=defineProps({
    data:Object,
})

const Reinscription=useReinscription()
const numeroInscription=ref('')
const suggestionParcour=ref([])
const validation=ref({})
const Data=useData()


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


const postReinscription=()=>{

    validation.value={}

    if(!numeroInscription.value){
        validation.value.numeroInscription="Le champ nouveau numéro d'inscription est obligatoire."
        return 
    }
    if(!Reinscription.selectedParcour){
        validation.value.parcour="Veuillez sélectionner un parcours"
        return
    }


    let payload={
        etudiant_id     : props.data.etudiant_id,
        to_parcour_id        : Reinscription.selectedParcour.id,
        numeroInscription : numeroInscription.value
    }
    
    Reinscription.handle(payload)

}


watchEffect(()=>{
    if(Reinscription.selectedParcour){
        validation.value.parcour=""
    }

    if(Reinscription.numeroInscription){
        validation.value.numeroInscription=""
    }
})




</script>


<style>
div.p-autocomplete-panel.p-component{
    z-index: 9999!important;
}

</style>
