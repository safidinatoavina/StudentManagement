<template>
    <div class="my-2">
        <h3 class="text-center text-info text-decoration-underline">Edit matière</h3>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <input v-model="matiereModel.matiere" type="text" placeholder="ECUE" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <input v-model="matiereModel.coefficient" type="number" placeholder="Crédit" class="form-control" >
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <AutoComplete v-model="matiereModel.parcour" disabled
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
                    <AutoComplete  v-model="matiereModel.professeur"
                        :suggestions="suggestionUser" 
                        @complete="searchUser($event)" 
                        placeholder="Professeur"
                        :dropdown="true" optionLabel="personne.nom" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.personne.nom}} {{ slotProps.item.personne.prenom||'' }}</div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-md-6">
                    <AutoComplete  v-model="matiereModel.ue"  disabled
                        :suggestions="suggestionUes" 
                        @complete="searchUes($event)" 
                        placeholder="Ue"
                        :dropdown="true" optionLabel="ue" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.ue}}</div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>
                <div class="col-12 col-md-6">
                    <AutoComplete  v-model="matiereModel.semestre" disabled
                        :suggestions="suggestionSemestre" 
                        @complete="searchSemestre($event)" 
                        placeholder="Semestre"
                        :dropdown="true" optionLabel="semestre" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2"> {{ slotProps.item.semestre }} </div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>

            </div>

        </div>

        <div class="modal-footer mt-3">
            <button type="button" :id="`edit-matiere-${matiere.id}`" class="btn btn-secondary" @click="refreshMatiere()" data-bs-dismiss="modal">
                Annuler
            </button>
            <button 
            type="button" class="btn btn-primary" @click="Data.editMatiere(matiereModel)"
            >
                Enregistrer
                <Spinner v-if="Data.pendings.edit_matiere?.[matiere.id]" />
            </button>
        </div>
    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { ref, watchEffect } from 'vue'
import { useData } from '@/stores/data'
import { useAdmin } from '@/stores/admin'
import { useRoute } from 'vue-router'



const Data=useData()
const Admin=useAdmin()
const matiereModel=ref({})
const route=useRoute()

const suggestionParcour=ref([])
const suggestionUser=ref([])
const suggestionSemestre=ref([])
const suggestionUes=ref([])

const props=defineProps({
    matiere: Object,
    is_responsable_parcour: {
        type: Boolean,
        default: false
    }
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
    
    const searchUser=(event)=>{

        setTimeout(() => {

            const prof_list=(Admin.getAdmins.filter(e=>e.roles.find(el=>el.id===3)))
            
            if (!event.query.trim().length) {
                suggestionUser.value = [...prof_list];
            }
            else {
                suggestionUser.value = prof_list.filter((user) => {

                    let resultNom=user.personne.nom.toLowerCase().includes(event.query.toLowerCase());
                    let resultPrenom=user.personne.prenom.toLowerCase().includes(event.query.toLowerCase());

                    return resultNom || resultPrenom

                });
            }
        }, 150);
    }

    const searchSemestre=(event)=>{

        setTimeout(() => {
            
            if (!event.query.trim().length) {
                suggestionSemestre.value = [...Data.semestres];
            }
            else {
                suggestionSemestre.value = Data.semestres.filter((semestre) => {

                    return semestre.semestre.toLowerCase().includes(event.query.toLowerCase());

                });
            }
        }, 150);
    }
    
    const searchUes=(event)=>{

        setTimeout(() => {
            if (!event.query.trim().length) {
                if(!props.is_responsable_parcour)
                    suggestionUes.value = [...Data.ues];
                else
                    suggestionUes.value = [...Data.ues?.filter((el)=>el.parcour.id==route.params.parcour)];

            }
            else {

                if(!props.is_responsable_parcour){

                    suggestionUes.value = Data.ues.filter((el)=>el.parcour.id==route.params.parcour)
                                                    .filter((ue) => {
    
                        return ue.ue.toLowerCase().includes(event.query.toLowerCase());
    
                    });

                }else{


                    suggestionUes.value = Data.ues.filter((ue) => {
                        
                        return ue.ue.toLowerCase().includes(event.query.toLowerCase());

                    });

                }
            }
        }, 150);
    }


    const refreshMatiere=()=>{
        matiereModel.value=JSON.parse(JSON.stringify(props.matiere))
    }

    watchEffect(()=>{
        refreshMatiere()
    })

</script>

<style scoped>

div.row{
    margin-top: 1rem;
}

</style>

