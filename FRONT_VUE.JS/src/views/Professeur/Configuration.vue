<template>
    <section class="p-3">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                    Vider Note
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <SuppressionNote />
            </div>
        </div>
    </section>
</template>

<script setup>
import SuppressionNote from '../../components/section/tabs/SuppressionNote.vue'
</script>
