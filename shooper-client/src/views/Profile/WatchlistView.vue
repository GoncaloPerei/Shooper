<script setup>
import { ref } from 'vue'
import Alerts from '@/components/Alerts.vue'

const alerts = ref({})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="w-auto h-auto flex flex-col gap-5">
    <div class="w-auto h-auto px-12 py-6 bg-[#242424] rounded-lg">
      <span>You don't have any products in your watchlist yet.</span>
    </div>
  </div>
</template>
