<template>
    <div class="mt-5 px-5">
        <div class="card" v-if="detail">
            <h5 class="card-header">
                {{ detail?.title }}
            </h5>
            <div class="card-body">
                <p v-html="detail?.content" class="card-text">
                </p>
                <small>{{ detail?.time }}</small>
                <div>
                    <i @click="Notification.deleteNotification(detail?.id)" v-if="!Notification.getPendings.delete_notification?.[detail.id]" class="fa fa-trash text-danger" style="cursor:pointer"></i>
                    <Spinner v-else class-color="text-danger" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Spinner from '../../components/annimate/Spinner.vue'
import { useNotification } from '@/stores/notification'
import { computed, onMounted } from 'vue'
import { onBeforeRouteUpdate, useRoute } from 'vue-router'
const Notification = useNotification()
const route=useRoute()
const detail = computed(() => Notification.getNotifications.data?.find((el) => el.id == route.params.notification))

onBeforeRouteUpdate((to,from) => {
    window.scrollTo(0, 0)
    if(from.params.notification!=to.params.notification)
        Notification.readNotification(to.params.notification)
})

onMounted(() => {
    window.scrollTo(0, 0)
    if(route.params.notification)
        Notification.readNotification(route.params.notification)
})

</script>
