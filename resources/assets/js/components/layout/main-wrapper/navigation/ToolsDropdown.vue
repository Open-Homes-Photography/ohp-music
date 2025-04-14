<template>
  <NavDropdown>
    <template #header>Tools</template>

    <template #options>
      <NavDropdownItem v-if="isAdmin" :target="servicesCatalogPath">
        <template #icon>
          <Icon :icon="faList" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Services Catalog
      </NavDropdownItem>

      <NavDropdownItem v-if="isAdmin" :target="promoCodesPath">
        <template #icon>
          <Icon :icon="faTicket" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Promo Codes
      </NavDropdownItem>

      <NavDropdownItem :target="manageGalleriesPath">
        <template #icon>
          <Icon :icon="faPhotoFilm" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Gallery Manager
      </NavDropdownItem>

      <NavDropdownItem v-if="isAdmin" target="#" @click.prevent="goToOldDashboard">
        <template #icon>
          <Icon :icon="faClockRotateLeft" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Old Dashboard
      </NavDropdownItem>

      <NavDropdownItem v-if="isAdmin" target="#">
        <button
          class="dropdown-item hover:bg-brand-blue-light-3 p-3"
          :disabled="creatingInvoices"
          :class="{ 'cursor-not-allowed': creatingInvoices }"
          @click.prevent="triggerInvoicingProcess"
        >
          <Icon :icon="faFileInvoiceDollar" color="#5d8d9d" class="mr-1.5" fixed-width />
          Create Invoices
          <Icon :icon="faSpinner" color="#5d8d9d" class="mr-1.5" fixed-width />
        </button>
      </NavDropdownItem>
    </template>
  </NavDropdown>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { faClockRotateLeft, faFileInvoiceDollar, faList, faPhotoFilm, faSpinner, faTicket } from '@fortawesome/free-solid-svg-icons'
import NavDropdown from './NavDropdown.vue'
import NavDropdownItem from './NavDropdownItem.vue'

defineProps({
  isAdmin: { type: Boolean, default: false },
  isSuperAdmin: { type: Boolean, default: false },
})
// TODO: fix target
// import {
// APP_MEDIA_OPEN_HOMES_ADMIN_PATH,
// APP_MEDIA_OPEN_HOMES_VA_PATH,
// // OLD_DASHBOARD_URL,
// APP_MUSIC_OPEN_HOMES_URL,
// APP_MEDIA_OPEN_HOMES_URL,
// } from "../../config";
// import WebsiteService from "../../services/WebsiteService";
// import InvoiceService from "../../services/InvoiceService";

const APP_MEDIA_OPEN_HOMES_ADMIN_PATH = ''
const APP_MEDIA_OPEN_HOMES_VA_PATH = ''
// const APP_MUSIC_OPEN_HOMES_URL = ''
const APP_MEDIA_OPEN_HOMES_URL = ''

const creatingInvoices = ref(false)

const manageGalleriesPath = computed(() => {
  return `${APP_MEDIA_OPEN_HOMES_URL}/galleries`
})
const promoCodesPath = computed(() => {
  return `${APP_MEDIA_OPEN_HOMES_URL}${APP_MEDIA_OPEN_HOMES_ADMIN_PATH}/promocodes`
})
const servicesCatalogPath = computed(() => {
  return `${APP_MEDIA_OPEN_HOMES_URL}${APP_MEDIA_OPEN_HOMES_VA_PATH}/services-catalog`
})

// const musicAppPath = computed(() => {
//   return `${APP_MUSIC_OPEN_HOMES_URL}`
// })
//
const goToOldDashboard = () => {
  // console.log('redirect to dashboard')
  // WebsiteService.getLoginAsAdminToken()
//   .then(response => window.open(OLD_DASHBOARD_URL + '/authenticate/mvp/?t=' + response.token, '_blank'))
//   .catch(error => console.log(error.message));
}

const triggerInvoicingProcess = () => {
  // console.log('trigger invoicing')
  // this.creatingInvoices = true;
// InvoiceService.triggerInvoicingProcess()
//   .then(response => {
//     if (response.url != null) {
//       this.creatingInvoices = false;
//       window.open(response.url, '_blank');
//     } else {
//       if (response.code === 0) {
//         this.$success('Success', response.message);
//       } else {
//         this.$warning('Nothing to process', response.message);
//       }
//     }
//     this.creatingInvoices = false;
//   }).catch(error => {
//     this.creatingInvoices = false;
//     this.$error('Error', error.message);
// });
}
</script>
