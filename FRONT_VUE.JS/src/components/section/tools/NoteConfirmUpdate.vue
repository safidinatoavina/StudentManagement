<template>
    <Modal :is-center="false" :idModal="`modal-id-${dataNote.id}`" size-class="modal-lg" class="text-start">
        <template #btn>   
            <slot></slot>
        </template>
        <template #body>
            <div class="m-3">
                <div class="text-primary text-decoration-underline fw-bolder text-center">
                    Veuillez confirmer la modification
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">N° inscription</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom(s)</th>
                            <th scope="col">Note</th>
                            <th scope="col">Changer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">{{ historique.numeroInscription }}</th>
                            <td>{{ historique.etudiant.personne.nom }}</td>
                            <td>{{ historique.etudiant.personne.prenom }}</td>
                            <td>
                                {{ dataNote.valeur }}
                            </td>
                            <td>
                                <input type="number" class="form-control" v-model="model_note">
                            </td>
                        </tr>
        
                    </tbody>
                </table>

                <div v-if="Note.getErrors.update_note"
                class="my-1 text-danger">
                    {{ Note.getErrors.update_note }}
                </div>

                <div class="d-flex justify-content-end">
                    <div @click="emit('cancel')" class="btn btn-secondary m-1" data-bs-dismiss="modal" :id="`close-modal-note-${dataNote.id}`">
                        cancel
                    </div>
                    <div class="btn btn-info m-1 fw-bolder" 
                    @click="Note.UpdateNote({
                        id             : dataNote.id,
                        valeur         : model_note
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
import { ref } from '@vue/reactivity'

const obj=defineProps({
    dataNote: Object,
    historique:Object
})

const emit=defineEmits(['saved','cancel'])

const model_note=ref(obj.dataNote?.valeur||0)

const Note=useNote()


const callback=()=>{
    document.getElementById(`close-modal-note-${obj.dataNote.id}`)?.click()
    emit('saved')
}

</script>
