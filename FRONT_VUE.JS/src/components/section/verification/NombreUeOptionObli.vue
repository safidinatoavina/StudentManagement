<template>
<div class="px-5" v-if="!Data.pendings.get_option">

    <div v-if="Data.ues.find(el=>el.option==1)">
        <div class="row">
            <div class="col-12 col-md-6 my-2">
                <label for="semestre-liste">semestre</label>
                <div>
                    <AutoComplete v-model="semestre" 
                    id="semestre-liste"
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

            <div class="col-12 col-md-6 my-2">
                <label for="ue-obi-obli-for-option">valeur</label>
                <input v-model="nombre_ue_obli" id="ue-obi-obli-for-option" type="number" class="form-control">
            </div>
        </div>

        <button class="btn btn-primary w-100 my-2" @click="Data.setNombreUeOptionObli({
            parcour_id: $route.params.parcour,
            semestre_id : semestre?.id,
            nombre_ue_obli : nombre_ue_obli
        })">
            Enregistrer <Spinner v-if="Data.pendings.set_option" />
        </button>

        <div class="my-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>semestre</th>
                        <th>valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="nb_ue in Data.getNombreUeObli" :key="`liste-ue-option-obli_${nb_ue.id}`">
                        <td>{{ nb_ue.semestre.semestre }}</td>
                        <td>{{ nb_ue.nombre_ue_obli }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div v-else class="mt-5">
        <h4 class="text-info text-center">Aucun UE optionel dans cette parcour</h4>
    </div>

</div>
<div v-else class="my-5">
    <Loading />
</div>

</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import { onMounted, ref, watch, watchEffect } from "vue";
import { useData } from '@/stores/data'
import AutoComplete from 'primevue/autocomplete'
import { onBeforeRouteUpdate, useRoute } from "vue-router";



const suggestionSemestre = ref([])
const semestre = ref('')
const nombre_ue_obli=ref(0)

const Data = useData()
const route=useRoute()



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

onMounted(() => {
    Data.getNombreUeOptionObli({ parcour_id: route.params.parcour })
    semestre.value = Data.en_cours?.semestre
})

watchEffect(() => {
    nombre_ue_obli.value= Data.getNombreUeObli.find(el=>el.semestre.id==semestre.value.id)?.nombre_ue_obli 
})

onBeforeRouteUpdate((from, to) => {
    if(to.params.parcour!=from.params.parcour)
        Data.getNombreUeOptionObli({parcour_id:to.params.parcour})
})

</script>
