<template>
    <section class="container mt-3">

        <h5 class="text-primary text-center border p-1 mb-3">Critères de pasage à niveau</h5>

        <div class="row" v-if="!Operation.getPending.critere">
            <div class="col border-end">
                <h4 class="text-primary text-decoration-underline">Critères de passage</h4>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center flex-column me-3 border py-4 px-1 w-25 ">
                        <div class="my-2">
                            <input id="ET-logique" value="et" type="radio" v-model="standard.logique" >
                            <label for="ET-logique" class="ms-1 fw-bold" style="cursor:pointer">ET</label>
                        </div>
                        <div>
                            <input id="OU-logique" value="ou" type="radio" v-model="standard.logique" >
                            <label for="OU-logique" class="ms-1 fw-bold" style="cursor:pointer">OU</label>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex my-1">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Moyenne minimun:</div>
                            </div>
                            <div>
                                <input v-model="standard.min_moyenne" type="number" class="form-control mt-1" >
                            </div>
                        </div>
                        <div class="d-flex mb-2 ">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre min UE valide:</div>
                            </div>
                            <div>
                                <input v-model="standard.min_ue" type="number" class="form-control mt-1" placeholder="min UE valide" >
                            </div>
                        </div>
                        <div class="d-flex">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre min de Crédit:</div>
                            </div>
                            <div>
                                <input v-model="standard.min_credit" type="number" class="form-control mt-1" placeholder="minimum crédit" >
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mt-2" @click="Lancer('passant')">
                    Lancer et enregister <Spinner v-if="Operation.getPending.critere_admis" />
                </button>

            </div>

            <div class="col">
                <h4 class="text-primary text-decoration-underline">Critères de redoublement</h4>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center flex-column me-3 border py-4 px-1 w-25 ">
                        <div class="my-2">
                            <input id="ET" value="et" type="radio" v-model="standard_redouble.logique" >
                            <label for="ET" class="ms-1 fw-bold" style="cursor:pointer">ET</label>
                        </div>
                        <div>
                            <input id="OU" value="ou" type="radio" v-model="standard_redouble.logique" >
                            <label for="OU" class="ms-1 fw-bold" style="cursor:pointer">OU</label>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Moyenne entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_redouble.min_moyenne" type="number" class="form-control mt-1" placeholder="minimum">
                                <input v-model="standard_redouble.max_moyenne" type="number" class="form-control mt-1" placeholder="maximum">
                            </div>
                        </div>
                        <div class="d-flex mb-2 ">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre des UE validé entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_redouble.min_ue"  type="number" class="form-control mt-1" placeholder="minimum" >
                                <input v-model="standard_redouble.max_ue"  type="number" class="form-control mt-1" placeholder="maximum" >
                            </div>
                        </div>
                        <div class="d-flex my-1">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre de Crédit entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_redouble.min_credit" type="number" class="form-control mt-1" placeholder="minimum">
                                <input v-model="standard_redouble.max_credit" type="number" class="form-control mt-1" placeholder="maximum">
                            </div>
                        </div>
                    </div>
                </div>


                <button class="btn btn-primary mt-2" @click="Lancer('redoublement')">
                    Lancer et enregister <Spinner v-if="Operation.getPending.critere_redouble" />
                </button>

            </div>


            <div class="px-5 mt-3">
                
                <h5 class="my-5 text-primary text-center text-decoration-underline"> Resultat Final </h5>
        
                <div class="d-flex justify-content-center">
        
                    <button class="btn btn-success my-3" @click="Operation.publicFinalResult()" :disabled="Operation.getPublicResult.final">
                        Publier le resultat Final
                        <Spinner v-if="Operation.getPending.public_final_result" />
                    </button>
        
                    <button class="btn my-3 ms-2" :class="[Operation.getPublicResult.final?'btn-danger':'btn-secondary']" @click="Operation.cancelFinalResult()" :disabled="!Operation.getPublicResult.final">
                        Annuler la publication
                        <Spinner v-if="Operation.getPending.cancel_final_result" />
                    </button>
                </div>
            </div>

        </div>
        <div v-else class="my-5">
            <Loading />
        </div>



        <div v-if="show_liste_passe">
            <h3 class="text-success mt-5 text-center text-decoration-underline">
                Liste des étudiants admis
            </h3>
            <PasseANiveau :items="Operation.getAdmis" type="passant">
                <template #export>
                    <div class="dropdown">
                            <a class="dropdown-toggle text-primary" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                Téléchargé
                            </a>

                            <ul class="dropdown-menu">
                                <li @click="DataIO.exportPassageAniveau('xlsx','passant',standard)">
                                    <a class="dropdown-item" href="#">Excel</a>
                                </li>
                                <li @click="DataIO.exportPassageAniveau('csv','passant',standard)">
                                    <a class="dropdown-item" href="#">CSV</a>
                                </li>
                                <li @click="DataIO.exportPassageAniveau('pdf','passant',standard)">
                                    <a class="dropdown-item" href="#">PDF</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.export_passage"/>

                        </div>
                </template>
            </PasseANiveau>
        </div>
        <div v-if="show_liste_double">
            <h3 class="text-danger mt-5 text-center text-decoration-underline">
                Liste des étudiants redoublant
            </h3>
            <PasseANiveau :items="Operation.getRedoublants" type="redoublant">
                <template #export>
                    <div class="dropdown">
                            <a class="dropdown-toggle text-primary" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                Téléchargé
                            </a>

                            <ul class="dropdown-menu">
                                <li @click="DataIO.exportPassageAniveau('xlsx','redoublant',standard_redouble)">
                                    <a class="dropdown-item" href="#">Excel</a>
                                </li>
                                <li @click="DataIO.exportPassageAniveau('csv','redoublant',standard_redouble)">
                                    <a class="dropdown-item" href="#">CSV</a>
                                </li>
                                <li @click="DataIO.exportPassageAniveau('pdf','redoublant',standard_redouble)">
                                    <a class="dropdown-item" href="#">PDF</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.export_passage"/>

                        </div>
                </template>
            </PasseANiveau>
        </div>
    </section>
</template>


<script setup>

import PasseANiveau from '../../list/PasseANiveau.vue'
import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useOperation } from '@/stores/operation'
import { onBeforeMount, ref, watchEffect } from 'vue'
import { useDataIO } from '@/stores/data_io'


const DataIO=useDataIO()
const Operation=useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const standard=ref({
    logique: 'et',
    min_moyenne:'',
    min_ue:'',
    min_credit: ''
})

const standard_redouble=ref({
    logique: 'et',
    min_moyenne:'',
    max_moyenne:'',
    min_ue:'',
    max_ue:'',
    min_credit: '',
    max_credit: ''
})

const show_liste_passe=ref(false)
const show_liste_double=ref(false)

const Lancer=(type_form)=>{
    if(type_form=='passant'){
        show_liste_passe.value=true
        show_liste_double.value=false
        Operation.setAdmis(standard.value)
    }else{
        show_liste_double.value=true
        show_liste_passe.value=false
        Operation.setRedoubles(standard_redouble.value)
    }

}

onBeforeMount(()=>{
    Operation.setCriteres()
    Operation.getPublicFinal()
})

watchEffect(()=>{

    standard.value.min_moyenne=Operation.getCritere.passant?.min_moyenne!==undefined?Operation.getCritere.passant?.min_moyenne : standard.value.min_moyenne
    standard.value.min_ue=Operation.getCritere.passant?.min_ue!==undefined?Operation.getCritere.passant?.min_ue : standard.value.min_ue
    standard.value.logique=Operation.getCritere.passant?.logique!==undefined?Operation.getCritere.passant?.logique : standard.value.logique
    standard.value.min_credit=Operation.getCritere.passant?.min_credit!==undefined?Operation.getCritere.passant?.min_credit : standard.value.min_credit


    //redoublant
    standard_redouble.value.logique=Operation.getCritere.redoublant?.logique!==undefined?Operation.getCritere.redoublant?.logique : standard_redouble.value.logique
    standard_redouble.value.min_moyenne=Operation.getCritere.redoublant?.min_moyenne!==undefined? Operation.getCritere.redoublant?.min_moyenne : standard_redouble.value.min_moyenne
    standard_redouble.value.min_ue=Operation.getCritere.redoublant?.min_ue!==undefined?Operation.getCritere.redoublant?.min_ue : standard_redouble.value.min_ue
    standard_redouble.value.max_moyenne=Operation.getCritere.redoublant?.max_moyenne!==undefined?Operation.getCritere.redoublant?.max_moyenne : standard_redouble.value.max_moyenne
    standard_redouble.value.max_ue=Operation.getCritere.redoublant?.max_ue!==undefined?Operation.getCritere.redoublant?.max_ue : standard_redouble.value.max_ue
    standard_redouble.value.min_credit=Operation.getCritere.redoublant?.min_credit!==undefined? Operation.getCritere.redoublant?.min_credit : standard_redouble.value.min_credit
    standard_redouble.value.max_credit=Operation.getCritere.redoublant?.max_credit!==undefined? Operation.getCritere.redoublant?.max_credit : standard_redouble.value.max_credit

})


</script>

<style scoped>

.passage-width-label{
    width:130px!important;
}

</style>