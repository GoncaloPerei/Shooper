<script setup>
const props = defineProps({
  product: { type: Object, required: true }
})
</script>

<template>
  <RouterLink
    :to="'/product/' + props?.product?.id + '/' + props?.product?.name"
    class="w-full max-w-72 h-auto p-6 mb-2.5 bg-[#242424] flex flex-col items-center justify-between gap-5 rounded-xl cursor-pointer overflow-hidden"
  >
    <img
      :src="props?.product?.filename"
      :alt="props?.product?.name"
      class="w-max max-w-50 rounded-lg overflow-hidden"
    />
    <ul class="w-full tracking-wider text-center leading-loose">
      <li class="text-base text-wrap break-words">{{ props?.product?.name }}</li>
      <div class="flex flex-col gap-1">
        <li class="underline text-xs text-[#6694FE]">
          {{ props?.product?.productUrlCount ?? 0 }}
          <span v-if="props?.product?.productUrlCount != 1">Shops</span><span v-else>Shop</span>
        </li>
        <li class="font-bold text-sm text-[#F7926E] text-wrap">
          since
          <span class="text-base font-bold"
            >{{ parseFloat(props?.product?.minPrice ?? 0).toFixed(2) }} €</span
          >
        </li>
      </div>
    </ul>
  </RouterLink>
</template>
