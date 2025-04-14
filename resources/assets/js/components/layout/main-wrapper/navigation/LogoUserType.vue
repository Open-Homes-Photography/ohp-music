<template>
  <div class="flex space-x-4 items-center">
    <a href="#/home" class="">
      <img src="@/../img/logo-oh.png" alt="Open Homes logo" class="h-[26px]">
    </a>

    <div
      class="rounded-md px-3.5 py-[2px] text-[12px] font-extrabold tracking-widest
        bg-[#305866] text-blue-200"
      v-bind="{ ...useAttrs() }"
    >
      {{ userLegend }}
    </div>
  </div>
</template>

<script>
import { useAttrs } from 'vue'

export default {
  name: 'LogoUserType',
  inheritAttrs: false,
  props: {
    userType: { type: String, default: 'agent' },
  },
  computed: {
    homePath () {
      // TODO: update target
      return this.userType === 'agent' ? '/dashboard' : '/admin/dashboard'
    },
    userLegend () {
      // TODO: update target
      return this.userType === 'agent' ? 'Atrium' : 'CX'
    },
  },
  methods: { useAttrs },
}
</script>

<style scoped>

</style>
