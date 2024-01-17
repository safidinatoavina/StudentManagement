<template>
    <div class="my-2">
        <h3 class="text-center text-info text-decoration-underline">Edit Ue</h3>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <input v-model="ueModel.ue" type="text" placeholder="ue" class="form-control">
                </div>
                <div class="col-12 col-md-3">
                    <input v-model="ueModel.credit" type="number" placeholder="credit" class="form-control" >
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <AutoComplete v-model="ueModel.parcour" disabled
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
                    <AutoComplete  v-model="ueModel.semestre" disabled
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
            <button type="button" :id="`edit-ue-${ue.id}`" class="btn btn-secondary" @click="refreshUe()" data-bs-dismiss="modal">
                Annuler
            </button>
            <button 
            type="button" class="btn btn-primary" @click="Data.editUE(ueModel)"
            >
                Enregistrer
                <Spinner v-if="Data.pendings.edit_ue?.[ue.id]" />
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



const Data=useData()
const Admin=useAdmin()
const ueModel=ref({})

const suggestionParcour=ref([])
const suggestionUser=ref([])
const suggestionSemestre=ref([])
const suggestionUes=ref([])

const props=defineProps({
    ue: Object
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
                suggestionUes.value = [...Data.ues];
            }
            else {
                suggestionUes.value = Data.ues.filter((ue) => {

                    return ue.ue.toLowerCase().includes(event.query.toLowerCase());

                });
            }
        }, 150);
    }


    const refreshUe=()=>{
        ueModel.value=JSON.parse(JSON.stringify(props.ue))
    }

    watchEffect(()=>{
        refreshUe()
    })

</script>

<style scoped>

div.row{
    margin-top: 1rem;
}

</style>

