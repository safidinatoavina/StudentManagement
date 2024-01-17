<template>
    <section class="container my-3">

        <h5 class="text-center my-2 text-info"> Parcours : {{ parcour_selected?parcour_selected.parcour:'aucun' }}</h5>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-5" id="ue-tab" data-bs-toggle="tab" data-bs-target="#ue-tab-pane" type="button" role="tab" aria-controls="ue-tab-pane" aria-selected="true">
                    UE
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="matiere-tab" data-bs-toggle="tab" data-bs-target="#matiere-tab-pane" type="button" role="tab" aria-controls="matiere-tab-pane" aria-selected="false">
                    ECUE
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="tp-responsable" data-bs-toggle="tab" data-bs-target="#tp-responsable-pane" type="button" role="tab" aria-controls="tp-responsable-pane" aria-selected="false">
                    TP
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-ue-matiere-tab" data-bs-toggle="tab" data-bs-target="#verification-ue-matiere-tab-pane" type="button" role="tab" aria-controls="verification-ue-matiere-tab-pane" aria-selected="true">
                    UE et ECUE verification
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="choix-option-ue" data-bs-toggle="tab" data-bs-target="#choix-option-ue-pane" type="button" role="tab" aria-controls="choix-option-ue-pane" aria-selected="false">
                    Option Ue par Etudiant
                </button>
            </li>
            <li class="nav-item" role="presentation" >
                <button class="nav-link" id="nombre-ue-option-obli-tab" data-bs-toggle="tab" data-bs-target="#nombre-ue-option-obli-tab-pane" type="button" role="tab" aria-controls="nombre-ue-option-obli-tab-pane" aria-selected="true">
                    Nombre d'UE optionnel inclus dans le calcul moyenne
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ue-tab-pane" role="tabpanel" aria-labelledby="ue-tab" tabindex="0">
                <UE :is_responsable_parcour="true" />
            </div>
            <div class="tab-pane fade" id="matiere-tab-pane" role="tabpanel" aria-labelledby="matiere-tab" tabindex="0">
                <Matiere :is_responsable_parcour="true"  />
            </div>
            <div class="tab-pane fade" id="tp-responsable-pane" role="tabpanel" aria-labelledby="tp-responsable" tabindex="0">
                <TP :is_responsable_parcour="true"/>
            </div>
            <div class="tab-pane fade" id="choix-option-ue-pane" role="tabpanel" aria-labelledby="choix-option-ue" tabindex="0">
                <UeOptionStudent />
            </div>
            <div class="tab-pane fade" id="verification-ue-matiere-tab-pane" role="tabpanel" aria-labelledby="verification-ue-matiere-tab" tabindex="0">
                <VerificationUe :is-admin="false" :parcour="parseInt($route.params.parcour)" />
            </div>
            <div class="tab-pane fade" id="nombre-ue-option-obli-tab-pane" role="tabpanel" aria-labelledby="nombre-ue-option-obli-tab" tabindex="0">
                <NombreUeOptionObli />
            </div>
        </div>
    </section>
</template>

<script setup>
import NombreUeOptionObli from '../../components/section/verification/NombreUeOptionObli.vue'
import VerificationUe from '../../components/section/verification/VerificationUe.vue'
import TP from '../../components/section/data/TP.vue'
import UE from '../../components/section/data/UE.vue'
import Matiere from '../../components/section/data/Matiere.vue'
import UeOptionStudent from '../../components/section/data/UeOptionStudent.vue'
import { useAuthStore } from '@/stores/auth'
import { computed, onMounted, watchEffect } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route=useRoute()
const router=useRouter()

const AuthStore=useAuthStore()



onMounted(()=>{
    if(AuthStore.Responsable?.length!==undefined){
        if(!AuthStore.Responsable.find(el=>el.id==route.params.parcour))
            router.push({name:'error-404'})
    }
})

const parcour_selected=computed(()=>AuthStore.Responsable.find(el=>el.id==route.params.parcour))

</script>
