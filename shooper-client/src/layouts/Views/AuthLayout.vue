<template>
  <div>
    <Header />
    <slot />
    <Footer />
  </div>
</template>

<script setup>
import Header from '@/layouts/Headers/AuthHeader.vue'
import Footer from '@/layouts/Footers/Footer.vue'
</script>
