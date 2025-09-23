<template>
  <ScreenBase>
    <template #header>
      <ScreenHeader layout="collapsed">Keywords</ScreenHeader>
    </template>

    <ScreenEmptyState v-if="libraryEmpty">
      <template #icon>
        <GuitarIcon size="96" />
      </template>
      No keywords found.
      <span class="secondary d-block">
        {{ isAdmin ? 'Have you set up your library yet?' : 'Contact your administrator to set up your library.' }}
      </span>
    </ScreenEmptyState>

    <template v-else>
      <ul v-if="keywords" class="keywords text-center">
        <li
          v-for="keyword in keywords"
          :key="keyword.name"
          :class="`level-${getLevel(keyword)}`"
          class="rounded-[0.5em] inline-block m-1.5 align-middle overflow-hidden"
        >
          <a
            :href="`/#/keywords/${encodeURIComponent(keyword.name)}`"
            :title="`${keyword.name}: ${pluralize(keyword.song_count, 'song')}`"
            class="bg-white/15 inline-flex items-center justify-center !text-k-text-secondary
          transition-colors duration-200 ease-in-out hover:!text-k-text-primary hover:bg-k-highlight"
          >
            <span class="name bg-white/5 px-[0.5em] py-[0.2em] leading-normal">{{ keyword.name }}</span>
            <span class="count items-center px-[0.5em] py-[0.2em]">
              {{ keyword.song_count }}
            </span>
          </a>
        </li>
      </ul>
      <ul v-else class="text-center">
        <li v-for="i in 20" :key="i" class="inline-block">
          <KeywordItemSkeleton />
        </li>
      </ul>
    </template>
  </ScreenBase>
</template>

<script lang="ts" setup>
import { GuitarIcon } from 'lucide-vue-next'
import { maxBy, minBy } from 'lodash'
import { computed, onMounted, ref } from 'vue'
import { commonStore } from '@/stores/commonStore'
import { keywordStore } from '@/stores/keywordStore'
import { pluralize } from '@/utils/formatters'
import { useAuthorization } from '@/composables/useAuthorization'
import { useErrorHandler } from '@/composables/useErrorHandler'

import ScreenHeader from '@/components/ui/ScreenHeader.vue'
import KeywordItemSkeleton from '@/components/ui/skeletons/KeywordItemSkeleton.vue'
import ScreenEmptyState from '@/components/ui/ScreenEmptyState.vue'
import ScreenBase from '@/components/screens/ScreenBase.vue'

const { isAdmin } = useAuthorization()
const { handleHttpError } = useErrorHandler()

const keywords = ref<Keyword[]>()

const libraryEmpty = computed(() => commonStore.state.song_length === 0)
const mostPopular = computed(() => maxBy(keywords.value, 'song_count'))
const leastPopular = computed(() => minBy(keywords.value, 'song_count'))

const levels = computed(() => {
  const max = mostPopular.value?.song_count || 1
  const min = leastPopular.value?.song_count || 1
  const range = max - min
  const step = range / 5

  return [min, min + step, min + step * 2, min + step * 3, min + step * 4, max]
})

const getLevel = (keyword: Keyword) => {
  const index = levels.value.findIndex(level => keyword.song_count <= level)
  return index === -1 ? 5 : index
}

const fetchKeywords = async () => {
  try {
    keywords.value = await keywordStore.fetchAll()
  } catch (error: unknown) {
    handleHttpError(error)
  }
}

onMounted(async () => {
  if (libraryEmpty.value) {
    return
  }
  await fetchKeywords()
})
</script>

<style lang="postcss" scoped>
.keywords {
  li {
    font-size: var(--unit);

    &:active {
      @apply scale-95;
    }
  }

  .level-0 {
    --unit: 1rem;
    @apply opacity-80;
  }

  .level-1 {
    --unit: 1.4rem;
    @apply opacity-[84%];
  }

  .level-2 {
    --unit: 1.8rem;
    @apply opacity-[88%];
  }

  .level-3 {
    --unit: 2.2rem;
    @apply opacity-[92%];
  }

  .level-4 {
    --unit: 2.6rem;
    @apply opacity-[96%];
  }

  .level-5 {
    --unit: 3rem;
    @apply opacity-100;
  }
}
</style>
