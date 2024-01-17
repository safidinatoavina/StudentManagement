<template>
    <div>

        <h5 class="text-center my-3 text-primary text-deoration-underline">
            Excel pour les moyenne matieres,ue et semestre (Base excel)
        </h5>

        <div class="my-3">


            <div class="d-flex justify-content-center">
                <div class="my-3">
                    <div class="row">
                        <div class="col my-2">
                            <AutoComplete v-model="parcour" 
                                :multiple="false"
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
                        <div class="col my-2">
                            <AutoComplete v-model="semestre" 
                                :multiple="false"
                                :suggestions="suggestionSemestre" 
                                @complete="searchSemestre($event)" 
                                placeholder="Semestre"
                                :dropdown="true" optionLabel="semestre" forceSelection>

                                <template #item="slotProps">
                                    <div class="country-item">
                                        <div class="ml-2">
                                            {{ slotProps.item.semestre }}
                                        </div>
                                    </div>
                                </template>

                            </AutoComplete>
                        </div>
                        <div class="col my-2">
                            <div class="d-flex text-brand fw-bolder">
                                <div class="mx-1">
                                    <label for="validation" class="me-1">validation</label>
                                    <input id="validation" type="radio" value="1" v-model="is_validation" />
                                </div>
                                <div class="mx-1">
                                    <label for="moyenne" class="me-1">moyenne</label>
                                    <input id="moyenne" type="radio" value="0" v-model="is_validation" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button @click="DataIO.exportMoyenneBase({
                    is_validation,
                    semestre_id: semestre?.id,
                    parcour_id:parcour?.id
                })" class="btn btn-primary" :disabled="!!!parcour?.id">
                    Telecharger excel base note  <Spinner v-if="DataIO.getPending.export_moyenne_ue" />
                </button>
            </div>

        </div>

        <div>
            <NoteStatParcour />
        </div>
    </div>
</template>

<script setup>
import NoteStatParcour from './NoteStatParcour.vue'
import Spinner from '../../annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { ref } from "vue"
import { useData } from '@/stores/data'
import { useDataIO } from '@/stores/data_io'

const parcour = ref('')
const semestre=ref('')
const is_validation=ref(1)
const suggestionParcour = ref([])
const suggestionSemestre = ref([])
const Data=useData()
const DataIO=useDataIO()

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

</script>

