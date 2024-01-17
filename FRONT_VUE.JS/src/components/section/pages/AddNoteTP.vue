<template>
    <section>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="add-note-tp-tab" data-bs-toggle="tab" data-bs-target="#add-note-tp" type="button" role="tab" aria-controls="add-note-tp" aria-selected="true">
                    Ajout Note
                </button>
                <button class="nav-link" id="liste-etudiant-tp-tab" data-bs-toggle="tab" data-bs-target="#liste-etudiant-tp" type="button" role="tab" aria-controls="liste-etudiant-tp" aria-selected="false">
                    Liste des étudiants
                </button>
                <button class="nav-link" id="liste-validation-tp-tab" data-bs-toggle="tab" data-bs-target="#liste-validation-tp" type="button" role="tab" aria-controls="liste-validation-tp" aria-selected="false">
                    Liste des Validations
                </button>
                <button class="nav-link" id="liste-moyenne-tp-tab" data-bs-toggle="tab" data-bs-target="#liste-moyenne-tp" type="button" role="tab" aria-controls="liste-moyenne-tp" aria-selected="false">
                    Liste des Notes
                </button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-note-tp" role="tabpanel" aria-labelledby="add-note-tp-tab" tabindex="0">

                <div class="d-flex justify-content-end me-4 mt-3">
                    <label class="btn btn-primary me-1">
                        <IconUpload class="me-1"/> 
                        Importer
                        <Spinner v-if="DataIO.getPending.import_note_tp" />
                        <input type="file" class="d-none" @change="importNote" ref="fileInput" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                    </label>
                    <button class="btn btn-primary" 
                    @click="DataIO.exportTemplateNoteTP({parcour_id,tp_id})"
                    >
                        Template <Spinner v-if="DataIO.getPending.export_template_note_tp" />
                    </button>
                </div>


                <EtudiantSearchTP :parcour="parseInt(parcour_id)" :tp="parseInt(tp_id)"></EtudiantSearchTP>

                <DataTable filterDisplay="menu"
                    :filters="filters"
                    :paginator="true" :rows="4" :value="Note.getNotesTP?.[parcour_id]?.filter(el=>el.is_set==1)||[]" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[4,10,20,50]"
                >
        
                    <template #header>
                        <div class="flex justify-content-between">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Recherche" />
                            </span>
                        </div>
                    </template>


                    <Column :sortable="true" field="historique.numeroInscription" header="N° d'inscription"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.nom" header="Nom"></Column>
                    <Column :sortable="true" field="historique.etudiant.personne.prenom" header="Prénom(s)"></Column>
                    <Column :sortable="true" field="valeur" header="Note"></Column>

                </DataTable>
            </div>
            <div class="tab-pane fade" id="liste-etudiant-tp" role="tabpanel" aria-labelledby="liste-etudiant-tp-tab" tabindex="0">
                <ListeEtudiantTP
                    :params="{
                        header:[] ,
                        parcour_id:matiereSelected?.matiere?.parcour?.id || parcour_id ,
                        tp ,
                        session_id:Data.en_cours?.session.id,
                        semestre_id:Data.en_cours?.semestre.id,
                        annee_universitaire_id:Data.annees?.id
                    }"
                />
            </div>
            <div class="tab-pane fade" id="liste-validation-tp" role="tabpanel" aria-labelledby="liste-validation-tp-tab" tabindex="0">
                <ListeEtudiantTP :validation="true"
                    :params="{
                        header:[] ,
                        parcour_id:matiereSelected?.matiere?.parcour?.id || parcour_id ,
                        tp ,
                        session_id:Data.en_cours?.session.id,
                        semestre_id:Data.en_cours?.semestre.id,
                        annee_universitaire_id:Data.annees?.id
                    }"
                />
            </div>
            <div class="tab-pane fade" id="liste-moyenne-tp" role="tabpanel" aria-labelledby="liste-moyenne-tp-tab" tabindex="0">
                <ListeEtudiantTP :note="true"
                    :params="{
                        header:[] ,
                        parcour_id:matiereSelected?.matiere?.parcour?.id || parcour_id ,
                        tp ,
                        session_id:Data.en_cours?.session.id,
                        semestre_id:Data.en_cours?.semestre.id,
                        annee_universitaire_id:Data.annees?.id
                    }"
                />
            </div>

        </div>

    </section>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import IconUpload from '../../icons/IconUpload.vue'
import StatistiqueNoteProf from '../tools/StatistiqueNoteProf.vue'
import ListeEtudiantTP from '../tools/ListeEtudiantTP.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { computed, ref, watchEffect } from 'vue'
import EtudiantSearchTP from '../tools/EtudiantSearchTP.vue'
import { useNote } from '@/stores/note'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useData } from '@/stores/data'
import { useDataIO } from '@/stores/data_io'



const Data=useData()
const DataIO=useDataIO()
const Note=useNote()
const route=useRoute()
const AuthStore=useAuthStore()
const fileInput=ref('')
const parcour_id=ref('')
const tp_id=ref('')

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const matiereSelected=computed(()=> AuthStore.getMatieresTP.find((element)=>element.id==tp_id.value))

// props pour l'operateur
const props=defineProps({
    parcour: Number,
    tp: Number
})

const importNote=(event)=>{

    const file = event.target.files[0]

    if (!file) {
        alert('fichier invalide')
        return
    }

    var formData = new FormData()
    formData.append('file',file)

    DataIO.importNoteTP(formData,props.tp,props.parcour)

    fileInput.value.value=''

}

watchEffect(()=>{
    parcour_id.value= route.params.parcour || props.parcour
    tp_id.value= route.params.tp || props.tp
    if(tp_id.value)
        Note.setNotesTP(tp_id.value)
})

</script>
