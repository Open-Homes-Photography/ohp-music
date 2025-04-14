<template>
  <a class="hover:bg-[#b3c9d1] p-3.5 block font-medium !text-[#212529] first:rounded-t last:rounded-b w-full" :href="target" target="_blank">
    <slot name="icon" /> <slot />
  </a>
</template>

<script lang="ts" setup>
defineProps({
  target: {
    type: String,
    default: '#',
  },
})
</script>
