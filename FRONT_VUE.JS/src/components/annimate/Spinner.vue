<template>
    <div class="spinner-border spinner-border-sm  ms-3" :class="classColor" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</template>

<script setup>

defineProps({
    classColor: {
        type: String,
        default: 'text-white'
    }
})

</script>

