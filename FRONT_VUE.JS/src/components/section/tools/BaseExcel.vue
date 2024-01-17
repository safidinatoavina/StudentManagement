<template>
    <section>
        <div class="mt-3 px-5">
            <div class="row">
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

        <div class="my-5 d-flex justify-content-center">
            <button class="btn btn-primary"  @click="DataIO.exportMoyenneBase({
                is_validation,
                parcour_id:$route.params.parcour,
                semestre_id: semestre?.id,
            })">
                Téléchargé excel base 
                <Spinner class-color="text-white mb-1" v-if="DataIO.getPending.export_moyenne_ue"/>
            </button>
        </div>

    </section>
</template>

<script setup>

import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { onMounted, ref, watchEffect } from 'vue'
import { useData } from '@/stores/data'
import { useDataIO } from '@/stores/data_io'

const is_validation = ref(1)
const semestre=ref('')
const Data=useData()
const DataIO=useDataIO()


const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const type_result=defineProps({
    type:String
})

const suggestionSemestre=ref([])


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

onMounted(()=>{
    semestre.value=Data.en_cours?.semestre
})


</script>

