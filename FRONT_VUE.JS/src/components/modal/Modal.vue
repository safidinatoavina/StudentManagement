<template>
    <div>
        <modal-btn :id-modal="idModal" >
            <slot name="btn"></slot>
        </modal-btn>
        <modal-body @close="$emit('close')" :id-modal="idModal" :is-center="isCenter" :size-class="sizeClass">
            <slot name="body"></slot>
        </modal-body>
    </div>
</template>


<script setup>
import ModalBody from './ModalBody.vue'
import ModalBtn from './ModalBtn.vue'

defineProps({
    idModal:{
        type:String,
        default: 'modal-default'
    },
    isCenter:{
        type:Boolean,
        default: true
    },
    sizeClass:{
        type:String,
        default: 'modal-md'
    }})

</script>