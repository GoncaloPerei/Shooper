<template>
  <label
    :htmlFor="name"
    class="font-montserrat capitalize font-bold text-lg max-md:text-normal max-sm:text-sm text-[#F36F3F] overflow-hidden tracking-wider w-full"
  >
    <slot />
    <input
      :id="name"
      :name="name"
      :type="type"
      :placeholder="placeholder"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="w-full p-4 tracking-wider font-montserrat font-light text-white outline-none rounded-t-xl bg-transparent border-b border-[#F36F3F]"
      autocomplete="off"
    />
  </label>
</template>

<script>
export default {
  props: {
    auto: {
      type: String,
      default: ''
    },
    name: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    modelValue: {
      type: String
    }
  }
}
</script>
