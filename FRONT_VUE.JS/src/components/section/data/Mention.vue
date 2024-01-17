
<template>
  <section>
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="text-primary">
                    Ajouter
                </div>
            </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <div class="row mb-2">
                        <div class="col">
                            <input type="text" v-model="mention.mention" class="form-control" placeholder="Mention">

                        </div>
                        <div class="col">
                            <input type="text" v-model="mention.abreviation" class="form-control" placeholder="Abreviation">
                        </div>

                        <Error :error="Data.error.mention"></Error>

                    </div>

                    <button class="btn btn-primary btn-sm" 
                    @click="Data.addMention(mention,showSuccessAdd)">
                        <span>Enregistrer</span>
                        <Spinner v-if="Data.pendings.add_mention" />
                    </button>


                </div>
            </div>
        </div>
    </div>


    <div class="mt-2">

        <DataTable filterDisplay="menu"
            :filters="filters"
            :paginator="true" :rows="4" :value="Data.mentions" responsiveLayout="scroll" 
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
        <Column header="Mention">
        <template #body="{data}">
            <div v-if="!edit[data.id]">{{ data.mention }}</div>
            <div v-else>

                <input v-model="mention[data.id].mention"
                type="text" class="form-control px-2">

            </div>
        </template>
        </Column>
        
        <Column header="Abreviation">
        <template #body="{data}">
            <div v-if="!edit[data.id]">{{ data.abreviation }}</div>
            <div v-else>
                <input v-model="mention[data.id].abreviation"
                type="text" class="form-control px-2">
            </div>
        </template>
        </Column>

        <Column :sortable="true" header="Actions">
        <template #body="{data}">

            <div v-if="!edit[data.id]" class="d-flex">
                <div @click="showFormEdit(data.id)"
                class="btn btn-sm me-2 p-1 text-primary">
                    <i class="fa fa-edit fs-5 "></i>
                </div>

                <div :id="data.id" @click="Data.deleteMention(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                    <i v-if="!Data.pendings.del_mention || !Data.pendings.del_mention[data.id] " 
                    class="fa fa-trash fs-5 "></i>
                    <Spinner v-else classColor="text-danger" />
                </div>
            </div>
            <div v-else>
                <button v-if="!Data.pendings.update_mention" @click="updateMention(data.id)" class="btn btn-primary me-1 p-1">
                    <check-icon></check-icon>
                </button>
                <button v-else class="btn btn-primary me-1 p-1">
                    <Spinner/>
                </button>
                <button @click="edit[data.id]=false" class="btn btn-secondary p-1">
                    <cancel-icon></cancel-icon>
                </button>
            </div>

        </template>
        </Column>

        </DataTable>

    </div>

  </section>
</template>

<script setup>
import CancelIcon from '../../icons/CancelIcon.vue'
import CheckIcon from '../../icons/CheckIcon.vue'
import Error from '../error/Error.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { useToast } from "primevue/usetoast";
import Spinner from '@/components/annimate/Spinner.vue';
import { ref } from '@vue/reactivity';
import { useData } from '@/stores/data'

const Data=useData()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


const mention=ref({})

const toast = useToast();


const edit=ref({})

const showFormEdit=(id)=>{

    let result=Data.mentions?.find(e=>e.id==id)

    if(!result)
        return

    edit.value[id]=true
    mention.value[id]={}
    mention.value[id].mention=result.mention
    mention.value[id].abreviation=result.abreviation

}





const showSuccessAdd = () => {
    toast.add({
        severity:'success', summary: 'Ajout mention', detail:'Création de mention succès', life: 3000
    });
}


const showSuccessDelete = () => {
    toast.add({
        severity:'success', summary: 'Suppression mention', detail:'Suppréssion de mention succès', life: 3000
    });
}


const updateMention=(id)=>{


    const showSuccessUpdate = () => {
        edit.value[id]=false
        toast.add({
            severity:'success', summary: 'Mis à jour mention', detail:'Mis à jour mention succès', life: 3000
        });
    }

    Data.updateMention(id,mention.value[id],showSuccessUpdate)

}

</script>


