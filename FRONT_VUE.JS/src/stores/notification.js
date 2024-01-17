import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'

import axios from 'axios'

export const useNotification = defineStore('notification', () => {
    
    const Toasting=useToasting()

    const pendings=ref({})
    const notifications = ref({})
    const badge_notification=ref(0)

    const getNotifications = computed(() => notifications.value)
    const getBadgeNotification = computed(()=>badge_notification.value)
    const getPendings=computed(()=>pendings.value)

    const fetchNotifications=()=>{

        pendings.value.fetch_notification=true

        axios.get('/notifications')
        .then((response)=>{
            pendings.value.fetch_notification=false
            notifications.value = response.data.notification
            badge_notification.value=response.data.badge_notification
        })
        .catch((error)=>{
            pendings.value.fetch_notification=false
        })

    }

    const loadMoreNotification = () => {

        if (!notifications.value?.next_page_url)
            return

        pendings.value.fetch_more_notification=true

        axios.get(notifications.value.next_page_url)
            .then((response)=>{
                pendings.value.fetch_more_notification = false
                let data = notifications.value.data || []
                response.data.notification.data.forEach((el) => {
                    data.push(el)
                })
                notifications.value = response.data.notification
                notifications.value.data=data
                badge_notification.value=response.data.badge_notification
            })
            .catch((error)=>{
                pendings.value.fetch_more_notification=false
                Toasting.errorDefault('Notification',error)
            })
    }

    const readNotification = (notification) => {

        pendings.value.read_notification = true
        axios.post(`/notifications/read/${notification}`)
        .then((response)=>{
            pendings.value.read_notification=false
            notifications.value.data.forEach((el, index) => {
                if (el.id == notification) {
                    if(!el.is_read)
                        --badge_notification.value
                    notifications.value.data[index].is_read=1
                }
            })
        })
        .catch((error)=>{
            pendings.value.read_notification=false
            Toasting.errorDefault('Notification',error)
        })
        
    }

    const readAllNotification = () => {
        pendings.value.read_all_notification=true
        axios.post(`/notifications/read-all`)
        .then((response)=>{
            pendings.value.read_all_notification=false
            notifications.value.data.forEach((el, index) => {
                notifications.value.data[index].is_read=1
            })
            badge_notification.value=0
        })
        .catch((error)=>{
            pendings.value.read_all_notification=false
            Toasting.errorDefault('Notification',error)
        })
    }

    const deleteNotification = (notification) => {
        if (!confirm('voulez vous vraiment supprimer cette notification?'))
            return;
        
        pendings.value.delete_notification = {}
        pendings.value.delete_notification[notification]=true

        axios.delete(`/notifications/delete/${notification}`)
        .then((response)=>{
            pendings.value.delete_notification[notification]=false
            notifications.value.data.forEach((el, index) => {
                if (el.id == notification) {
                    if(!el.is_read)
                        --badge_notification.value
                    notifications.value.data.splice(index,1)
                }
            })
            Toasting.success('Suppression notification','suppression succès')
        })
        .catch((error) => {
            console.log(error)
            pendings.value.delete_notification[notification]=false
            Toasting.errorDefault('Suppression notification',error)
        })
    }

    return {
        getNotifications,
        getPendings,
        getBadgeNotification,
        fetchNotifications,
        readNotification,
        readAllNotification,
        loadMoreNotification,
        deleteNotification
    }

})
