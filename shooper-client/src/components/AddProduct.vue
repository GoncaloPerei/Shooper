<script setup>
import { ref, onMounted } from 'vue'
import Alerts from '@/components/Alerts.vue'
import InputText from 'primevue/inputtext'
import ModData from '@/components/ModData.vue'
import Dropdown from 'primevue/dropdown'
import { useProductUrlsStore } from '@/store/useProductUrlsCrud'
import { useProductsStore } from '@/store/useProductsCrud'
import changeDialogState from '@/resources/changeDialogState.js'

const productUrlStore = useProductUrlsStore()
const productsStore = useProductsStore()
const alerts = ref({})

const addContent = async (visible, disable) => {
  try {
    await productUrlStore.postContent()
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Sucessfully!', 'The Product Url Will Be Reviewed!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

onMounted(() => {
  try {
    productUrlStore.getCategories()
    productsStore.getCategories()
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="w-full h-auto my-4 flex justify-between tracking-wider box-border items-center">
    <div>
      <span class="text-4xl font-bold leading-normal"
        >Can’t find your product?<br /><span class="text-lg font-normal"
          >If you don’t find the product you want you can always add it.</span
        ></span
      ><br />
    </div>
    <ModData label="Add" type="Add" name="Product Url" @handle-submit="addContent">
      <template #header>Product Url</template>
      <template #subhead>Add a url to a product.</template>
      <template #main>
        <div class="flex flex-col align-items-center gap-3 mb-3">
          <label for="name" class="font-semibold w-6rem"
            >Url <span class="text-[#F36F3F]">*</span></label
          >
          <InputText
            id="name"
            class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
            v-model="productUrlStore.url"
            autocomplete="off"
          />
          <span class="text-xs"
            >Paste the url from the product you want to add. The url is the link of the website page
            from the product.</span
          >
        </div>
        <div class="flex flex-col align-items-center gap-3 mb-3">
          <label for="url" class="font-semibold w-6rem"
            >Product <span class="text-[#F36F3F]">*</span></label
          >
          <Dropdown
            v-model="productUrlStore.product"
            :options="productUrlStore.categories.filter((category) => category.items.length > 0)"
            optionLabel="label"
            optionGroupLabel="label"
            optionGroupChildren="items"
            placeholder="Select a product"
            class="w-full p-3 border border-solid border-white/40"
            ><template #optiongroup="slotProps">
              <div class="flex items-center gap-2.5">
                <b-icon-grid-fill />
                <div>{{ slotProps.option.label }}</div>
              </div>
            </template></Dropdown
          >
          <ModData label="Add" type="Label" name="Product" @handle-submit="">
            <template #header>Can't find your product?</template>
            <template #subhead>Add a new product.</template>
            <template #main>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="name" class="font-semibold w-6rem"
                  >Name <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="name"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="productsStore.name"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="file" class="font-semibold w-6rem"
                  >File <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="file"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="productsStore.filename"
                  autocomplete="off"
                />
                <span class="text-xs">Paste the url of the product image in the product page.</span>
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-5">
                <label for="categories" class="font-semibold w-6rem"
                  >Category <span class="text-[#F36F3F]">*</span></label
                >
                <Dropdown
                  id="categories"
                  v-model="productsStore.category"
                  :options="productsStore.categories"
                  placeholder="Select a category"
                  optionLabel="name"
                  class="w-full p-3 border border-solid border-white/40"
                />
              </div>
            </template>
          </ModData>
        </div>
      </template>
    </ModData>
  </div>
</template>
