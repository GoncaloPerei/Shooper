<script setup>
import Logo from '@/components/Logo.vue'
import Profile from '@/components/Profile.vue'
import NavBar from '@/components/Navbar.vue'
import { RouterLink } from 'vue-router'
import { ref, onMounted } from 'vue'
import Navlink from '@/components/Navlink.vue'
import { useCategoriesStore } from '@/store/useCategoriesCrud'
import { useAuthStore } from '@/store/auth'
import Alerts from '@/components/Alerts.vue'
import SearchAuto from '@/components/SearchAuto.vue'

const store = useCategoriesStore()
const authStore = useAuthStore()

const alerts = ref({})

const open = ref(false)
const setOpen = () => {
  open.value = !open.value
}

onMounted(async () => {
  try {
    await Promise.all([store.$reset(), store.getActiveContent()])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <header
    class="w-full sticky top-0 left-0 z-[990] flex items-center justify-between justify-self-start max-h-20 h-max p-10 max-md:py-8 max-sm:py-4 bg-[#242424] border-b border-white/20 backdrop-filter backdrop-blur-lg bg-opacity-30"
  >
    <div class="flex gap-5 items-center">
      <b-icon-list
        @click="setOpen"
        class="text-white text-3xl max-md:text-2xl max-sm:text-xl cursor-pointer"
      />
      <router-link to="/">
        <Logo />
      </router-link>
    </div>
    <SearchAuto />
    <Profile />
  </header>
  <NavBar :setOpen="setOpen" :open="open">
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink
        v-for="data in store.activeContent?.data"
        :key="data.id"
        @click="setOpen"
        :link="'/listing/' + data?.id + '/' + data?.name"
        ><template #img
          ><img
            :src="data?.filename"
            alt="Illustration of an oven."
            class="w-10 max-w-72" /></template
        ><template #name>{{ data.name }}</template></Navlink
      >
    </div>
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink
        iconName="b-icon-suit-heart-fill"
        @click="setOpen"
        :link="'/user/profile/' + authStore?.user?.id + '/' + authStore?.user?.name + '/favorites'"
        ><template #name>Favourites</template></Navlink
      >
      <Navlink
        iconName="b-icon-stopwatch-fill"
        @click="setOpen"
        :link="'/user/profile/' + authStore?.user?.id + '/' + authStore?.user?.name + '/watchlist'"
        ><template #name>Watchlist</template></Navlink
      >
    </div>
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink iconName="b-icon-person-fill-lock" @click="setOpen" link="/admin"
        ><template #name>Administrator</template></Navlink
      >
    </div>
  </NavBar>
</template>
