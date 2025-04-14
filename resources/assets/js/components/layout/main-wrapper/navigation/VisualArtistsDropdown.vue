<template>
  <NavDropdown>
    <template #header>Visual Artists</template>

    <template #options>
      <NavDropdownItem :target="`${APP_ATRIUM_OPEN_HOMES_URL}/va-admin/users`">
        <template #icon>
          <Icon :icon="faCamera" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Visual Artists
      </NavDropdownItem>

      <NavDropdownItem :target="`${APP_ATRIUM_OPEN_HOMES_URL}/va/dashboard`">
        <template #icon>
          <Icon :icon="faUpLong" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Uploads
      </NavDropdownItem>

      <NavDropdownItem v-if="false" :target="vaBillsPath">
        <template #icon>
          <Icon :icon="faFileInvoiceDollar" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Bills
      </NavDropdownItem>
    </template>
  </NavDropdown>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { faCamera, faFileInvoiceDollar, faUpLong } from '@fortawesome/free-solid-svg-icons'
import NavDropdown from './NavDropdown.vue'
import NavDropdownItem from './NavDropdownItem.vue'

const APP_ATRIUM_OPEN_HOMES_URL = window.ATRIUM_APP_URL
const APP_MEDIA_OPEN_HOMES_URL = window.MEDIA_APP_URL
const APP_MEDIA_OPEN_HOMES_VA_PATH = '/atrium/va'

const vaBillsPath = computed(() => {
  return `${APP_MEDIA_OPEN_HOMES_URL}${APP_MEDIA_OPEN_HOMES_VA_PATH}/bills/all`
})
</script>
