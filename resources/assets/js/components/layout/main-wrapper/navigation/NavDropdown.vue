<template>
  <div ref="menuElement" class="bg-transparent text-white relative">
    <a href="#" class="!text-white dropdown-toggle font-medium py-4 md:py-3 px-2 hover:underline block" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" @click.prevent="toggleMenu">
      <slot name="header" />
    </a>

    <div class="divide-y divide-brand-gray-50 !p-0 absolute bg-white z-50 rounded right-0 left-auto min-w-48 mt-[1px]" :class="{ hidden: !isOpen }">
      <slot name="options" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'

const isOpen = ref(false)

const toggleMenu = () => isOpen.value = !isOpen.value

const menuElement = ref<HTMLElement>()

onClickOutside(menuElement, () => isOpen.value = false)
</script>

<style>
  .dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: '';
  border-top: 0.3em solid;
  border-right: 0.3em solid transparent;
  border-bottom: 0;
  border-left: 0.3em solid transparent;
}
</style>
