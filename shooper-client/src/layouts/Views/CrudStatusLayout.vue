<template>
  <div class="flex flex-col gap-6">
    <div class="flex flex-col gap-3.5 justify-between">
      <slot name="header" />
      <div class="w-full flex justify-between">
        <slot name="actions" />
      </div>
    </div>
    <slot name="table" />
  </div>
</template>
