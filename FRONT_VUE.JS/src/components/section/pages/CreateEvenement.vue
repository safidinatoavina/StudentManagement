<template>
    <section>
        <div class="row mx-5 mt-3">
            <div class="col-12">
                <label for="titre-evenement" class="fw-bold">Titre</label>
                <input v-model="eventData.titre" type="text" id="titre-evenement" class="form-control" placeholder="Titre">
            </div>
            <div class="col-12">
                <label for="titre-evenement" class="fw-bold">Contenue</label>
                <ckeditor :editor="editor" v-model="eventData.contenu" :config="editorConfig"></ckeditor>            
            </div>
            <div class="col-12">
                <div class="my-2 fw-bold">Statut de l'évènement</div>
                <div class="d-flex">
                    <div class="me-2">
                        <input value="1"  v-model="eventData.is_active" type="radio" id="actif">
                        <label for="actif">Actif</label>
                    </div>
                    <div class="ms-2">
                        <input value="0" v-model="eventData.is_active" type="radio" id="deactif">
                        <label for="deactif">Désactivé</label>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button @click="Evenement.saveEvenement(eventData)" class="my-3 btn btn-primary w-100">
                    Enregistrer
                    <Spinner v-if="Evenement.getPending.save" class="ms-2" />
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import UseUploadAdapterPlugin from '@/composant/ckeditor-plugins'
import { computed, ref } from "vue";
import { useEvenement } from "@/stores/evenement"

const Evenement=useEvenement()
const eventData=ref({})
const editor=ClassicEditor

const editorConfig=ref({

    toolbar:{
        items:[
            "heading","|","bold","italic","link",
            "bulletedList","numberedList","|","outdent",
            "indent","|","uploadImage","blockQuote","insertTable",
            "undo","redo"
        ]
    },
    placeholder: "Contenu de l'événement!",
    extraPlugins: [ UseUploadAdapterPlugin ],

})


</script>

