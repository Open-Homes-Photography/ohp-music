<template>
  <nav class="flex items-center gap-x-6 text-white bg-[#386878] md:h-16 py-2.5 px-0 md:px-[16px]">
    <LogoUserType user-type="cx" />

    <button
      type="button" class="navbar-toggler collapsed pt-2 md:pt-0" data-toggle="collapse"
      data-target="#app-navbar-collapse" aria-expanded="false"
    >
      <span class="navbar-toggler-icon" />
    </button>

    <div v-if="userAuthenticated" class="hidden items-center gap-x-6 md:flex flex-row grow divide-y divide-brand-gray-100 md:divide-none">
      <OrdersDropdown v-if="userAuthenticated" :is-admin="true" />
      <CustomersDropdown v-if="userAuthenticated" />
      <VisualArtistsDropdown v-if="userAuthenticated" />
      <ToolsDropdown v-if="userAuthenticated && isSuperAdmin" :is-admin="isAdmin" :is-super-admin="isSuperAdmin" />
    </div>

    <div class="flex flex-row justify-end md:gap-4 gap-2 items-center pt-2 pr-2 md:pt-0 md:pr-0">
      <UserNotification v-if="userAuthenticated" :user-id="userId" />
      <UserDropdown :user-authenticated="userAuthenticated" :user-name="userName" :user-id="userId" />
    </div>
  </nav>
</template>

<script lang="ts" setup>
// import * as AuthService from '../../services/AuthService';
import LogoUserType from './LogoUserType.vue'
import OrdersDropdown from './OrdersDropdown.vue'
import CustomersDropdown from './CustomersDropdown.vue'
import VisualArtistsDropdown from './VisualArtistsDropdown.vue'
import ToolsDropdown from './ToolsDropdown.vue'
import UserDropdown from './UserDropdown.vue'
import UserNotification from './UserNotification.vue'
import { computed } from 'vue'
import { useAuthorization } from '@/composables/useAuthorization'

const { isAdmin, currentUser } = useAuthorization()

const isSuperAdmin = computed(() => {
  return false
  // return this.$store.getters.isSuperAdminUser;
})

const userAuthenticated = computed(() => {
  return !!currentUser.value
})
const userId = computed(() => {
  return currentUser.value.id
})
const userName = computed(() => {
  return currentUser.value.name
})
</script>
