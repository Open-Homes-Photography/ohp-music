<template>
  <div class="cursor-pointer flex" @click.stop="handleClick">
    <div class="flex items-start">
      <div class="flex items-center mr-2">
        <i
          class="fas fa-circle mr-2 text-[0.25rem]"
          :class="readAt ? 'unread-alert text-transparent' : 'unread-alert text-red-500'"
        />
        <i class="fas fa-party-horn text-blue-900 bg-blue-100 rounded-full p-2" />
      </div>
    </div>
    <div class="flex-grow">
      <div class="w-full flex items-center">
        <div class="flex-1">
          <p class="max-w-[15rem]" :class="{ 'font-weight-bold': !readAt, 'truncate': !expanded }">
            {{ title }}
          </p>
        </div>
        <div class="text-gray-400 whitespace-nowrap">
          {{ formatDate(createdAt) }}
        </div>
      </div>
      <div class="w-full">
        <p class="max-w-[19rem] text-gray-400" :class="{ truncate: !expanded }">
          {{ description }}
        </p>
        <div v-if="url">
          <a :href="url" target="_blank" class="text-sm leading-5 font-medium text-blue-500" @click.stop>
            {{ urlText ?? 'Learn More' }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    default: '',
  },
  url: {
    type: String,
    default: '',
  },
  urlText: {
    type: String,
    default: null,
  },
  readAt: {
    type: String,
    default: null,
  },
  createdAt: {
    type: String,
    default: null,
  },
  updatedAt: {
    type: String,
    default: null,
  },
})

const emit = defineEmits<{ (e: 'markAsRead'): void }>()

const expanded = ref(false)

const formatDate = date => {
  const d = new Date(date)
  return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()}`
}

const handleClick = () => {
  expanded.value = !expanded.value

  if (!props.readAt) {
    emit('markAsRead')
  }
}
</script>
