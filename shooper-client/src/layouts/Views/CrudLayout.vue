<script setup>
import CrudHeader from '@/components/CrudHeader.vue'
defineProps({
  iconName: { type: String, required: true }
})
</script>

<template>
  <div class="w-auto h-auto flex flex-col gap-10 m-16 text-white font-montserrat">
    <CrudHeader :iconName="iconName"><slot name="header" /></CrudHeader>
    <main class="w-auto mx-32 flex flex-col gap-12">
      <slot name="main" />
    </main>
  </div>
</template>
