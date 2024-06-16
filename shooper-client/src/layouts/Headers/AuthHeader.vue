<script setup>
import Logo from '@/components/Logo.vue'
import { RouterLink } from 'vue-router'
</script>

<template>
  <div
    class="w-full fixed top-0 flex items-center justify-center max-h-20 h-max p-10 max-md:py-8 max-sm:py-4 bg-[#1E1E1E] border-b border-white/20 backdrop-filter backdrop-blur-lg bg-opacity-30"
  >
  <router-link to="/">
    <Logo />
  </router-link>
  </div>
</template>
