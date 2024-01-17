<template>
    <Modal :id-modal="`modal-id-12566`" :is-center="false">
        <template #btn>
            <h5>
                <i class="fa fa-backspace fa-2x text-primary back" title="back"></i>
            </h5>
        </template>
        <template #body>
            <div class="pt-5 pb-2 text-center">
                <div>
                    <icon-warning-triangle class="text-warning" />
                    <span class="ms-2">
                        {{ text }}
                    </span> 
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <button :id="`close-modal-12566`" class="btn btn-secondary btn-sm me-2" 
                    data-bs-dismiss="modal">Non</button>
                    
                    <button @click="$emit('confirm')" data-bs-dismiss="modal"
                    class="btn btn-primary btn-sm me-2"
                    >
                    <span>Oui</span>

                    </button>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script setup>
import IconWarningTriangle from '../icons/IconWarningTriangle.vue'
import Modal from './Modal.vue'

defineProps({
    text:{
        type: String,
        default:  'vous voulez vraiment confirmer cette action ?'
    }
})

</script>
