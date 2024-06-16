<script setup>
defineProps({
  iconName: { type: String, required: true }
})
</script>

<template>
  <button class="w-auto flex gap-1.5 items-center text-[#6694FE] font-semibold underline">
    <component :is="iconName" class="text-sm" />
    <span class="text-sm tracking-wider"><slot /></span>
  </button>
</template>
