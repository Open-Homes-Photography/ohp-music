<template>
  <NavDropdown>
    <template #header>Customers</template>

    <template #options>
      <NavDropdownItem :target="customersListPath">
        <template #icon>
          <Icon :icon="faUsers" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Customers
      </NavDropdownItem>

      <NavDropdownItem :target="`${APP_ATRIUM_OPEN_HOMES_URL}/admin/brokerages`">
        <template #icon>
          <Icon :icon="faHouse" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Brokerages
      </NavDropdownItem>
    </template>
  </NavDropdown>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { faHouse, faUsers } from '@fortawesome/free-solid-svg-icons'
import NavDropdown from './NavDropdown.vue'
import NavDropdownItem from './NavDropdownItem.vue'

const APP_ATRIUM_OPEN_HOMES_URL = window.ATRIUM_APP_URL
const APP_MEDIA_OPEN_HOMES_URL = window.MEDIA_APP_URL
const APP_MEDIA_OPEN_HOMES_ADMIN_PATH = '/atrium/admin'

const customersListPath = computed(() => {
  return `${APP_MEDIA_OPEN_HOMES_URL}${APP_MEDIA_OPEN_HOMES_ADMIN_PATH}/customers`
})
</script>
