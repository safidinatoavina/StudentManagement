<template>
    <div class="pt-5 px-5">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 my-1">
                <input type="text" class="form-control" v-model="editModel.parcour" placeholder="Parcour" >
            </div>
            <div class="col-12 col-sm-6 col-md-4 my-1">
                <input type="text" class="form-control" v-model="editModel.abreviation" placeholder="abreviation" >
            </div>
            <div class="col-12 col-sm-6 col-md-4 my-1">
                <AutoComplete  v-model="editModel.mention" disabled 
                    :suggestions="suggestionMention" 
                    @complete="searchMention($event)" 
                    placeholder="Mention"
                    :dropdown="true" optionLabel="mention" forceSelection>

                        <template #item="slotProps">
                            <div class="country-item">
                                <div class="ml-2"> {{ slotProps.item.mention }} </div>
                            </div>
                        </template>

                </AutoComplete>
            </div>

            <div class="col-12 col-sm-6 col-md-4 my-1">
                <AutoComplete  v-model="editModel.grade"  disabled
                    :suggestions="suggestionGrade" 
                    @complete="searchGrade($event)" 
                    placeholder="Grade"
                    :dropdown="true" optionLabel="grade" forceSelection>

                        <template #item="slotProps">
                            <div class="country-item">
                                <div class="ml-2"> {{ slotProps.item.grade +' niveau: '+slotProps.item.niveau }} </div>
                            </div>
                        </template>

                </AutoComplete>
            </div>

            <div class="col-12 col-sm-6 col-md-4 my-1">
                <AutoComplete v-model="editModel.responsable"
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

            <div class="col-12 col-sm-6 col-md-4 my-1">
                <AutoComplete v-model="editModel.jury" 
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


        </div>

        <!-- FOOTER -->
        <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" :id="'cancel-btn-'+data.id" data-bs-dismiss="modal">
                Annuler
            </button>
            <button 
            type="button" class="btn btn-primary"
            @click="updateParcour"
            >
                Enregistrer
                <Spinner v-if="Data.pendings.update_parcour" />
            </button>
        </div>
    </div>
</template>


<script setup>
import Spinner from '../../annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { useAdmin } from '@/stores/admin'
import { useData } from '@/stores/data'
import { ref, watchEffect } from "vue";
import {  useToast } from "primevue/usetoast";


const editModel=ref({})
const suggestionMention=ref([])
const suggestionGrade=ref([])
const suggestionResponsable=ref([])
const suggestionJury=ref([])
const Data=useData()
const Admin=useAdmin()
const toast=useToast()


const props=defineProps({
    data:{
        type:Object,
        required: true
    }
})

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


const updateParcour=()=>{

    Data.updateParcour(editModel.value,
    ()=>{
        toast.add({
            severity:'success', summary: 'Mis à jour parcour', detail:'Mis à jour parcour avec succès', life: 3000
        });

        let cancelBtn=document.getElementById(`cancel-btn-${props.data.id}`)
        cancelBtn?.click()
    })
}



watchEffect(()=>{
    editModel.value={...props.data}
    editModel.value.responsable=props.data.user_responsables.length?props.data.user_responsables[0]:''
    editModel.value.jury=props.data.jury.length?props.data.jury[0]:''
})



</script>
