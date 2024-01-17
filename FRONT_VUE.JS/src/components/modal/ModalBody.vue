<template>
    
    <div class="modal fade" :id="idModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" 
    aria-hidden="true">
        <div class="modal-dialog" :class="[isCenter?'modal-dialog-centered':'',sizeClass]">
            <div class="modal-content">
                <div class="modal-body p-0">
                    
                    <svg @click="$emit('close')" class="bi bi-x-square-fill position-absolute end-0 border  text-danger border-0 p-0"
                    style="top:0px;cursor:pointer"
                    data-bs-dismiss="modal" aria-label="Close"
                    xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                    </svg>
            
                    <slot></slot>
                </div>

            </div>
        </div>
    </div>

 </template>

<script setup>
defineProps({
    idModal:String,
    isCenter:{
        type:Boolean,
        default: true
    },
    sizeClass:{
        type:String,
        default: 'modal-md'
    }
})
</script>
