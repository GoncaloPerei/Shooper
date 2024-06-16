<script setup>
import Logo from '@/components/Logo.vue'

defineProps({
  open: Boolean,
  setOpen: Function
})
</script>

<template>
  <div
    :class="{
      'fixed top-0 w-full transition-all h-screen backdrop-filter duration-450 ease-in-out backdrop-blur-lg z-[998]':
        open,
      'duration-450 ease-in-out': !open
    }"
  >
    <div
      class="z-[999] fixed top-0 w-1/4 p-7 h-screen bg-[rgb(36,36,36)] flex flex-col gap-3.5 transition-all duration-500 ease-in-out overflow-x-auto"
      :class="{
        'left-0': open,
        '-left-1/4': !open
      }"
    >
      <div class="flex justify-between items-center pb-3.5">
        <Logo />
        <b-icon-x
          @click="setOpen"
          class="text-white text-3xl max-md:text-2xl max-sm:text-xl cursor-pointer"
        />
      </div>
      <div class="flex flex-col gap-3.5">
        <slot />
      </div>
    </div>
  </div>
</template>
