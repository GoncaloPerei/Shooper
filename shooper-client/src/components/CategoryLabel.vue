<script setup>
const props = defineProps({
  name: { type: String, required: false },
  link: { type: String, required: true }
})
</script>

<template>
  <RouterLink :to="link">
    <div class="w-fit h-fit flex gap-2.5 items-center cursor-pointer">
      <span class="text-[#6694fe] font-semibold underline tracking-wider">{{ props.name }}</span>
      <b-icon-caret-right-fill class="text-[#6694fe]" />
    </div>
  </RouterLink>
</template>
