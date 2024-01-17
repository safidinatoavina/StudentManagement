<template>
    <div class="text-danger ms-1" v-if="errors[field]">
        <div v-for="(error) in errors[field]" :key="error">
            {{ error }}
        </div>
    </div>
</template>

<script setup>
defineProps({
    errors:Object,
    field: String
})
</script>

<style scoped>
div{
    font-size: 12px;
}
</style>
