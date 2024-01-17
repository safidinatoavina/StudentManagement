<template>
    <div>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <span class="position-relative" style="width:50px">
                    <i class="fa fa-bell "></i>
                        <span v-if="Notification.getBadgeNotification" class="position-absolute top-25 start-50 translate-middle badge rounded-pill bg-danger">
                            {{ Notification.getBadgeNotification>99?'99+':Notification.getBadgeNotification }}
                            <span class="visually-hidden">unread notification</span>
                        </span>
                </span>

                <span class="d-none d-lg-inline-flex">Notification</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light rounded-0 rounded-bottom m-0 notification-dropdown border border-info">
                <div v-if="Notification.getNotifications.data?.length">
                    <div v-for="notification in dropdownNotifications" :key="`notification-${notification.id}`">
                        <RouterLink :to="{name:'detail-notifications',params:{
                                        notification:notification.id
                                    }
                        }" class="dropdown-item" :class="[notification.is_read?'':'unread']">

                            <h6 class="fw-bolder mb-0">
                                {{ notification.title }}
                            </h6>
                            <div v-html="truncateText(notification.content)" class="notification-content d-inline-block">
                            </div>
                            <br>
                            <small>{{ notification.time }}</small>
                        </RouterLink>
                        <hr class="dropdown-divider">
                    </div>
                    <RouterLink v-if="Notification.getNotifications.next_page_url||Notification.getNotifications.data.length>6" :to="{name:'notifications'}" class="dropdown-item text-center text-primary text-decoration-underline">
                        Voir plus
                    </RouterLink>
                </div>
                <div v-else class="text-center">
                    <h6>Aucun notification</h6>
                </div>
            </div>
        </div> 
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import { useNotification } from '@/stores/notification'
import { computed, onMounted } from 'vue';

const Notification=useNotification()

const dropdownNotifications = computed(() => {
    let data = []
    Notification.getNotifications.data.forEach((el, index) => {
        if(index<=6)
        data.push(el)
    })

    return data
})

const truncateText=(text)=>{
    if (text.length > 20) {
      return text.slice(0, 20) + '...';
    }
    return text;
  }

onMounted(() => {
    Notification.fetchNotifications()
})

</script>

<style scoped>
.notification-dropdown{
  width: 200px;
  max-height: 250px;
  overflow-y: scroll;
}

.unread{
    background-color: #d3cfcfab;
}

.notification-content{
    width: 100%;
    overflow: hidden;
}


@media (max-width:300px) {
    .notification-dropdown{
        width: 100%;
    }
}

</style>

