<script setup>
import { useAuthStore } from '@/store/auth'
import CrudStatusLabel from '@/components/CrudStatusLabel.vue'
import Navlink from '@/components/Navlink.vue'
import { twMerge } from 'tailwind-merge'

import { useRoute, useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

const store = useAuthStore()

const signOut = async () => {
  await store.signOut()
  router.push('/')
}
</script>

<template>
  <div
    class="relative w-full min-h-screen h-auto flex gap-12 px-16 py-16 text-white font-montserrat items-stretch"
  >
    <div class="sticky top-24 w-4/12 h-fit flex flex-col gap-8 self-start">
      <span class="text-2xl font-bold text-nowrap"
        >Hello, {{ store.user.name }}<br /><button
          type="button"
          class="text-base font-normal tracking-wider group relative w-auto cursor-pointer py-3 overflow-hidden text-nowrap text-center transition-colors duration-300"
          @click="signOut"
        >
          Sign Out
          <span
            aria-hidden="true"
            class="absolute bottom-0 inset-x-0 z-[99] h-1 -translate-x-full bg-[#F36F3F] transition-transform duration-300 group-hover:translate-x-0"
          ></span></button
      ></span>
      <div class="w-full h-auto bg-[#242424] p-8 flex flex-col gap-5 rounded-lg">
        <CrudStatusLabel :class="twMerge('text-xl tracking-normal')">Account Panel</CrudStatusLabel>
        <ul>
          <li>
            <Navlink
              :class="twMerge('hover:scale-100')"
              iconName="b-icon-database-fill"
              :link="'/user/profile/' + store?.user?.id + '/' + store?.user?.name + '/'"
              ><template #name>Personal Data</template></Navlink
            >
          </li>
          <li>
            <Navlink
              :class="twMerge('hover:scale-100 text-lg')"
              iconName="b-icon-list-ul"
              :link="'/user/profile/' + store?.user?.id + '/' + store?.user?.name + '/lists'"
              ><template #name>Lists</template></Navlink
            >
          </li>
          <li>
            <Navlink
              :class="twMerge('hover:scale-100')"
              iconName="b-icon-stopwatch-fill"
              :link="'/user/profile/' + store?.user?.id + '/' + store?.user?.name + '/watchlist'"
              ><template #name>Watchlist</template></Navlink
            >
          </li>
        </ul>
      </div>
    </div>
    <div class="w-full h-auto flex flex-col gap-8">
      <CrudStatusLabel>{{ route.name }}</CrudStatusLabel>
      <RouterView />
    </div>
  </div>
</template>
