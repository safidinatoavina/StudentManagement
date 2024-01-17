<template>
    <div>
        <div v-if="matiere">
            <div v-if="!Note.pendingObj.liste_note" class="my-5">
                <DataTable :filters="filters" v-model:selection="select_etudiants" :value="Note.getNotes[parcour]" 
                :rows="10"
                :paginator="true"
                responsiveLayout="scroll"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
                dataKey="id" 
                >

                    <template #header>
                        <div class="d-flex justify-content-between">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Recherche" />
                            </span>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column :sortable="true" field="historique.numeroInscription" header="N°INSCRIPTION"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.nom" header="NOM"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.prenom" header="PRENOM(S)"></Column>
                    <Column :sortable="true" sortField="valeur" header="NOTE">
                    <template #body="{data}">
                        <div>
                            {{ data.is_set?data.valeur:'X' }}
                        </div>
                    </template>
                    </Column>
                </DataTable>
            </div>
            <div v-else class="my-5">
                <Loading />
            </div>
        </div>
        <div v-else>
            <div v-if="!Note.pendingObj.liste_note_tp" class="my-5">
                <DataTable 
                :paginator="true"
                :rows="10"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10,20,50]"
                responsiveLayout="scroll"
                :filters="filters" 
                v-model:selection="select_etudiants" 
                :value="Note.getNotesTP[parcour]" 
                dataKey="id" 
                >


                    <template #header>
                        <div class="d-flex justify-content-between">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Recherche" />
                            </span>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column :sortable="true" field="historique.numeroInscription" header="N°INSCRIPTION"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.nom" header="NOM"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.prenom" header="PRENOM(S)"></Column>
                    <Column :sortable="true" sortField="valeur" header="NOTE">
                        <template #body="{data}">
                            <div>
                                {{ data.is_set?data.valeur:'X' }}
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div v-else class="my-5">
                <Loading />
            </div>
        </div>

        <div class="my-2 mx-5">
            <button v-if="select_etudiants.length" @click="viderNotes" class="btn btn-primary">
                vider <Spinner v-if="Note.pendingObj.vider_note" />
            </button>
        </div>

    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { onBeforeMount, ref, watchEffect } from 'vue'
import { useNote }  from '@/stores/note'
const select_etudiants = ref([])

const Note=useNote()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
});

const props = defineProps({
    parcour: Number,
    matiere: {
        type: Number,
        dafault: 0
    },
    tp: {
        type: Number,
        default: 0
    }
})

const viderNotes = () => {

    let note_ids = []

    select_etudiants.value.forEach(element => {
        note_ids.push(element.id)
    });

    if (props.matiere) {
        Note.viderNoteMatiere({
            note_ids: note_ids,
            parcour: props.parcour
        })
    }

    if(props.tp){
        Note.viderNoteTp({
            note_tp_ids: note_ids,
            parcour: props.parcour
        })
    }
}

watchEffect(() => {

    if (props.matiere) {
       Note.setNotes(props.matiere,props.parcour) 
    } else {
        Note.setNotesTP(props.tp,props.parcour)
    }
    
})

</script>
