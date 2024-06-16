<script setup>
defineProps({
  iconName: { type: String, required: true }
})
</script>

<template>
  <header class="flex gap-2.5 items-center">
    <div class="p-3.5 bg-[rgba(243,111,63,1)] rounded-full">
      <component :is="iconName" class="text-2xl text-[#242424]" />
    </div>
    <span class="w-fit p-2 text-3xl font-bold"><slot /></span>
  </header>
</template>
