<template>
  <div ref="menuElement" class="relative">
    <a id="dropdownNotificationLink" class="!text-white rounded-full p-3 h-10 w-10 flex items-center justify-center bg-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click.prevent="toggleMenu">
      <Icon v-if="unreadNotificationsCount > 0" :icon="faBell" color="#407a8c" />
      <Icon v-else :icon="faBell" color="#9ca3af" />
      <span v-if="unreadNotificationsCount > 0" class="badge badge-pill badge-danger position-absolute" style="top:0; right:0;">
        {{ unreadNotificationsCountDisplay }}
      </span>
    </a>

    <div v-if="isOpen" class="absolute bg-white right-0 left-auto w-[25rem] max-h-64 overflow-auto p-1" aria-labelledby="dropdownNotificationLink">
      <p class="block px-0.5 py-1.5 mb-0 whitespace-nowrap text-black">Notifications</p>
      <div class="h-0 mx-0.5 my-0 overflow-hidden border-t border-solid border-[#e9ecef]" />
      <p v-if="notifications.length === 0" class="ml-4 text-sm leading-5 font-normal text-gray-500">You have no notifications yet</p>
      <div v-for="(notification, index) in notifications.slice(0, showCount)" :key="index" class="dropdown-item whitespace-normal px-1 focus:outline-none active:outline-none">
        <UserNotificationItem
          :title="notification.data.title ?? ''"
          :description="notification.data.description"
          :url="notification.data.url ?? null"
          :url-text="notification.data.url_text ?? null"
          :read-at="notification.read_at ?? ''"
          :created-at="notification.created_at ?? ''"
          :updated-at="notification.updated_at ?? ''"
          @mark-as-read="markAsRead(notification)"
        />
        <div v-if="index !== notifications.length - 1" class="dropdown-divider" />
      </div>
      <button v-if="notifications.length > showCount" class="w-full text-center py-2 text-blue-500 bg-white" @click.stop="showMore">Show More</button>
    </div>
  </div>
</template>

<script lang="ts" setup>
// import UserService from '../../services/UserService';
import { computed, ref } from 'vue'
import { faBell } from '@fortawesome/free-solid-svg-icons'
import UserNotificationItem from './UserNotificationItem.vue'
import { onClickOutside } from '@vueuse/core'

defineProps({
  userId: {
    type: String,
    default: '',
  },

  isOpen: {
    type: Boolean,
    default: true,
  },
})

const isOpen = ref(false)

const toggleMenu = () => isOpen.value = !isOpen.value

const menuElement = ref<HTMLElement>()

onClickOutside(menuElement, () => isOpen.value = false)

interface NotificationType {
  id: string
  read_at: string
  created_at: string
  updated_at: string
  data: {
    title: string
    description: string
    url: string
    url_text: string
  }
}

const notifications = ref<NotificationType[]>([])

const showCount = ref(10)

const unreadNotificationsCount = computed(() => notifications.value.filter(notification => !notification.read_at).length)

const unreadNotificationsCountDisplay = computed(() => unreadNotificationsCount.value > 9 ? '9+' : unreadNotificationsCount.value)

const showMore = () => {
  showCount.value += 5
}

// const formatDate = (date) => {
//   let d = new Date(date);
//   return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()}`;
// }

const markAsRead = (notification: NotificationType) => {
  if (!notification.read_at) {
    // UserService.markNotificationAsRead(notification.id)
    //   .then(() => {
    //     notification.read_at = new Date().toISOString();
    //   }).catch(error => {
    //     console.error('Fail marking notification as read:', error);
    //   });
  }
}
</script>

<style scoped>
.dropdown-item.active,
.dropdown-item:active {
  color: inherit;
  text-decoration: none;
  background-color: transparent;
}
</style>
