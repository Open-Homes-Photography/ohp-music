<template>
  <ScreenBase>
    <template #header>
      <ScreenHeader v-if="keyword" :layout="headerLayout">
        Keyword: <span class="text-thin">{{ decodeURIComponent(name!) }}</span>
        <ControlsToggle v-if="songs.length" v-model="showingControls" />

        <template #thumbnail>
          <ThumbnailStack :thumbnails="thumbnails" />
        </template>

        <template v-if="keyword" #meta>
          <span>{{ pluralize(keyword.song_count, 'song') }}</span>
          <span>{{ duration }}</span>
        </template>

        <template #controls>
          <SongListControls
            v-if="!isPhone || showingControls"
            :config="config"
            @play-all="playAll"
            @play-selected="playSelected"
          />
        </template>
      </ScreenHeader>
      <ScreenHeaderSkeleton v-else />
    </template>

    <SongListSkeleton v-if="showSkeletons" class="-m-6" />
    <SongList
      v-else
      ref="songList"
      class="-m-6"
      @sort="fetchWithSort"
      @press:enter="onPressEnter"
      @scroll-breakpoint="onScrollBreakpoint"
      @scrolled-to-end="fetch"
    />

    <ScreenEmptyState v-if="!songs.length && !loading">
      <template #icon>
        <GuitarIcon size="96" />
      </template>

      No songs in this keyword.
    </ScreenEmptyState>
  </ScreenBase>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import { GuitarIcon } from 'lucide-vue-next'
import { pluralize, secondsToHumanReadable } from '@/utils/formatters'
import { eventBus } from '@/utils/eventBus'
import { playbackService } from '@/services/playbackService'
import { keywordStore } from '@/stores/keywordStore'
import { songStore } from '@/stores/songStore'
import { useRouter } from '@/composables/useRouter'
import { useErrorHandler } from '@/composables/useErrorHandler'
import { useSongList } from '@/composables/useSongList'
import { useSongListControls } from '@/composables/useSongListControls'

import ScreenHeader from '@/components/ui/ScreenHeader.vue'
import ScreenEmptyState from '@/components/ui/ScreenEmptyState.vue'
import SongListSkeleton from '@/components/ui/skeletons/SongListSkeleton.vue'
import ScreenHeaderSkeleton from '@/components/ui/skeletons/ScreenHeaderSkeleton.vue'
import ScreenBase from '@/components/screens/ScreenBase.vue'

const {
  SongList,
  ControlsToggle,
  ThumbnailStack,
  headerLayout,
  songs,
  songList,
  thumbnails,
  showingControls,
  isPhone,
  onPressEnter,
  playSelected,
  onScrollBreakpoint,
} = useSongList(ref<Song[]>([]), { type: 'Keyword' }, { sortable: true, filterable: false })

const { SongListControls, config } = useSongListControls('Keyword')

const { getRouteParam, go, onRouteChanged } = useRouter()

let sortField: MaybeArray<PlayableListSortField> = 'title'
let sortOrder: SortOrder = 'asc'

const randomSongCount = 500
const name = ref<string | null>(null)
const keyword = ref<Keyword | null>(null)
const loading = ref(false)
const page = ref<number | null>(1)

const moreSongsAvailable = computed(() => page.value !== null)
const showSkeletons = computed(() => loading.value && songs.value.length === 0)
const duration = computed(() => secondsToHumanReadable(keyword.value?.length ?? 0))

const fetch = async () => {
  if (!moreSongsAvailable.value || loading.value) {
    return
  }

  loading.value = true

  try {
    let fetched: { songs: Playable[], nextPage: number | null }

    [keyword.value, fetched] = await Promise.all([
      keywordStore.fetchOne(name.value!),
      songStore.paginateForKeyword(name.value!, {
        sort: sortField,
        order: sortOrder,
        page: page.value!,
      }),
    ])

    page.value = fetched.nextPage
    songs.value.push(...fetched.songs)
  } catch (error: unknown) {
    useErrorHandler('dialog').handleHttpError(error)
  } finally {
    loading.value = false
  }
}

const refresh = async () => {
  keyword.value = null
  page.value = 1
  songs.value = []

  await fetch()
}

const fetchWithSort = async (field: MaybeArray<PlayableListSortField>, order: SortOrder) => {
  page.value = 1
  songs.value = []
  sortField = field
  sortOrder = order

  await fetch()
}

const getNameFromRoute = () => getRouteParam('name') ?? null

onRouteChanged(route => {
  if (route.screen !== 'Keyword') {
    return
  }
  name.value = getNameFromRoute()
})

const playAll = async () => {
  if (!keyword.value) {
    return
  }

  // we ignore the queueAndPlay's await to avoid blocking the UI
  if (keyword.value!.song_count <= randomSongCount) {
    playbackService.queueAndPlay(songs.value, true)
  } else {
    playbackService.queueAndPlay(await songStore.fetchRandomForKeyword(keyword.value!, randomSongCount))
  }

  go('queue')
}

onMounted(() => (name.value = getNameFromRoute()))

watch(name, async () => name.value && await refresh())

// We can't really tell how/if the keywords have been updated, so we just refresh the list
eventBus.on('SONGS_UPDATED', async () => keyword.value && await refresh())
</script>
