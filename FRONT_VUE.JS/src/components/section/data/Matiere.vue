<template>
    <section>

                           <!-- ECUE -->

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
                                <input v-model="postData.matiere"
                                type="text" 
                                class="form-control" placeholder="ECUE">
                            </div>
                            <div class="col-6 col-lg-3 my-2">
                                <input v-model="postData.coefficient"
                                type="text" 
                                class="form-control" placeholder="credit dans l'offre">
                            </div>

                            <div class="col-6 col-lg-3 my-2">
                                <AutoComplete v-model="postData.ue" 
                                :suggestions="suggestionUes" 
                                @complete="searchUes($event)" 
                                placeholder="Ue"
                                :dropdown="true" optionLabel="ue" forceSelection>
    
                                    <template #item="slotProps">
                                        <div class="country-item">
                                            <div class="ml-2">{{slotProps.item.ue + ` (${slotProps.item.parcour.abreviation})`}} ({{ slotProps.item.semestre.semestre }})</div>
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
    
                        <button @click="sendMatiere"
                        class="btn btn-primary btn-sm">
                        <span>Enregistrer</span>
                        <Spinner v-if="Data.pendings.add_matiere" />
                        </button>
    
    
                    </div>
                </div>
            </div>
        </div>
    
    
        <div class="mt-2">
    
            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="is_responsable_parcour?myParcoursMatieres:Data.matiers" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >
    
            <template #header>
                <div class="d-flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </span>
                    <div v-if="false">
                            <label for="import-matiere" class="btn btn-primary ms-1">
                                <IconUpload class="me-1"/> Importer
                            </label>
                            <input type="file" ref="fileInput" id="import-matiere" @change="importMatiere" style="display:none" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <a class="dropdown-toggle template text-primary ms-2" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                             <button class="btn btn-primary">Template</button>
                            </a>
                            <ul class="dropdown-menu">
                                <li @click="DataIO.getMatiereHead('xlsx')">
                                    <a class="dropdown-item" href="#">En tete Excel</a>
                                </li>
                                <li @click="DataIO.getMatiereHead('csv')" >
                                    <a class="dropdown-item" href="#">En tete Excel CSV</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.import_matiere"/>
                    </div> 
                </div>
            </template>
    
            <Column :sortable="true" field="id" header="ID"></Column>
            <Column :sortable="true" field="matiere" header="ECUE"></Column>
            <Column :sortable="true" field="coefficient" header="CRÉDIT"></Column>
            <Column  header="Parcours">
                <template #body="{data}">
                    <div>
                        {{ data.parcour.parcour }} {{ data.parcour.parcour?'('+data.parcour?.abreviation+')':'' }}
                    </div>
                </template>
            </Column>
            <Column :sortable="true" field="semestre.semestre" header="SEMESTRE"></Column>
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
            <Column :sortable="true" field="ue.ue" header="UE"></Column>
            <Column header="Status" v-if="false">
            <template #body="{data}">
                <div>
                    <select v-model="statusModel[data.id]" @change="updateStatus(data.id)">
                        <option value="1">Actif</option>
                        <option value="0">Désactiver</option>
                    </select>
                </div>
            </template>
            </Column>
            <Column  header="ACTIONS" v-if="!is_responsable_parcour">
            <template #body="{data}">
                <div class="d-flex">
                    <Modal size-class="modal-lg" :id-modal="'modal-matiere-'+data.id" :is-center="false">
                        <template #btn>
                            <div class="btn btn-sm me-2 p-1 text-primary">
                                <i class="fa fa-edit fs-5 "></i>
                            </div>
                        </template>
                        <template #body>
                            <EditMatiere :is_responsable_parcour="is_responsable_parcour" :matiere="data" />
                        </template>
                    </Modal>
                    <div :id="data.id" @click="Data.deleteMatiere(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_matiere || !Data.pendings.del_matiere[data.id] " 
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
import EditMatiere from '@/components/section/edit/EditMatiere.vue'
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

    
    const suggestionParcour=ref([])
    const suggestionUser=ref([])
    const suggestionUes=ref([])
    const statusModel=ref({})

    const filters = ref({
            'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
        });
    
    const postData=ref({
        user:'',
        matiere:'',
        ue:'',
        coefficient:''
    })


    const props=defineProps({
        is_responsable_parcour: {
            type: Boolean,
            default: false
        }
    })


    const myParcoursMatieres=computed(()=>{
        return Data.matiers?.filter((el)=>route.params.parcour==el.parcour.id && AuthStore.Responsable.find(parcour=>parcour.id==route.params.parcour))
    })

    
    const sendMatiere=()=>{
        const data_send={
            user_id:postData.value.user?.id,
            ue_id: postData.value.ue?.id,
            matiere: postData.value.matiere,
            semestre_id: postData.value.semestre?.id,
            coefficient:postData.value.coefficient
        }
    
        const finish=()=>{
    
            postData.value.user=''
            postData.value.matiere=''
            postData.value.ue=''
            postData.value.coefficient=''
    
            toast.add({
                severity:'success', summary: 'Ajout matière', detail:'Création de matière succès', life: 3000
            });
    
        }
    
        Data.addMatiere(data_send,finish)
    
    
    }
    
    
    const showSuccessDelete = () => {
        toast.add({
            severity:'success', summary: 'Suppression matière', detail:'Suppréssion matière succès', life: 3000
        });
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

    
    const searchUes=(event)=>{

        setTimeout(() => {
            if (!event.query.trim().length) {
                if(props.is_responsable_parcour)
                    suggestionUes.value = [...Data.ues?.filter((el)=>el.parcour.id==route.params.parcour)];
                else
                    suggestionUes.value = [...Data.ues];

            }
            else {

                if(props.is_responsable_parcour){

                    suggestionUes.value = Data.ues.filter((el)=>el.parcour.id==route.params.parcour)
                                                    .filter((ue) => {
    
                        return `${ue.ue}(${ue.parcour.abreviation})(${ue.semestre.semestre}) ${ue.parcour.parcour}`.toLowerCase().includes(event.query.toLowerCase());
    
                    });

                }else{


                    suggestionUes.value = Data.ues.filter((ue) => {
                        
                        return `${ue.ue}(${ue.parcour.abreviation})(${ue.semestre.semestre}) ${ue.parcour.parcour}`.toLowerCase().includes(event.query.toLowerCase());
 
                    });

                }
            }
        }, 150);
    }

    const updateStatus=(id)=>{
        Data.setStatusMatiere(id,statusModel.value[id])
    }

    const importMatiere=(event)=>{

        const file = event.target.files[0]
        if (!file) {
            alert('fichier invalide')
            return
        }

        var formData = new FormData()
        formData.append('file',file)

        DataIO.importMatiere(formData)

        fileInput.value.value=''

    }

    watchEffect(()=>{
        Data.matiers?.forEach((el)=>{
            statusModel.value[el.id]=el.status
        })
    })
    
</script>
    
    