<template>
    <section class="container mt-3">

        <h5 class="text-primary text-center border p-1 mb-3">Critères de validation semestre "{{ Data.en_cours?.semestre.semestre }}"</h5>

        <div class="row" v-if="!Operation.getPending.fetch_validation">
            <div class="col border-end">
                <h4 class="text-primary text-decoration-underline">Critères de "V"</h4>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center flex-column me-3 border py-4 px-1 w-25 ">
                        <div class="my-2">
                            <input id="ET" value="et" type="radio" v-model="standard.logique">
                            <label for="ET" class="ms-1 fw-bold" style="cursor:pointer">ET</label>
                        </div>
                        <div>
                            <input id="OU" value="ou" type="radio" v-model="standard.logique">
                            <label for="OU" class="ms-1 fw-bold" style="cursor:pointer">OU</label>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex mb-2 ">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Moyenne min:</div>
                            </div>
                            <div>
                                <input v-model="standard.min_moyenne" type="number" class="form-control mt-1" placeholder="minimum moyenne">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre min des UE:</div>
                            </div>
                            <div>
                                <input v-model="standard.min_ue" type="number" class="form-control mt-1" placeholder="minimum ue" >
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

                <button class="btn btn-primary mt-2" @click="Lancer('V')">
                    Enregister <Spinner v-if="Operation.getPending.set_v" />
                </button>

            </div>

            <div class="col">
                <h4 class="text-primary text-decoration-underline">Critères de "VPC"</h4>

                <div class="d-flex align-items-center">
                    
                    <div class="d-flex align-items-center flex-column me-3 border py-4 px-1 w-25">
                        <div class="my-2">
                            <input id="ET-vpc" value="et" type="radio" v-model="standard_vpc.logique">
                            <label for="ET-vpc" class="ms-1 fw-bold" style="cursor:pointer">ET</label>
                        </div>
                        <div>
                            <input id="OU-vpc" value="ou" type="radio" v-model="standard_vpc.logique">
                            <label for="OU-vpc" class="ms-1 fw-bold" style="cursor:pointer">OU</label>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Moyenne entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_vpc.min_moyenne" type="number" class="form-control mt-1" placeholder="minimum">
                                <input v-model="standard_vpc.max_moyenne" type="number" class="form-control mt-1" placeholder="maximum">
                            </div>
                        </div>
                        <div class="d-flex mb-2 ">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre des UE entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_vpc.min_ue"  type="number" class="form-control mt-1" placeholder="minimum" >
                                <input v-model="standard_vpc.max_ue"  type="number" class="form-control mt-1" placeholder="maximum" >
                            </div>
                        </div>
                        <div class="d-flex">
                            <div>
                                <div class="me-1 passage-width-label fw-bolder">Nombre de Crédit entre:</div>
                            </div>
                            <div class="d-flex">
                                <input v-model="standard_vpc.min_credit" type="number" class="form-control mt-1" placeholder="minimum">
                                <input v-model="standard_vpc.max_credit" type="number" class="form-control mt-1" placeholder="maximum">
                            </div>
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary mt-2" @click="Lancer('VPC')">
                    Enregister <Spinner v-if="Operation.getPending.set_vpc" />
                </button>

            </div>


            <h5 class="my-5 text-center text-primary text-decoration-underline">
                Publication resultat par semestre
            </h5>

            <div class="d-flex justify-content-center" >

                <div>
                    <button class="btn btn-success my-3" @click="Operation.publicSemestreResult()" :disabled="Operation.getPublicResult.semestre">
                        Publier le resultat semestre {{ Data.en_cours?.semestre.semestre }}
                        <Spinner v-if="Operation.getPending.public_semestre_result" />
                    </button>

                    <button class="btn my-3 ms-2" :class="[Operation.getPublicResult.semestre?'btn-danger':'btn-secondary']" @click="Operation.cancelSemestreResult()" :disabled="!Operation.getPublicResult.semestre">
                        Annuler la publication
                        <Spinner v-if="Operation.getPending.cancel_semestre_result" />
                    </button>
                </div>


            </div>

        </div>
        <div v-else class="my-5">
            <Loading />
        </div>


    </section>
</template>


<script setup>

import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import AutoComplete from 'primevue/autocomplete'
import { useOperation } from '@/stores/operation'
import { onBeforeMount, onMounted, ref, watchEffect } from 'vue'
import { useData } from '@/stores/data'

const Data=useData()

const Operation=useOperation()


const standard=ref({
    logique:'et',
    type: 'v',
    min_moyenne:'',
    min_ue:'',
    min_credit:''
})

const standard_vpc=ref({
    logique:'et',
    type: 'vpc',
    min_moyenne:'',
    max_moyenne:'',
    min_ue:'',
    max_ue:'',
    min_credit:'',
    max_credit:''
})


const Lancer=(type_form)=>{
    if(type_form=='V'){
        Operation.setV(standard.value)
    }else{
        Operation.setVPC(standard_vpc.value)
    }

}

onBeforeMount(()=>{
    Operation.fetchValidationSemestres()
    Operation.getPublicSemestre()
})

watchEffect(()=>{

    standard.value.min_moyenne=Operation.getCritereValidation.v?.min_moyenne!==undefined?Operation.getCritereValidation.v?.min_moyenne : standard.value.min_moyenne
    standard.value.min_ue=Operation.getCritereValidation.v?.min_ue!==undefined?Operation.getCritereValidation.v?.min_ue    : standard.value.min_ue
    standard.value.min_credit=Operation.getCritereValidation.v?.min_credit!==undefined?Operation.getCritereValidation.v?.min_credit : standard.value.min_credit
    standard.value.logique=Operation.getCritereValidation.v?.logique!==undefined?Operation.getCritereValidation.v?.logique : standard.value.logique



    //vpc
    standard_vpc.value.min_moyenne=Operation.getCritereValidation.vpc?.min_moyenne!==undefined?Operation.getCritereValidation.vpc?.min_moyenne   : standard_vpc.value.min_moyenne
    standard_vpc.value.max_moyenne=Operation.getCritereValidation.vpc?.max_moyenne!==undefined?Operation.getCritereValidation.vpc?.max_moyenne   : standard_vpc.value.max_moyenne
    standard_vpc.value.min_ue=Operation.getCritereValidation.vpc?.min_ue!==undefined?Operation.getCritereValidation.vpc?.min_ue                  : standard_vpc.value.min_ue
    standard_vpc.value.max_ue=Operation.getCritereValidation.vpc?.max_ue!==undefined?Operation.getCritereValidation.vpc?.max_ue                  : standard_vpc.value.max_ue
    standard_vpc.value.min_credit=Operation.getCritereValidation.vpc?.min_credit!==undefined?Operation.getCritereValidation.vpc?.min_credit      : standard_vpc.value.min_credit
    standard_vpc.value.max_credit=Operation.getCritereValidation.vpc?.max_credit!==undefined?Operation.getCritereValidation.vpc?.max_credit      : standard_vpc.value.max_credit
    standard_vpc.value.logique=Operation.getCritereValidation.vpc?.logique!==undefined? Operation.getCritereValidation.vpc?.logique              : standard_vpc.value.logique

console.log(Operation.getCritereValidation.vpc?.min_ue)
})


</script>

<style scoped>

.passage-width-label{
    width:130px!important;
}

</style>

