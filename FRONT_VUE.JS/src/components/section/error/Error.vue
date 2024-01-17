<template>
    <div v-if="error" class="text-danger">
        <div v-for="(errors,index) in error " :key="`error-${index}`">
            <ul>
                <li v-for="(e,i) in errors" :key="`error-message-${i+index}`">
                    <span>{{ e }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

defineProps({
    error:{
        type:Object,
        default:{}
    }
})

</script>
