<script setup>
defineProps({
  type: String
})
</script>

<template>
  <button
    class="bg-[#F36F3F] w-auto px-8 py-4 h-auto rounded-xl capitalize font-montserrat font-bold text-lg max-md:text-normal max-sm:text-sm text-[#1E1E1E] hover:bg-[#F36F3F]/80 disabled:bg-[#F36F3F]/80 transition-colors duration-350 tracking-wider"
    :type="type"
  >
    <slot></slot>
  </button>
</template>
