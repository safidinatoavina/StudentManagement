<template>
    <div class="mt-3 mb-2">

        <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="liste-passant-tab" data-bs-toggle="pill" data-bs-target="#liste-passant" type="button" role="tab" aria-controls="liste-passant" aria-selected="true">
                    Liste admis
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="liste-redoublant-tab" data-bs-toggle="pill" data-bs-target="#liste-redoublant" type="button" role="tab" aria-controls="liste-redoublant" aria-selected="false">
                    Liste redoublant
                </button>
            </li>
            <li class="nav-item" role="presentation" v-if="false">
                <button class="nav-link" id="liste-renvoyer-tab" data-bs-toggle="pill" data-bs-target="#liste-renvoyer" type="button" role="tab" aria-controls="liste-renvoyer" aria-selected="false">
                    Liste renvoyer
                </button>
            </li>

        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div  class="tab-pane fade show active" id="liste-passant" role="tabpanel" aria-labelledby="liste-passant-tab" tabindex="0">
                <PasseANiveau :items="Operation.getAdmis" type="passant">
                    <template #export>
                        <div class="dropdown">
                                <a class="dropdown-toggle text-primary" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    Téléchargé
                                </a>

                                <ul class="dropdown-menu">
                                    <li @click="DataIO.exportPassageAniveau('xlsx','passant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">Excel</a>
                                    </li>
                                    <li @click="DataIO.exportPassageAniveau('csv','passant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">CSV</a>
                                    </li>
                                    <li @click="DataIO.exportPassageAniveau('pdf','passant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">PDF</a>
                                    </li>
                                </ul>

                                <Spinner class-color="text-primary" v-if="DataIO.getPending.export_passage"/>

                            </div>
                    </template>
                </PasseANiveau>
            </div>
            <div  class="tab-pane fade" id="liste-redoublant" role="tabpanel" aria-labelledby="liste-redoublant-tab" tabindex="0">
                <PasseANiveau :items="Operation.getRedoublants" type="redoublant">
                    <template #export>
                        <div class="dropdown">
                                <a class="dropdown-toggle text-primary" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    Téléchargé
                                </a>

                                <ul class="dropdown-menu">
                                    <li @click="DataIO.exportPassageAniveau('xlsx','redoublant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">Excel</a>
                                    </li>
                                    <li @click="DataIO.exportPassageAniveau('csv','redoublant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">CSV</a>
                                    </li>
                                    <li @click="DataIO.exportPassageAniveau('pdf','redoublant',{parcour_id:$route.params.parcour})">
                                        <a class="dropdown-item" href="#">PDF</a>
                                    </li>
                                </ul>

                                <Spinner class-color="text-primary" v-if="DataIO.getPending.export_passage"/>

                            </div>
                    </template>
                </PasseANiveau>


            </div>
            <div class="tab-pane fade" id="liste-renvoyer" role="tabpanel" aria-labelledby="liste-renvoyer-tab" tabindex="0">
                renvoyer
            </div>
        </div>

    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import PasseANiveau from '../../list/PasseANiveau.vue'
import { onMounted, ref } from "vue";
import { useDataIO } from '@/stores/data_io'
import { useOperation } from '@/stores/operation'
import { onBeforeRouteUpdate, useRoute } from 'vue-router';

const DataIO=useDataIO()
const Operation = useOperation()
const route=useRoute()


onMounted(() => {
    Operation.fetchAdmis(route.params.parcour)
    Operation.fetchRedoublant(route.params.parcour)
})

onBeforeRouteUpdate((from, to) => {

    if (from.params.parcour != to.params.parcour) {

        Operation.fetchAdmis(to.params.parcour)
        Operation.fetchRedoublant(to.params.parcour)

    }

})

</script>
