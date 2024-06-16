<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useProductsStore } from '@/store/useProductsCrud'
import { useSearchHistoryStore } from '@/store/useSearchHistoryCrud'
import { useAuthStore } from '@/store/auth'
import Alerts from '@/components/Alerts.vue'

const authStore = useAuthStore()
const productsStore = useProductsStore()
const searchHistoryStore = useSearchHistoryStore()
const router = useRouter()

const alerts = ref({})
const open = ref(false)

const closeDropdown = () => {
  try {
    open.value = false
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
}

const handleInput = async () => {
  if (searchHistoryStore?.search) {
    try {
      productsStore.$patch((state) => {
        state.params.filter.name = searchHistoryStore?.search
      })
      await productsStore.getActiveContent()
      open.value = true
    } catch (error) {
      alerts.value.error('An error ocurred!', error)
    }
  } else {
    try {
      open.value = false
      productsStore.activeProducts = []
    } catch (error) {
      alerts.value.error('An error ocurred!', error)
    }
  }
}

const handleSubmit = async (search) => {
  if (search) {
    searchHistoryStore.search = search
  }
  try {
    if (authStore.authStatus) {
      await searchHistoryStore.postContent()
    } else {
      const existingHistory = JSON.parse(localStorage.getItem('SearchHistory')) || []

      const updatedHistory = [...existingHistory, searchHistoryStore.search]

      localStorage.setItem('SearchHistory', JSON.stringify(updatedHistory))
    }
    getHistory()
    open.value = false
    router.push({
      path: `/listing/${searchHistoryStore.search}`
    })
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
}

const clearHistory = async () => {
  try {
    if (authStore.authStatus) {
      searchHistoryStore.deleteContent()
    } else {
      localStorage.clear('SearchHistory')
    }
    searchHistoryStore.searchHistory = []
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
}

const removeHistory = async (id) => {
  try {
    if (authStore.authStatus) {
      searchHistoryStore.deleteContent(id)
    } else {
      searchHistoryStore.searchHistory.splice(id, 1)
      const updatedSearchHistory = searchHistoryStore.searchHistory.map((item) => item.search)
      localStorage.setItem('SearchHistory', JSON.stringify(updatedSearchHistory))
    }
    await getHistory()
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
}

const getHistory = async () => {
  try {
    await authStore.getUser()
  } catch (error) {
    console.log(error)
  }
  searchHistoryStore.searchHistory = []
  try {
    if (authStore.authStatus) {
      authStore?.user?.searchHistory.forEach((search) => {
        searchHistoryStore.searchHistory.push({ search: search?.search_term, id: search?.id })
      })
    } else {
      const searchHistory = JSON.parse(localStorage.getItem('SearchHistory'))
      searchHistory.map(function (search, id) {
        searchHistoryStore.searchHistory.push({ search, id })
      })
    }
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  getHistory()
})
</script>

<template>
  <Alerts ref="alerts" />
  <div
    v-click-outside="closeDropdown"
    class="relative max-w-2/4 w-2/4 h-auto max-sm:hidden border border-white rounded-xl flex items-center bg-[#242424] z-[990]"
  >
    <input
      id="search"
      name="search"
      type="text"
      placeholder="Search a product..."
      v-model="searchHistoryStore.search"
      @input="handleInput()"
      @keyup.enter="handleSubmit()"
      class="w-full leading-auto bg-transparent font-montserrat max-md:text-sm text-[#FFFFFF] px-5 outline-none tracking-wider"
      autocomplete="off"
    />
    <button
      @click="handleSubmit()"
      class="p-3.5 bg-[#F36F3F] hover:bg-[#F36F3F]/80 transition-colors rounded-xl"
    >
      <b-icon-search class="text-xl max-md:text-sm text-[#1e1e1e]" />
    </button>
    <div
      class="w-full h-auto rounded-xl absolute top-full border border-white bg-[#242424] my-1.5"
      v-show="open"
    >
      <p class="font-bold p-5 pb-2.5 text-white tracking-wider">Research Suggestions</p>
      <RouterLink
        :to="'/product/' + product?.id + '/' + product?.name"
        @click="open = false"
        v-for="product in productsStore?.activeContent?.data?.slice(0, 4)"
        class="w-full h-auto text-white text-sm p-5 flex items-center gap-3.5"
      >
        <img :src="product.filename" class="w-12 rounded-lg" />
        {{ product.name }}
      </RouterLink>
      <p v-if="productsStore?.activeContent?.productCount === 0" class="text-sm p-5 pb-2.5 text-white">
        Nothing in suggestions...
      </p>
      <div class="w-full p-5 pb-2.5 h-auto flex justify-between">
        <p class="font-bold text-white tracking-wider">Search History</p>
        <!-- <button
          @click="clearHistory()"
          class="underline underline-offset-2 text-sm tracking-wider text-white"
        >
          Clear All...
        </button> -->
      </div>
      <div class="max-h-60 overflow-y-auto overflow-x-hidden">
        <div
          v-for="search in searchHistoryStore?.searchHistory?.slice()?.reverse()"
          class="w-full h-auto p-5 flex items-center gap-3.5"
        >
          <button
            @click="handleSubmit(search?.search)"
            class="w-full h-auto text-white text-sm flex items-center gap-3.5"
          >
            <b-icon-arrow-repeat class="text-lg" />{{ search?.search }}
          </button>
          <button
            @click="removeHistory(search?.id)"
            class="text-white text-sm underline underline-offset-2"
          >
            Remove
          </button>
        </div>
      </div>
      <p
        v-if="searchHistoryStore?.searchHistory?.length <= 0"
        class="text-sm p-5 pb-2.5 text-white"
      >
        Nothing in search history...
      </p>
      <button
        @click="handleSubmit()"
        class="w-full h-auto text-white text-sm p-5 flex items-center gap-3.5 underline underline-offset-4"
      >
        See all search results for "{{ searchHistoryStore.search }}"
      </button>
    </div>
  </div>
</template>
