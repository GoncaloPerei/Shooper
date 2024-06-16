<script setup>
const props = defineProps({
  name: { type: String, required: true }
})

const emits = defineEmits(['handleSubmit', 'update:modelValue'])

const handleSubmit = () => {
  emits('handleSubmit', props.aditionalvalue)
}
</script>

<template>
  <form
    @submit.prevent=""
    class="max-w-2/4 w-2/4 h-fit max-sm:hidden border border-white rounded-full bg-transparent flex items-center overflow-hidden"
  >
    <input
      id="search"
      name="search"
      @input="$emit('update:modelValue', $event.target.value), handleSubmit()"
      type="text"
      :placeholder="'Search a ' + name + '...'"
      class="w-full py-3 leading-auto bg-transparent font-montserrat max-md:text-sm text-[#FFFFFF] px-5 outline-none tracking-wider"
      autocomplete="off"
    />
  </form>
</template>
