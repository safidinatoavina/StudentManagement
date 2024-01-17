<template>
    <section>
        <div class="row mx-5 mt-3" v-if="!Evenement.getPending.show && eventData?.titre!=undefined">
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
            <div class="col-12 mt-1">
                <button @click="Evenement.updateEvenement(Route.params.evenement,eventData)" class="my-3 btn btn-primary w-100">
                    Enregistrer
                    <Spinner v-if="Evenement.getPending.update" class="ms-2" />
                </button>
            </div>
        </div>
        <div v-else class="mt-5">
            <Loading />
        </div>
    </section>
</template>

<script setup>
import Loading from '../../annimate/Loading.vue'
import Spinner from '../../annimate/Spinner.vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import UseUploadAdapterPlugin from '@/composant/ckeditor-plugins'
import { computed, onBeforeMount, onMounted, ref } from "vue";
import { useEvenement } from "@/stores/evenement"
import { useRoute } from 'vue-router';

const Evenement=useEvenement()
const Route=useRoute()
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

onMounted(()=>{
    let find_evenement=Evenement.getEvenements.find(el=>el.id==Route.params.evenement)
    
    eventData.value.titre=find_evenement?.titre
    eventData.value.contenu=find_evenement?.contenu
    eventData.value.is_active=find_evenement?.is_active

    if(!find_evenement){
        Evenement.showEvenement(Route.params.evenement,eventData)
    }
})

</script>

