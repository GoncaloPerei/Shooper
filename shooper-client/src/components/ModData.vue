<script setup>
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import { ref } from 'vue'

const props = defineProps({
  name: { type: String },
  label: { type: String },
  id: { type: Number },
  iconName: { type: String },
  type: { type: String }
})

const emits = defineEmits(['handleSubmit'])

const visible = ref(false)

const disableSubmit = ref(false)

const disableBtn = () => {
  disableSubmit.value = true
  setTimeout(() => {
    disableSubmit.value = false
  }, 3500)
}

const handleSubmit = async () => {
  disableBtn()
  emits('handleSubmit', visible, disableSubmit, props.id)
}
</script>

<template>
  <button
    v-if="props.type === 'Add'"
    @click="visible = true"
    class="w-fit flex gap-2.5 items-center bg-[#F36F3F] px-5 py-3.5 rounded-xl text-[#242424] hover:bg-[#F36F3F]/80 transition-colors duration-300"
  >
    <b-icon-plus-circle-fill class="text-xl" />
    <span class="font-montserrat font-bold tracking-wider">Add <slot name="header" /></span>
  </button>
  <span
    v-if="props.type === 'Label'"
    @click="visible = true"
    class="text-xs text-start underline underline-offset-2 text-[#6694FE] cursor-pointer tracking-wider font-montserrat"
    ><slot name="header"
  /></span>
  <button v-if="props.iconName" @click="visible = true" class="h-fit w-fit">
    <component :is="iconName" class="text-xl h-fit w-fit" />
  </button>
  <Dialog
    v-model:visible="visible"
    modal
    :draggable="false"
    dismissableMask
    :header="label + '&nbsp;' + name"
    class="font-montserrat tracking-wider overflow-hidden"
    :style="{ width: '25rem' }"
  >
    <form @submit.prevent="handleSubmit">
      <span class="text-sm block text-[#F7926E]"><slot name="subhead" /></span>
      <div class="h-auto max-h-96 overflow-y-auto flex flex-col scroll-pr-3 gap-2.5">
        <slot name="main" />
      </div>
      <div class="mt-2.5 flex w-full justify-end gap-5">
        <Button
          type="submit"
          class="font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
          :label="label + '&nbsp;' + name"
          :disabled="disableSubmit"
        ></Button>
        <Button
          type="button"
          class="font-montserrat tracking-wider outline outline-1 outline-[#F36F3F] text-[#F36F3F] px-2.5 py-1.5"
          label="Cancel"
          severity="secondary"
          @click="visible = false"
        ></Button>
      </div>
    </form>
  </Dialog>
</template>
