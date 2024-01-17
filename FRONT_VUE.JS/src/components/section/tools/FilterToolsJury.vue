<template>
    <div class="my-2 text-center">
        <div class="row">

            <!-- <div class="col-md-4 my-2">

                <AutoComplete v-model="status" 
                        :multiple="false"
                        :suggestions="suggestionStatus" 
                        @complete="searchStatus($event)" 
                        placeholder="Status"
                        :dropdown="true" optionLabel="valeur" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2 text-capitalize">
                                        {{slotProps.item.valeur}} 
                                    </div>
                                </div>
                            </template>
                </AutoComplete>

            </div> -->

            <!-- <div class="col-md-4 my-2">
                <select v-model="model.signe_note"
                class="border rounded me-1">
                    <option :value="0">signe</option>
                    <option :value="1">supérieur</option>
                    <option :value="-1">inférieur</option>
                </select>
                <input v-model="model.note"
                type="number" placeholder="Note" style="width:80px" >
            </div> -->

            <!-- <div class="col-md-4 my-2">
                <select v-model="model.grade"
                class="border form-control me-1">
                    <option :value="0">Grade</option>
                    <option value="Licence">Licence</option>
                    <option value="Master">Master</option>

                </select>
            </div>
            <div class="col-md-4 my-2">
                <select v-model="model.niveau"
                class="border form-control me-1">
                    <option :value="0">Niveau</option>
                    <option v-for="niveau in niveaux" :value="niveau" :key="'niveau_'+niveau">{{ niveau }}</option>

                </select>
            </div> -->

            <!-- <div class="col-md-4 my-2">
                <AutoComplete v-model="session" 
                        :multiple="false"
                        :suggestions="suggestionSession" 
                        @complete="searchSession($event)" 
                        placeholder="Session"
                        :dropdown="true" optionLabel="session" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2 text-capitalize">
                                        {{slotProps.item.session}} 
                                    </div>
                                </div>
                            </template>
                </AutoComplete>
            </div> -->
            <div class="col-md-4 my-2">

                <AutoComplete v-model="semestre" 
                        :multiple="false"
                        :suggestions="suggestionSemestre" 
                        @complete="searchSemestre($event)" 
                        placeholder="Semestre"
                        :dropdown="true" optionLabel="semestre" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2 text-capitalize">
                                        {{slotProps.item.semestre}} 
                                    </div>
                                </div>
                            </template>
                </AutoComplete>
            </div>

            <!-- <div class="col-md-4 my-2">
                <AutoComplete v-model="matiers" 
                        :multiple="true"
                        :suggestions="suggestionMatier" 
                        @complete="searchMatier($event)" 
                        placeholder="matier(s)"
                        :dropdown="true" optionLabel="matiere" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">
                                        {{slotProps.item.matiere}} 
                                    </div>
                                </div>
                            </template>
                </AutoComplete>
            </div> -->
            <!-- <div class="col-md-4 my-2 ">
                <input v-model="model.nom_prenom"
                type="text" class="form-control text-center" placeholder="Nom prenom">
            </div> -->
            <!-- <div class="col-md-4 my-2 ">
                <input v-model="model.numeroInscription"
                type="text" class="form-control text-center" placeholder="numero d'inscription">
            </div> -->

        </div>
    </div>
</template>


<script setup>

import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { ref } from '@vue/reactivity'
import { useData } from '@/stores/data'
import { computed, watchEffect } from '@vue/runtime-core'
import { useOperation } from '@/stores/operation'

const parcour=defineProps({
    parcour:Object,
    type:String
})

const Operation=useOperation()

const Data=useData()
const matiers=ref([])
const status=ref('')
const semestre=ref('')
const session=ref('')
const suggestionMatier=ref([])
const suggestionAnnee=ref([])
const suggestionStatus=ref([])
const suggestionSemestre=ref([])
const suggestionSession=ref([])

const model=ref({
    status_id:0,
    signe_note:0,
    grade:0,
    niveau:0,
    semestre_id:0,
    session_id:0,
})

const parcour_matiers=computed(()=>{
    return Data.matiers.filter((e)=>e.parcour_id==parcour.parcour.id)
})

const niveaux=computed(()=>{

    if(model.value.grade=='Master')
        return [1,2]
    else 
        return [1,2,3]
})






const searchStatus=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionStatus.value = [...Data.all_data.status];
            }
            else {
                suggestionStatus.value = Data.all_data.status.filter((st) => {
                    return st.valeur.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
}


const searchSemestre=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionSemestre.value = [...Data.all_data.semestres];
            }
            else {
                suggestionSemestre.value = Data.all_data.semestres.filter((st) => {
                    return st.valeur.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
}

const searchSession=(event)=>{
    setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionSession.value = [...Data.all_data.sessions];
            }
            else {
                suggestionSession.value = Data.all_data.sessions.filter((st) => {
                    return st.valeur.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
}


const searchMatier=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionMatier.value = [...parcour_matiers.value];
            }
            else {
                suggestionMatier.value = parcour_matiers.value.filter((matiere) => {
                    return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
    }





watchEffect(()=>{

    const payload={}
    payload.parcours=[parcour.parcour.id]
    payload.type=parcour.type


    if(model.value.signe_note!=0){
        payload.signe_note=model.value.signe_note>0?'>=':'<='
        if(model.value.note)
            payload.note=model.value.note
        else
            payload.note=0
    }

    if(model.value.grade!=0)
        payload.grade=model.value.grade

    if(model.value.niveau!=0)
        payload.niveau=model.value.niveau


    if(model.value.numeroInscription)
        payload.numeroInscription=model.value.numeroInscription 
    
    if(model.value.nom_prenom)
        payload.nom_prenom=model.value.nom_prenom 
        

    if(matiers.value.length){

        let matiers_id_list=[]
        matiers.value.forEach(element => {
            matiers_id_list.push(element.id)
        });

        payload.matiers=matiers_id_list
    }


    if(status.value){

        if(typeof status.value =='object')
            payload.status_id=status.value.id
        else
            payload.status_id=undefined

    }

    if(semestre.value){

        payload.semestre_id=semestre.value.id

    }

    if(session.value){

        if(typeof status.value =='object')
            payload.session_id=session.value.id
        else
            payload.session_id=undefined

    }


    Operation.setTools(payload)

    if(semestre.value){

       Operation.setValidationParUe()

    }

})

</script>
