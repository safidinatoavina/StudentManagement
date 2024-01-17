<template>
    <section class="conatiner my-2 px-3">


        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li v-if="false" class="nav-item" role="presentation">
                <button class="nav-link" id="global-tab" data-bs-toggle="tab" data-bs-target="#global-tab-pane" type="button" role="tab" aria-controls="global-tab-pane" aria-selected="false">
                    GLOBAL
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link active px-5" id="ue-tab" data-bs-toggle="tab" data-bs-target="#ue-tab-pane" type="button" role="tab" aria-controls="ue-tab-pane" aria-selected="true">
                    UE et ECUE
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="note-tab" data-bs-toggle="tab" data-bs-target="#note-tab-pane" type="button" role="tab" aria-controls="note-tab-pane" aria-selected="false">
                    NOTE
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stat-tab" data-bs-toggle="tab" data-bs-target="#stat-tab-pane" type="button" role="tab" aria-controls="stat-tab-pane" aria-selected="false">
                    STATISTIQUES
                </button>
            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="global-tab-pane" role="tabpanel" aria-labelledby="global-tab" tabindex="0">
                <VerificationGlobal />
            </div>
            <div class="tab-pane fade show active" id="ue-tab-pane" role="tabpanel" aria-labelledby="ue-tab" tabindex="0">
                <VerificationUe />
            </div>
            <div class="tab-pane fade" id="note-tab-pane" role="tabpanel" aria-labelledby="note-tab" tabindex="0">
                <VerificationNote />
            </div>
            <div class="tab-pane fade" id="stat-tab-pane" role="tabpanel" aria-labelledby="stat-tab" tabindex="0">
                <StatMatiereParcour />
            </div>
        </div>

    </section>
</template>

<script setup>
import StatMatiereParcour from '../../components/section/verification/statistique/StatMatiereParcour.vue'
import VerificationNote from '../../components/section/verification/VerificationNote.vue'
import VerificationGlobal from '../../components/section/verification/VerificationGlobal.vue'
import VerificationUe from '../../components/section/verification/VerificationUe.vue'
import { onMounted, ref } from "vue"



onMounted(()=>{
    window.scrollTo(0,0)
})

</script>