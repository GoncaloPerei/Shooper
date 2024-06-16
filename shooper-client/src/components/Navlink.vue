<script setup>
defineProps({
  iconName: { type: String, required: false },
  link: { type: String, required: true }
})
</script>

<template>
  <RouterLink
    :to="link"
    class="w-auto flex gap-3.5 items-center py-5 px-2.5 cursor-pointer rounded-xl hover:scale-105 text-wrap overflow-hidden"
  >
    <component :is="iconName" class="text-white" />
    <slot name="img"/>
    <span class="text-white font-montserrat font-medium"><slot name="name"/></span>
  </RouterLink>
</template>
