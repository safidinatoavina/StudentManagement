<template>
    <section class="container">
        <h5 class="text-center mt-2 text-primary text-decoration-underline bg-white">
            Espace Secretaire
        </h5>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="liste-etudiant-tab" data-bs-toggle="tab" data-bs-target="#liste-etudiant-tab-pane" type="button" role="tab" aria-controls="liste-etudiant-tab-pane" aria-selected="true">
                    Fiche de présence
                </button>
            </li>
            <li class="nav-item" role="presentation" >
                <button class="nav-link" id="relever-tab" data-bs-toggle="tab" data-bs-target="#relever-tab-pane" type="button" role="tab" aria-controls="relever-tab-pane" aria-selected="false">
                    Relevé des notes
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="liste-etudiant-tab-pane" role="tabpanel" aria-labelledby="liste-etudiant-tab" tabindex="0">
                <ListeEtudiantSecretaire />
            </div>
            <div class="tab-pane fade" id="relever-tab-pane" role="tabpanel" aria-labelledby="relever-tab" tabindex="0">
                <ReleverNote />
            </div>
        </div>

    </section>
</template>

<script setup>
import ListeEtudiantSecretaire from '../../components/section/tabs/ListeEtudiantSecretaire.vue'
import ReleverNote from '../../components/section/tabs/ReleverNote.vue'

</script>
