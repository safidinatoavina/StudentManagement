<template>
    <section class="mt-3">

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="box shadow-sm rounded bg-white mb-3">
                        <div class="box-body p-0">

                            <div v-for="notification in Notification.getNotifications.data" :key="`detail-notification${notification.id}`" 
                             class="p-3 d-flex align-items-center border-bottom osahan-post-header"
                             :class="[notification.is_read?'bg-light':'bg-notification']"
                            >
                                <div class="font-weight-bold mr-3">
                                    <div class="text-truncate fw-bolder">{{ notification.title }}</div>
                                    <div class="small" v-html="notification.content">
                                    </div>
                                    <small>{{ notification.time }}</small>
                                    <div class="d-flex justify-content-between">
                                        <RouterLink :to="{name:'detail-notifications',params:{
                                            notification:notification.id
                                        }
                                        }" href="#" class="text-primary">
                                            détails
                                        </RouterLink>
                                        <i v-if="!Notification.getPendings.delete_notification?.[notification.id]" class="fa fa-trash text-danger" style="cursor:pointer" @click="Notification.deleteNotification(notification.id)"></i>
                                        <Spinner class-color="text-danger" v-else/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2" v-if="Notification.getNotifications.next_page_url&&!Notification.getPendings.fetch_more_notification">
                <a class="text-primary text-center text-decoration-underline" style="cursor:pointer" @click="Notification.loadMoreNotification()">
                    voir plus
                </a>
            </div>
            <div class="row" v-if="Notification.getPendings.fetch_more_notification">
                <Loading />
            </div>
        </div>

    </section>

</template>

<script setup>
import Spinner from '../../components/annimate/Spinner.vue'
import Loading from '../../components/annimate/Loading.vue'
import { RouterLink } from 'vue-router';
import { useNotification } from '@/stores/notification'

const Notification=useNotification()

</script>

<style scoped>

.dropdown-list-image {
    position: relative;
    height: 2.5rem;
    width: 2.5rem;
}
.dropdown-list-image img {
    height: 2.5rem;
    width: 2.5rem;
}
.btn-light {
    color: #2cdd9b;
    background-color: #e5f7f0;
    border-color: #d8f7eb;
}

.bg-notification{
    background-color: #726c6c5e;
}

</style>