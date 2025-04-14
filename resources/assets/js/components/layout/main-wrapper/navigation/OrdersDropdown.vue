<template>
  <NavDropdown>
    <template #header>Orders</template>

    <template #options>
      <NavDropdownItem :target="APP_ATRIUM_OPEN_HOMES_URL">
        <template #icon>
          <Icon :icon="faClipboardList" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        All Orders
      </NavDropdownItem>

      <NavDropdownItem :target="`${APP_ATRIUM_OPEN_HOMES_URL}/admin/quote`">
        <template #icon>
          <Icon :icon="faFile" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Create Quote
      </NavDropdownItem>

      <NavDropdownItem :target="`${APP_ATRIUM_OPEN_HOMES_URL}/admin/request`">
        <template #icon>
          <Icon :icon="faCartPlus" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Create Order
      </NavDropdownItem>
    </template>
  </NavDropdown>
</template>

<script lang="ts" setup>
import { faCartPlus, faClipboardList, faFile } from '@fortawesome/free-solid-svg-icons'
import NavDropdown from './NavDropdown.vue'
import NavDropdownItem from './NavDropdownItem.vue'

defineProps({
  isAgent: { type: Boolean, default: false },
  isAdmin: { type: Boolean, default: false },
})

const APP_ATRIUM_OPEN_HOMES_URL = window.ATRIUM_APP_URL
</script>
