<template>
    <Modal :is-center="false" size-class="modal-lg" class="text-start" @close="emit('cancel')">
        <template #btn>   
            <slot></slot>
        </template>
        <template #body>
            <div class="m-3">
                <div class="text-primary text-decoration-underline fw-bolder text-center">
                    Veuillez confirmer l'enregistrement
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">N° inscription</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom(s)</th>
                            <th scope="col">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">{{ historique.numeroInscription }}</th>
                            <td>{{ historique?.etudiant?.personne?.nom }}</td>
                            <td>{{ historique?.etudiant?.personne?.prenom }}</td>
                            <td>
                                <input placeholder="Exemple: 3.5"
                                type="number" v-model="note_value" class="form-control">
                            </td>
                        </tr>
        
                    </tbody>
                </table>

                <div v-if="Note.getErrors.save_note"
                class="my-1 text-danger">
                    {{ Note.getErrors.save_note }}
                </div>

                <div class="d-flex justify-content-end">

                    <div @click="emit('cancel')" class="btn btn-secondary m-1" data-bs-dismiss="modal" id="close-modal-note">
                        cancel
                    </div>

                    <div class="btn btn-info m-1 fw-bolder" v-if="!isTP"
                    @click="Note.saveNote({
                        historique_id  : historique.id,
                        parcour_id,
                        matiere_id     : $route.params.matiere || matiere_id,
                        valeur         : note_value,
                        id             : Note.getNotes?.[$route.params.parcour || parcour_id].find(el=>el.historique_id==historique.id)?.id
                    },callback)"
                    >
                        <span class="me-1">confirm</span>
                        <Spinner v-if="Note.getPendingNote" />
                    </div>

                    <div class="btn btn-info m-1 fw-bolder" v-else
                    @click="Note.saveNoteTP({
                        historique_id  : historique.id,
                        tp_id     : $route.params.tp || tp_id, //tp_id pour le operateur de saisie
                        valeur         : note_value,
                        parcour_id,
                        id             : Note.getNotesTP?.[$route.params.parcour || parcour_id ].find(el=>el.historique_id==historique.id)?.id  
                    },callback)"
                    >
                        <span class="me-1">confirm</span>
                        <Spinner v-if="Note.getPendingNote" />
                    </div>

                </div>


            </div>
        </template>
    </Modal>

</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import Modal from '../../modal/Modal.vue'
import { useNote } from '@/stores/note'
import axios from 'axios'
import { ref } from '@vue/reactivity'

const props=defineProps({
    historique: Object,
    isTP:{
        type:Boolean,
        dafault:false
    },
    tp_id: Number, //pour operateur de saisie
    matiere_id: Number, //pour operateur de saisie
    parcour_id:Number //pour operateur de saisie
})

const emit=defineEmits(['saved','cancel'])

const Note=useNote()

const note_value=ref('')

const callback=()=>{
    document.getElementById('close-modal-note')?.click()
    emit('saved')
}

</script>


