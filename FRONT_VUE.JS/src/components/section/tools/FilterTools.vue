<template>
    <div class="my-2 text-center">
        <div class="row">
            <div class="col-md-4 my-2">

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
            <div class="col-md-4 my-2">

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

            </div>

            <div class="col-md-4 my-2">
                <select v-model="model.signe_note"
                class="border rounded me-1">
                    <option :value="0">signe</option>
                    <option :value="1">supérieur</option>
                    <option :value="-1">inférieur</option>
                </select>
                <input v-model="model.note"
                type="number" placeholder="Note" style="width:80px" >
            </div>

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
            <div class="col-md-4 my-2">
                <AutoComplete v-model="parcours" 
                        :multiple="true"
                        :suggestions="suggestionParcour" 
                        @complete="searchParcour($event)" 
                        placeholder="Parcours"
                        :dropdown="true" optionLabel="parcour" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">
                                        {{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}
                                    </div>
                                </div>
                            </template>
                </AutoComplete>
            </div>
            <div class="col-md-4 my-2">
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
            </div>
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

            <div class="col-md-4 my-2">
                <AutoComplete v-model="matiers" 
                        :multiple="true"
                        :suggestions="suggestionMatier" 
                        @complete="searchMatier($event)" 
                        placeholder="ECUE"
                        :dropdown="true" optionLabel="matiere" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">
                                        {{slotProps.item.matiere}} 
                                    </div>
                                </div>
                            </template>
                </AutoComplete>
            </div>
            <!-- <div class="col-md-4 my-2 ">
                <input v-model="model.nom_prenom"
                type="text" class="form-control text-center" placeholder="Nom prenom">
            </div> -->
            <div class="col-md-4 my-2 ">
                <input v-model="model.numeroInscription"
                type="text" class="form-control text-center" placeholder="numero d'inscription">
            </div>

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
import { useEtudiantNoteFilter } from '@/stores/filtre_etudiant'
import { computed, onMounted, watchEffect } from '@vue/runtime-core'
import { useAuthStore } from '@/stores/auth'


const Data=useData()
const parcours=ref([])
const matiers=ref([])
const annees=ref('')
const status=ref('')
const semestre=ref('')
const session=ref('')
const suggestionParcour=ref([])
const suggestionMatier=ref([])
const suggestionAnnee=ref([])
const suggestionStatus=ref([])
const suggestionSemestre=ref([])
const suggestionSession=ref([])
const AuthStore=useAuthStore()

const EtudiantNoteFilter=useEtudiantNoteFilter()
const model=ref({
    status_id:0,
    signe_note:0,
    grade:0,
    niveau:0,
    semestre_id:0,
    session_id:0,
})

const parcour_matiers=computed(()=>{
    if(parcours.value.length){
        return Data.matiers.filter((e)=>parcours.value.find((el)=>el.id==e.parcour_id))
    }
    else{
        return Data.matiers
    }
})

const niveaux=computed(()=>{

    if(model.value.grade=='Master')
        return [1,2]
    else 
        return [1,2,3]
})




const searchParcour=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {
                if(AuthStore.isAdmin)
                    suggestionParcour.value = [...Data.parcours];
                else if(AuthStore.isJury){
                    let parcours_jury=Data.parcours.filter((el)=>{
                        return AuthStore.getUser.parcours.find(_parcour=>_parcour.id==el.id)
                    })

                    suggestionParcour.value = [...parcours_jury];
                }
                else{
                    
                    suggestionParcour.value=[]
                }
            }
            else {
                if(AuthStore.isAdmin){
                    suggestionParcour.value = Data.parcours.filter((parcour) => {
                        return `${parcour.parcour} ${parcour.abreviation}`.toLowerCase().includes(event.query.toLowerCase());
                    });
                }else if(AuthStore.isJury){
                    let parcours_jury=Data.parcours.filter((el)=>{
                        return AuthStore.getUser.parcours.find(_parcour=>_parcour.id==el.id)
                    }).filter((parcour) => {
                        return `${parcour.parcour} ${parcour.abreviation}`.toLowerCase().includes(event.query.toLowerCase());
                    });

                    suggestionParcour.value = [...parcours_jury];
                }
            }
        }, 150);
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
                if(AuthStore.isAdmin){
                    suggestionMatier.value = [...parcour_matiers.value];
                }else if(AuthStore.isJury){
                    let autorize_matieres=parcour_matiers.value.filter((el)=>{
                        return AuthStore.getUser.parcours.find(parc=>parc.id==el?.parcour?.id)
                    })
                    suggestionMatier.value = [...autorize_matieres];
                }
            }
            else {

                if(AuthStore.isAdmin){
                    suggestionMatier.value = parcour_matiers.value.filter((matiere) => {
                        return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
                    });
                }else if(AuthStore.isJury){
                    suggestionMatier.value = parcour_matiers.value.filter((matiere) => {
                        return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
                    }).filter((matiere)=>{
                        return AuthStore.getUser.parcours.find(parc=>parc.id==matiere?.parcour?.id)
                    })
                }
            }
        }, 150);
    }


watchEffect(()=>{
    if((typeof Data.annees=='object') && !model.value.annee)
        model.value.annee=Data.annees[Data.annees.length-1]?.id
})

watchEffect(()=>{

    const payload={}


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
        
    if(parcours.value.length){

        let parcour_id_list=[]
        parcours.value.forEach(element => {
            parcour_id_list.push(element.id)
        });

        payload.parcours=parcour_id_list
    }

    if(matiers.value.length){

        let matiers_id_list=[]
        matiers.value.forEach(element => {
            matiers_id_list.push(element.id)
        });

        payload.matiers=matiers_id_list
    }

    if(annees.value){

        if(typeof annees.value =='object')
            payload.annee_universitaire_id=annees.value.id
        else
            payload.annee_universitaire_id=undefined

    }

    if(status.value){

        if(typeof status.value =='object')
            payload.status_id=status.value.id
        else
            payload.status_id=undefined

    }

    if(semestre.value){

        
        if(typeof semestre.value =='object')
            payload.semestre_id=semestre.value.id
        else
            payload.semestre_id=undefined

    }

    if(session.value){

        if(typeof session.value =='object')
            payload.session_id=session.value.id
        else
            payload.session_id=undefined

    }


    EtudiantNoteFilter.setTools(payload)
    EtudiantNoteFilter.setShowResult(false)
})


onMounted(()=>{
    annees.value=Data.annees.find(el=>el.statut==1)
})

</script>


