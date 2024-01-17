<template>
    <section class="m-3">



        <div v-if="!FilterEtudiant.getPending.validation" class="container">

            <div v-if="FilterEtudiant.getValidations.resultat.length">

                <h4 class="text-center m-5 text-decoration-underline text-primary">
                    {{ FilterEtudiant.getValidations.nom }} {{ FilterEtudiant.getValidations.prenom }} ({{ FilterEtudiant.getValidations.annee }})
                </h4>

                <h1 v-if="FilterEtudiant.getValidations.final" class="text-center my-4"
                :class="[FilterEtudiant.getValidations.final.toLowerCase()=='admis'?'text-success':(FilterEtudiant.getValidations.final.toLowerCase()=='redouble'?'text-warning':'text-danger')]"
                >
                    Resultat Final : <span class="text-uppercase text-decoration-underline me-2">{{ FilterEtudiant.getValidations.final }}</span>

                    <img v-if="FilterEtudiant.getValidations.final.toLowerCase()=='admis'"
                     src="/img/gif/wine.gif" alt="(wine)" style="width:70px;height:70px">

                    <EmojyCentralIcon v-else-if="FilterEtudiant.getValidations.final.toLowerCase()=='redouble'" />

                    <EmojyFrownIcon v-else   />

                </h1>

                <div v-if="FilterEtudiant.getValidations.resultat_semestres.length">
                    <h5 class="text-center text-decoration-underline text-primary my-3">Validation par Semestre</h5>
                    <DataTable filterDisplay="menu"
                            showGridlines
                            :paginator="true" :rows="10" :value="FilterEtudiant.getValidations.resultat_semestres" responsiveLayout="scroll" 
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rowsPerPageOptions="[10,20,50]"
                        >


                            <Column :sortable="true" field="semestre" header="Semestre"></Column>
                            <Column :sortable="true" field="validation" header="Validation"></Column>

                    </DataTable>
                </div>

                <DataTable filterDisplay="menu" v-if="false"
                        showGridlines
                        :paginator="true" :rows="10" :value="FilterEtudiant.getValidations.resultat" responsiveLayout="scroll" 
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        :rowsPerPageOptions="[10,20,50]"
                    >
        
        
                        <Column :sortable="true" field="ue" header="Ue"></Column>
                        <Column :sortable="true" field="semestre" header="Semestre"></Column>
                        <Column :sortable="true" field="valeur_session_normal" header="Validation Session normal"></Column>
                        <Column v-if="FilterEtudiant.getValidations.resultat[0].validation!==false" :sortable="true" field="validation" header="Validation"></Column>
        
        
                </DataTable>



                <ul class="list-group">
                    <li class="list-group-item" v-for="(resultat,index) in FilterEtudiant.getValidations.resultat" :key="`resultat-etudiant-${index}`">
                       
                        <div>
                            <div class="p-2">
                                <strong>
                                    UE : {{ resultat.ue }}
                                </strong>,
                                <strong class="ms-2">
                                    SEMESTRE : {{ resultat.semestre }}
                                </strong>,
                                <strong class="ms-2">
                                    SESSION NORMAL : <span :class="[resultat.valeur_session_normal.toLowerCase()=='v'?'text-success':'text-danger','fw-bolder']">{{ resultat.valeur_session_normal }}</span>
                                </strong>,
                                <strong class="ms-2" v-if="resultat.validation!=false">
                                    APRÈS RATTRAPAGE : <span :class="[resultat.validation.toLowerCase()=='v'?'text-success':'text-danger','fw-bolder']">{{ resultat.validation }}</span>
                                </strong>
                                <span v-if="resultat.option==0" class="badge text-bg-primary mx-2">obligatoire</span>
                                <span v-else class="badge text-bg-secondary mx-2">facultatif</span>
                            </div>
                            <div style="cursor:pointer" class="text-primary" @click="FilterEtudiant.getValidations.resultat[index].plus=!FilterEtudiant.getValidations.resultat[index].plus">Detail ECUE {{ resultat.plus?'--':'++' }}</div>
                            <div v-if="resultat.plus">
                                <ul>
                                    <li v-for="(ecue,index) in resultat.matieres" :key="`resultat-ecue${ecue.matiere}-${index}`">
                                        <strong>
                                            ECUE : {{ ecue.matiere }}
                                        </strong>,
                                        <strong>
                                            VALIDATION: <span :class="[ecue.validation.toLowerCase()=='v'?'text-success':'text-danger','fw-bolder']">{{ ecue.validation }}</span>
                                        </strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </li>
                </ul>



            </div>
            <div class="m-5" v-if="!FilterEtudiant.getValidations.resultat_semestres.length && !FilterEtudiant.getValidations.resultat.length">
                <h5 class="fs-1 text-center text-danger">404</h5>
                <h1 class="text-center text-brand text-decoration-underline" >
                    Resultat indisponible 
                </h1>
            </div >
        </div>
        <div v-else class="m-5">
            <Loading/>
        </div>
    </section>
</template>
    
<script setup>
import EmojyFrownIcon from '../../../components/icons/EmojyFrownIcon.vue'
import Loading from '@/components/annimate/Loading.vue'
import Spinner from '@/components/annimate/Spinner.vue'
import EmojyCentralIcon from '../../../components/icons/EmojyCentralIcon.vue'
import DataTable from 'primevue/datatable'
import InputText from 'primevue/inputtext'
import Column from 'primevue/column'
import { useData } from '@/stores/data'
import { onBeforeMount, onMounted, ref, watchEffect } from 'vue'
import { usePublicFilterEtudiant } from '@/stores/public_etudiant.js'
import { useRoute } from 'vue-router'

const route=useRoute()
const FilterEtudiant=usePublicFilterEtudiant()

onBeforeMount(()=>{
    FilterEtudiant.setValidations(route.params.historique)
})

onMounted(()=>{
    window.scrollTo(0,0);
})

</script>

