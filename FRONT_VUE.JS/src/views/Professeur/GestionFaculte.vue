<template>
    <section class="m-5" v-if="data.data_faculte!=null">


        <h5 class="text-danger text-center">
            Attention,<br> la suppression de données dans cette section peut avoir un impact sur toutes les données liées à elle.
        </h5>

        <div class="conatiner">
            <GestionAcademique />
        </div>

        <div class="container">
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li @click="setActive('annee')" class="nav-item" role="presentation">
                    <button class="nav-link active" id="annee" data-bs-toggle="tab" data-bs-target="#annee-pane" type="button" role="tab" aria-controls="annee-pane" aria-selected="true">
                        Année universitaire
                    </button>
                </li>
                <li @click="setActive('mention')" class="nav-item" role="presentation">
                    <button class="nav-link" id="mentions" data-bs-toggle="tab" data-bs-target="#mentions-pane" type="button" role="tab" aria-controls="mentions-pane" aria-selected="false">
                        Mentions
                    </button>
                </li>
                <li @click="setActive('grade')" class="nav-item" role="presentation">
                    <button class="nav-link" id="grades" data-bs-toggle="tab" data-bs-target="#grades-pane" type="button" role="tab" aria-controls="grades-pane" aria-selected="false">
                        Grades
                    </button>
                </li>
                <li @click="setActive('parcour')" class="nav-item" role="presentation">
                    <button class="nav-link" id="parcours" data-bs-toggle="tab" data-bs-target="#parcours-pane" type="button" role="tab" aria-controls="parcours-pane" aria-selected="false">
                        Parcours
                    </button>
                </li>
                <li @click="setActive('ue')" class="nav-item" role="presentation">
                    <button class="nav-link" id="ues" data-bs-toggle="tab" data-bs-target="#ues-pane" type="button" role="tab" aria-controls="ues-pane" aria-selected="false">
                        UE
                    </button>
                </li>
                <li @click="setActive('matiere')" class="nav-item" role="presentation">
                    <button class="nav-link" id="matieres" data-bs-toggle="tab" data-bs-target="#matieres-pane" type="button" role="tab" aria-controls="matieres-pane" aria-selected="false">
                        ECUE
                    </button>
                </li>
                <li @click="setActive('tp')" class="nav-item" role="presentation">
                    <button class="nav-link" id="tp" data-bs-toggle="tab" data-bs-target="#tp-pane" type="button" role="tab" aria-controls="tp-pane" aria-selected="false">
                        TP
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="annee-pane" role="tabpanel" aria-labelledby="annee" tabindex="0">
                    <AnneeUniversitaire v-if="active.annee" />
                </div>
                <div class="tab-pane fade" id="mentions-pane" role="tabpanel" aria-labelledby="mentions" tabindex="0">
                    <Mention v-if="active.mention" />
                </div>
                <div class="tab-pane fade" id="grades-pane" role="tabpanel" aria-labelledby="grades" tabindex="0">
                    <Grade v-if="active.grade"/>
                </div>
                <div class="tab-pane fade" id="parcours-pane" role="tabpanel" aria-labelledby="parcours" tabindex="0">
                    <Parcour v-if="active.parcour"/>
                </div>
                <div class="tab-pane fade" id="ues-pane" role="tabpanel" aria-labelledby="ues" tabindex="0">
                    <UE v-if="active.ue" />
                </div>
                <div class="tab-pane fade" id="matieres-pane" role="tabpanel" aria-labelledby="matieres" tabindex="0">
                    <Matiere v-if="active.matiere" />
                </div>
                <div class="tab-pane fade" id="tp-pane" role="tabpanel" aria-labelledby="tp" tabindex="0">
                    <TP v-if="active.tp"/>
                </div>
            </div>

        </div>
        
    </section>
    <div v-else class="m-5">
        <Loading />
    </div>
</template>

<script setup>
import TP from '../../components/section/data/TP.vue'
import Loading from '../../components/annimate/Loading.vue'
import Matiere from '../../components/section/data/Matiere.vue'
import UE from '../../components/section/data/UE.vue'
import AnneeUniversitaire from '../../components/section/data/AnneeUniversitaire.vue'
import Parcour from '../../components/section/data/Parcour.vue'
import Grade from '../../components/section/data/Grade.vue'
import Mention from '../../components/section/data/Mention.vue'
import GestionAcademique from '../../components/section/data/GestionAcademique.vue'
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import { useData } from '@/stores/data'




    const data=useData()


    const active=ref({
        annee:true,
        mention: false,
        grade: false,
        parcour: false,
        ue: false,
        matiere: false,
        tp:false
    })

    const setActive=(tab)=>{
        active.value[tab]=true
    }

    onMounted(()=>{
        window.scrollTo(0,0);
    })


</script>


