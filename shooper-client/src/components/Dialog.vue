<script setup>
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import { ref } from 'vue'

const visible = ref(false)

const props = defineProps({
  visible: { type: Object, required: true },
  header: { type: String, required: true }
})
</script>

<template>
  <Dialog
    v-model:visible="visible"
    modal
    dismissableMask
    :header="'Add ' + header"
    class="bg-[#242424] font-montserrat tracking-wider border-b border-white/40"
    :style="{ width: '25rem' }"
  >
    <form @submit.prevent="handleSubmit">
      <slot />
      <div class="flex justify-end gap-5">
        <Button
          type="submit"
          label="Add User"
          :disabled="disableSubmit"
          class="bg-[#F36F3F] px-2.5 py-1.5 text-[#242424] font-montserrat tracking-wider"
        ></Button>
        <Button
          type="button"
          label="Cancel"
          severity="secondary"
          @click="visible = false"
          class="outline outline-1 outline-[#F36F3F] text-[#F36F3F] px-2.5 py-1.5 font-montserrat tracking-wider"
        ></Button>
      </div>
    </form>
  </Dialog>
</template>
