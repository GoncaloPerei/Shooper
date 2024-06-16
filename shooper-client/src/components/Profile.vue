<script setup>
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/store/auth'
const authStore = useAuthStore()
</script>

<template>
  <div class="flex items-center gap-3.5">
    <span v-if="authStore.user" class="font-montserrat text-white text-right font-bold">
      {{ authStore.user?.name }}<br /><span class="text-sm font-normal tracking-wider">{{
        authStore.user?.role?.name
      }}</span></span
    >
    <router-link
      to="/auth/signin"
      replace
      class="justify-center rounded-full bg-transparent p-3.5 border border-white"
      v-if="!authStore.status"
    >
      <b-icon-person-fill class="text-white text-xl max-md:text-lg" />
    </router-link>
    <router-link
      :to="'/user/profile/' + authStore?.user?.id + '/' + authStore?.user?.name"
      replace
      class="justify-center rounded-full bg-transparent p-3.5 border border-white"
      v-else
    >
      <b-icon-person-fill class="text-white text-xl max-md:text-lg" />
    </router-link>
  </div>
</template>
