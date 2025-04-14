<template>
  <button
    type="button"
    class="flex items-center gap-2 focus:outline-none"
    :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': disabled }"
    :disabled="disabled"
    @click.stop="emit('click')"
  >
    <span v-if="loading" class="flex-1 flex justify-center items-center">
      <Spinner :class="loadingSize" />
    </span>

    <span v-if="confirmed" class="flex-1 flex justify-center items-center">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 448 512"
        :class="loadingSize"
      >
        <path
          fill="currentColor"
          d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
        />
      </svg>
    </span>

    <slot />
  </button>
</template>

<script lang="ts" setup>
import Spinner from './Spinner.vue'

defineProps({
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  loadingColor: { type: String, default: 'text-white' },
  loadingSize: { type: String, default: 'h-3 w-3' },
  confirmed: { type: Boolean, default: false },
})

const emit = defineEmits<{ (e: 'click'): void }>()
</script>
