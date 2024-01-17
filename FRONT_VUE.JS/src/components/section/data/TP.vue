<template>
    <section>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                    <div class="text-primary">
                        Ajouter
                    </div>
                </button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
    
                        <div class="row mb-2">
                            <div class="col-6 col-lg-3 my-2">
                                <input v-model="postData.tp"
                                type="text" 
                                class="form-control" placeholder="Nom TP">
                            </div>
                            <div class="col-6 col-lg-3 my-2">
                                <AutoComplete v-model="postData.matiere" 
                                :suggestions="suggestionMatiere" 
                                @complete="searchMatiere($event)" 
                                placeholder="Matiere"
                                :dropdown="true" optionLabel="matiere" forceSelection>
    
                                    <template #item="slotProps">
                                        <div class="country-item">
                                            <div class="ml-2">{{slotProps.item.matiere}} {{'('+slotProps.item.parcour.parcour+')'}} {{ slotProps.item.semestre.semestre }}</div>
                                        </div>
                                    </template>

                                </AutoComplete>
                            </div>

                            <div class="col-6 col-lg-3 my-2">
                                <AutoComplete v-model="postData.user" 
                                    :suggestions="suggestionUser" 
                                    @complete="searchUser($event)" 
                                    placeholder="Professeur"
                                    :dropdown="true" optionLabel="personne.nom" forceSelection>
        
                                        <template #item="slotProps">
                                            <div class="country-item">
                                                <div class="ml-2">{{slotProps.item.personne.nom}} {{ slotProps.item.personne.prenom||'' }}</div>
                                            </div>
                                        </template>
    
                                </AutoComplete>
                            </div>
    
                            <Error :error="Data.error.matiere"></Error>
    
                        </div>
    
                        <button @click="sendTP"
                        class="btn btn-primary btn-sm"
                        >
                            <span>Enregistrer</span>
                            <Spinner v-if="Data.pendings.add_tp" />
                        </button>
    
    
                    </div>
                </div>
            </div>
        </div>
    
    
        <div class="mt-2">
    
            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="is_responsable_parcour?myParcoursTps:Data.tps" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >
    
            <template #header>
                <div class="d-flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </span>
                </div>
            </template>
    
            <Column :sortable="true" field="id" header="ID"></Column>
            <Column :sortable="true" field="tp" header="TP"></Column>
            <Column filterField="matiere"  header="ECUE">
                <template #body="{data}">
                    <div>
                        {{ data.matiere.matiere }} {{ data.matiere.matiere?'('+data.matiere.parcour.parcour+')':'' }}
                    </div>
                </template>
            </Column>
            <Column :sortable="true" field="matiere.semestre.semestre" header="Semestre"></Column>
            <Column  header="PROFESSEUR">
                <template #body="{data}">
                    <a href="#" v-if="data.professeur?.personne?.nom||data.professeur?.personne?.prenom">
                        {{ (data.professeur?.personne?.nom||'')+' '+ (data.professeur?.personne?.prenom||'') }}
                    </a>
                    <a href="#" v-else>
                        Aucun
                    </a>
                </template>
            </Column>

            <Column  header="ACTIONS" v-if="!is_responsable_parcour">
            <template #body="{data}">
                <div class="d-flex">
                    <Modal size-class="modal-lg" :id-modal="'modal-tp-edit-'+data.id" :is-center="false">
                        <template #btn>
                            <div class="btn btn-sm me-2 p-1 text-primary">
                                <i class="fa fa-edit fs-5 "></i>
                            </div>
                        </template>
                        <template #body>
                            <EditTP  :tp="data" />
                        </template>
                    </Modal>
                    <div :id="data.id" v-if="!is_responsable_parcour" @click="Data.deleteTP(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_tp || !Data.pendings.del_tp[data.id] " 
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
import IconUpload from '../../icons/IconUpload.vue'
import Modal from '../../modal/Modal.vue'
import Error from '../error/Error.vue'
import EditTP from '@/components/section/edit/EditTP.vue'
import Spinner from '../../annimate/Spinner.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { useToast } from "primevue/usetoast";
import { ref } from '@vue/reactivity'
import { useData } from '@/stores/data'
import { useAdmin } from '@/stores/admin'
import { computed, onMounted, watchEffect } from '@vue/runtime-core'
import { useDataIO } from '@/stores/data_io'
import { useAuthStore } from '@/stores/auth'
import { useRoute } from 'vue-router'



    
    const Data=useData()
    const toast=useToast()
    const Admin=useAdmin()
    const DataIO=useDataIO()
    const fileInput=ref('')
    const AuthStore=useAuthStore()
    const route=useRoute()


    
    const suggestionMatiere=ref([])
    const suggestionUser=ref([])
    const statusModel=ref({})

    const filters = ref({
            'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
        });
    
    const postData=ref({
        user:'',
        matiere:'',
        ue:'',
    })


    const props=defineProps({
        is_responsable_parcour: {
            type: Boolean,
            default: false
        }
    })


    const myParcoursTps=computed(()=>{
        return Data.tps?.filter((el)=>AuthStore.Responsable.find(parcour=>parcour.id==el.matiere?.parcour?.id))
                        .filter((el)=>el.matiere?.parcour?.id==route.params.parcour)
    })

    
    const sendTP=()=>{
        const data_send={
            user_id:postData.value.user?.id,
            matiere_id: postData.value.matiere?.id,
            tp:postData.value.tp
        }
    
        const finish=()=>{
    
            postData.value.user=''
            postData.value.matiere=''
            postData.value.tp=''
    
        }
    
        Data.addTP(data_send,finish)
    
    
    }
    
    
    const showSuccessDelete = () => {
        toast.add({
            severity:'success', summary: 'Suppression TP', detail:'Suppréssion TP succès', life: 3000
        });
    }
    
    

    
    const searchMatiere=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {

                if(!props.is_responsable_parcour){
                    suggestionMatiere.value = [...Data.matiers];
                }else{
                    suggestionMatiere.value = [...Data.matiers.filter(matiere_el=>{
                        return matiere_el.parcour.id==route.params.parcour
                    })];
                }
            }
            else {

                if(!props.is_responsable_parcour){
                    suggestionMatiere.value = Data.matiers.filter((matiere) => {
                        return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
                    });
                }else{
                    suggestionMatiere.value = Data.matiers.filter(matiere_el=>{
                        return matiere_el.parcour.id==route.params.parcour
                    }).filter((matiere) => {
                        return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
                    });
                }
            }
        }, 150);
    }
    
    const searchUser=(event)=>{

        setTimeout(() => {

            const prof_list=(Admin.getAdmins.filter(e=>e.roles.find(el=>el.id===3)))
            
            if (!event.query.trim().length) {
                suggestionUser.value = [...prof_list];
            }
            else {
                suggestionUser.value = prof_list.filter((user) => {

                    let resultNom=user.personne.nom?.toLowerCase().includes(event.query.toLowerCase());
                    let resultPrenom=user.personne.prenom?.toLowerCase().includes(event.query.toLowerCase());

                    return resultNom || resultPrenom

                });
            }
        }, 150);
    }

    const updateStatus=(id)=>{
        Data.setStatusMatiere(id,statusModel.value[id])
    }



    watchEffect(()=>{
        Data.matiers?.forEach((el)=>{
            statusModel.value[el.id]=el.status
        })
    })
    
    onMounted(()=>{
        Admin.setAdmins()
    })
</script>
