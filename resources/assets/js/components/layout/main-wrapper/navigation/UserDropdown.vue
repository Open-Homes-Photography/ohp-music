<template>
  <NavDropdown v-if="userAuthenticated">
    <template #header>{{ userName }}</template>

    <template #options>
      <NavDropdownItem target="#">
        <template #icon>
          <Icon :icon="faPen" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Edit Profile
      </NavDropdownItem>

      <NavDropdownItem target="#" @click.prevent="logout">
        <template #icon>
          <Icon :icon="faRightToBracket" color="#5d8d9d" class="mr-1.5" fixed-width />
        </template>
        Log Out
      </NavDropdownItem>
    </template>
  </NavDropdown>
  <a v-else class="nav-link" href="/login">Login</a>
</template>

<script lang="ts" setup>
import { faPen, faRightToBracket } from '@fortawesome/free-solid-svg-icons'
import NavDropdown from './NavDropdown.vue'
import NavDropdownItem from './NavDropdownItem.vue'
import { eventBus } from '@/utils/eventBus'

defineProps({
  userAuthenticated: {
    type: Boolean,
    default: false,
  },

  userName: {
    type: String,
    default: '',
  },

  userId: {
    type: String,
    default: '',
  },

})

const logout = async () => {
  eventBus.emit('LOG_OUT')
}
</script>
