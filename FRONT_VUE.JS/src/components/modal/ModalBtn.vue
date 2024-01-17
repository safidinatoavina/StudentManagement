<template>
    <div data-bs-toggle="modal" :data-bs-target="`#${idModal}`">
        <slot></slot>
    </div>
</template>

<script setup>

defineProps({
    idModal:String
})

</script>