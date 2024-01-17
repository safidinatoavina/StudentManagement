<template>
<section>
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                <div class="text-primary">
                    Ajouter
                </div>
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <div class="row mb-2">
                        <div class="col-6 col-lg-3 my-2">
                            <input v-model="postData.parcour"
                            type="text" 
                            class="form-control" placeholder="Parcours">
                        </div>
                        <div class="col-6 col-lg-3 my-2">
                            <input v-model="postData.abreviation"
                            type="text" 
                            class="form-control" placeholder="Abreviation">
                        </div>
                        <div class="col-6 col-lg-3 my-2">
                            <AutoComplete v-model="postData.mention" 
                            :suggestions="suggestionMention" 
                            @complete="searchMention($event)" 
                            placeholder="Mention"
                            :dropdown="true" optionLabel="mention" forceSelection>

                                <template #item="slotProps">
                                    <div class="country-item">
                                        <div class="ml-2">{{slotProps.item.mention}}</div>
                                    </div>
                                </template>

                            </AutoComplete>
                        </div>
                        <div class="col-6 col-lg-3 my-2">
                            <AutoComplete v-model="postData.grade" 
                            :suggestions="suggestionGrade" 
                            @complete="searchGrade($event)" 
                            placeholder="Grade"
                            :dropdown="true" optionLabel="grade" forceSelection>

                                <template #item="slotProps">
                                    <div class="country-item">
                                        <div class="ml-2">{{slotProps.item.grade}} niveau {{ slotProps.item.niveau }}</div>
                                    </div>
                                </template>

                            </AutoComplete>
                        </div>
                        <div class="col-6 col-lg-6 my-2">
                            <AutoComplete v-model="postData.jury" 
                            :suggestions="suggestionJury" 
                            @complete="searchJury($event)" 
                            placeholder="President de Jury"
                            :dropdown="true" optionLabel="personne.nom" forceSelection>

                                <template #item="slotProps">
                                    <div class="country-item">
                                        <div class="ml-2">{{ `${slotProps.item.personne.nom+' '+(slotProps.item.personne.prenom||'')}` }}</div>
                                    </div>
                                </template>

                            </AutoComplete>
                        </div>
                        <div class="col-6 col-lg-6 my-2">
                            <AutoComplete v-model="postData.responsable" 
                            :suggestions="suggestionResponsable" 
                            @complete="searchResponsable($event)" 
                            placeholder="Responsable parcour"
                            :dropdown="true" optionLabel="personne.nom" forceSelection>

                                <template #item="slotProps">
                                    <div class="country-item">
                                        <div class="ml-2">{{ `${slotProps.item.personne.nom+' '+(slotProps.item.personne.prenom||'')}` }}</div>
                                    </div>
                                </template>

                            </AutoComplete>
                        </div>

                        <Error :error="Data.error.parcour"></Error>

                    </div>

                    <button @click="sendParcours"
                    class="btn btn-primary btn-sm">
                    <span>Enregistrer</span>
                    <Spinner v-if="Data.pendings.add_parcour" />
                    </button>


                </div>
            </div>
        </div>
    </div>


    <div class="mt-2">

        <DataTable filterDisplay="menu"
            :filters="filters"
            :paginator="true" :rows="4" :value="Data.parcours" responsiveLayout="scroll" 
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[4,10,20,50]"
        >

        <template #header>
            <div class="flex justify-content-between">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                </span>
            </div>
        </template>

        <Column :sortable="true" field="id" header="ID"></Column>

        <Column header="Parcours" filterField="parcour">
            <template #body="{data}">
                <div>
                    {{ data.parcour }}
                </div>
            </template>
        </Column>

        <Column header="Abreviation" filterField="abreviation">
            <template #body="{data}">
                <div>
                    {{ data.abreviation }}
                </div>
            </template>
        </Column>
        <Column header="Mention" filterField="mention.mention">
            <template #body="{data}">
                <div>
                    {{ data.mention.mention }}
                </div>
            </template>
        </Column>
        <Column header="Grade" filterField="grade.grade">
            <template #body="{data}">
                <div >
                    {{ data.grade.grade }}
                </div>
            </template>
        </Column>
        <Column header="Niveau">
            <template #body="{data}">
                <div>
                    {{ data.grade.niveau }}
                </div>
            </template>
        </Column>
    <!-- responsable  -->
        <Column header="Responsable" filterField="user_responsables.0.personne.nom">
            <template #body="{data}">
            <div>
                <div v-if="!data.user_responsables?.length">
                    Aucun
                </div>
                <div v-else>
                    <span class="text-uppercase">
                        {{ data.user_responsables.personne?.nom || data.user_responsables[0]?.personne?.nom || '' }}
                    </span> 
                        {{ data.user_responsables.personne?.prenom || data.user_responsables[0]?.personne?.prenom || '' }}
                </div>
            </div>
        </template>
        </Column>


        <Column header="Jury" filterField="jury.0.personne.nom">
        <template #body="{data}">
            <div>
                <div v-if="!data.jury.length">
                    Aucun
                </div>
                <div v-else>
                    <span class="text-uppercase">
                        {{ data.jury.personne?.nom || data.jury[0]?.personne?.nom || '' }}
                    </span> 
                        {{ data.jury.personne?.prenom || data.jury[0]?.personne?.prenom || '' }}
                </div>
            </div>

        </template>
        </Column>
        <Column header="Actions">

            <template #body="{data}">
                <div class="d-flex">

                    <Modal size-class="modal-lg" :id-modal="'modal-parcour-edit-'+data.id" :is-center="false">
                        <template #btn>
                            <div class="btn btn-sm me-2 p-1 text-primary">
                                <i class="fa fa-edit fs-5 "></i>
                            </div>
                        </template>
                        <template #body>
                            <div>
                                <EditParcour :data="data" />
                            </div>
                        </template>
                    </Modal>

                    <div :id="data.id" @click="Data.deleteParcour(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_parcour || !Data.pendings.del_parcour[data.id] " 
                        class="fa fa-trash fs-5 "></i>
                        <Spinner v-else classColor="text-danger" />
                    </div>
                </div>
            </template>

        </Column>

        </DataTable>

    </div>
</section>
</template>

<script setup>
import EditParcour from '../edit/EditParcour.vue'
import Modal from '../../modal/Modal.vue'
import Error from '../error/Error.vue'
import Spinner from '../../annimate/Spinner.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { useToast } from "primevue/usetoast";
import { ref } from '@vue/reactivity'
import { useData } from '@/stores/data'
import { watchEffect } from '@vue/runtime-core'
import { useAdmin } from '@/stores/admin'

const Data=useData()
const toast=useToast()
const Admin=useAdmin()

const suggestionMention=ref([])
const suggestionGrade=ref([])
const suggestionJury=ref([])
const suggestionResponsable=ref([])

const postData=ref({
    grade:'',
    mention:'',
    parcour:'',
    abreviation:'',
    jury:'',
    responsable:''
})


const sendParcours=()=>{
    const data_send={
        grade_id:postData.value.grade?.id,
        mention_id:postData.value.mention?.id,
        parcour: postData.value.parcour,
        abreviation: postData.value.abreviation,
        jury_id    : postData.value.jury?.id,
        responsable_id : postData.value.responsable?.id,
    }

    const finish=()=>{

        postData.value.grade=''
        postData.value.mention=''
        postData.value.parcour=''
        postData.value.abreviation=''
        postData.value.jury=''
        postData.value.responsable=''



        toast.add({
            severity:'success', summary: 'Ajout parcour', detail:'Création de parcour succès', life: 3000
        });



    }

    Data.addParcour(data_send,finish)


}



const showSuccessDelete = () => {
    toast.add({
        severity:'success', summary: 'Suppression parcour', detail:'Suppréssion de parcour succès', life: 3000
    });
}




const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const searchMention=(event)=>{
    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionMention.value = [...Data.mentions];
        }
        else {
            suggestionMention.value = Data.mentions.filter((mention) => {
                return mention.mention.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 150);
}

const searchGrade=(event)=>{
    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionGrade.value = [...Data.grades];
        }
        else {
            suggestionGrade.value = Data.grades.filter((grade) => {
                return grade.grade.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 150);
}



const searchResponsable=(event)=>{

    const responsable_list=Admin.getAdmins.filter((e)=>e.roles.find(el=>el.id==6))

    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionResponsable.value = [...responsable_list];
        }
        else {
            suggestionResponsable.value = responsable_list.filter((user) => {
                return `${user.personne.nom+' '+user.personne.prenom}`.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 150);

}

const searchJury=(event)=>{

    const jury_list=Admin.getAdmins.filter((e)=>e.roles.find(el=>el.id==2))

    setTimeout(() => {
        if (!event.query.trim().length) {
            suggestionJury.value = [...jury_list];
        }
        else {
            suggestionJury.value = jury_list.filter((user) => {
                return `${user.personne.nom+' '+user.personne.prenom}`.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 150);

}


</script>

