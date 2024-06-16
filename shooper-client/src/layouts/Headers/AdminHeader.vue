<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import Logo from '@/components/Logo.vue'
import Navbar from '@/components/Navbar.vue'
import Profile from '@/components/Profile.vue'
import Navlink from '@/components/Navlink.vue'
import { useAuthStore } from '@/store/auth'

const store = useAuthStore()

const open = ref(false)
const setOpen = () => {
  open.value = !open.value
}
</script>

<template>
  <header
    class="w-full sticky top-0 z-[98] flex items-center justify-between max-h-20 h-max p-10 max-md:py-8 max-sm:py-4 bg-[#242424] border-b border-white/20 backdrop-filter backdrop-blur-lg bg-opacity-30"
  >
    <div class="flex gap-5 items-center">
      <b-icon-list
        @click="setOpen"
        class="text-white text-3xl max-md:text-2xl max-sm:text-xl cursor-pointer"
      />
      <router-link to="/admin">
        <Logo />
      </router-link>
    </div>
    <Profile />
  </header>
  <Navbar :setOpen="setOpen" :open="open">
    <hr class="h-px bg-white/40 border-0" />
    <div class="">
      <Navlink @click="setOpen" iconName="b-icon-house-fill" link="/admin"
        ><template #name>Dashboard</template></Navlink
      >
    </div>
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink @click="setOpen" iconName="b-icon-people-fill" link="/admin/user"
        ><template #name>Users</template></Navlink
      >
      <Navlink @click="setOpen" iconName="b-icon-tags-fill" link="/admin/brand"
        ><template #name>Brands</template></Navlink
      >
      <Navlink @click="setOpen" iconName="b-icon-box-fill" link="/admin/product"
        ><template #name>Products</template></Navlink
      >
      <Navlink @click="setOpen" iconName="b-icon-link" link="/admin/product/url"
        ><template #name>Product Url's</template></Navlink
      >
      <Navlink @click="setOpen" iconName="b-icon-grid-fill" link="/admin/category"
        ><template #name>Categories</template></Navlink
      >
      <Navlink @click="setOpen" iconName="b-icon-shop-window" link="/admin/store"
        ><template #name>Stores</template></Navlink
      >
    </div>
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink @click="store.signOut" iconName="b-icon-box-arrow-right" link="/"
        ><template #name>Sign Out</template></Navlink
      >
    </div>
    <hr class="h-px bg-white/40 border-0" />
    <div>
      <Navlink iconName="b-icon-person-fill-lock" link="/"
        ><template #name>Client</template></Navlink
      >
    </div>
  </Navbar>
</template>
