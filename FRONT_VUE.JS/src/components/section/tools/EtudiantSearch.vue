<template>
    <section class="form-group m-2">


        <div class="border p-3 pb-4 border-primary rounded">

            <p class="text-primary text-center text-decoration-underline">
               Filtre des étudiants 
            </p>

            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="nom">Filter par nom et/ou prenom</label>
                    <div class="w-100">
                        <AutoComplete v-model="historique_student" 
                        class="w-100"
                        :suggestions="suggestionNom" 
                        @complete="searchNom($event)"
                        placeholder="Taper le nom à chercher"
                        :dropdown="true" :optionLabel="`etudiant.personne.full_name`" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.etudiant?.personne?.nom+' '+(slotProps.item.etudiant?.personne?.prenom||'')}} ({{ slotProps.item.numeroInscription }})</div>
                                </div>
                            </template>

                        </AutoComplete>
                    </div>

                </div>

                <div class="col-12 col-md-6">
                    <label for="num_inscription">Numero d'inscription</label>
                    <input v-model="model.numeroInscription" id="num_inscription" type="text" class="form-control">
                </div>


            </div>



            <div class="row" v-if="historique_student">
                <div class="col-12">

                    <NoteConfirm :matiere_id="parseInt(matiere)" :parcour_id="parseInt(parcour)"  @saved="reinitForm" @cancel="reinitForm"
                    :historique="historique_student">
                        <button class="btn btn-primary fw-bolder w-100 mt-3" >Continuer</button>
                    </NoteConfirm>

                </div>
            </div>
            <div class="row" v-else>
                <div class="col-12">
                    <p class="text-danger text-center mt-2">
                        Veillez remplire le formulaire
                    </p>
                </div>
            </div>
        </div>


        <div v-if="!selected" class="text-center mt-3 text-danger">
            <div v-if="selected!==false">
                Le numéro d'inscription est introuvable dans {{ matiereSelected.parcour.parcour }}
            </div>
        </div>
        <div v-else-if="!model.note" class="text-center text-danger mt-3">
            Le champ note ne peut pas être vide 
        </div>


    </section>
</template>

<script setup>
import NoteConfirm from './NoteConfirm.vue'

import { ref } from "@vue/reactivity";
import { useAuthStore } from '@/stores/auth'
import { useNote } from '@/stores/note'
import {  computed, onBeforeMount, watchEffect } from '@vue/runtime-core'
import { useRoute } from 'vue-router';
import AutoComplete from 'primevue/autocomplete'


const Note=useNote()
const AuthStore=useAuthStore()
const route=useRoute()
const parcour_id=ref('')


const model=ref({})
const selected=ref(false)
const historique_student=ref('')
const suggestionNom=ref([])

const props=defineProps({
    parcour: Number,
    matiere:Number
})

const matiereSelected=computed(()=> AuthStore.getMatieres.find((element)=>element.parcour_id==parcour_id.value))


const deleteModel=()=>{
    model.value={}
}

const reinitForm=()=>{
    deleteModel()
    historique_student.value=''
    Note.reinitNoteError()
}



watchEffect(()=>{
    if(historique_student.value){
        model.value.numeroInscription=historique_student.value.numeroInscription
    }
})

watchEffect(()=>{
    if(model.value.numeroInscription){
        historique_student.value=Note.getEtudiants?.[parcour_id.value]?.find(etudiant=>{
            return etudiant.numeroInscription==model.value.numeroInscription
        })

    }
})





const searchNom=(event)=>{

    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionNom.value = [...Note.getEtudiants?.[parcour_id.value].filter(historique=>{
                return Note.getNotes?.[parcour_id.value]?.find(note=>note.historique.etudiant.id==historique.etudiant.id && !note.is_set)
            })];
        }
        else {
            suggestionNom.value = Note.getEtudiants?.[parcour_id.value].filter((user) => {

                return `${user.etudiant.personne.nom} ${user.etudiant.personne.prenom}`.toLowerCase()
                        .includes(event.query.toLowerCase()) && Note.getNotes?.[parcour_id.value].find(note=>note.historique.etudiant.id==user.etudiant.id && !note.is_set)
                
            });
        }
    }, 150);

}

watchEffect(()=>{
    parcour_id.value=route.params.parcour || props.parcour
})




</script>


