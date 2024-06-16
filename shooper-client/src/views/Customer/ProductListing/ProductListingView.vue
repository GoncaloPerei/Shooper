<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import Alerts from '@/components/Alerts.vue'
import { useProductsStore } from '@/store/useProductsCrud'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
import { useCategoriesStore } from '@/store/useCategoriesCrud'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import CategoryLabel from '@/components/CategoryLabel.vue'
import AddProduct from '@/components/AddProduct.vue'
import InputText from 'primevue/inputtext'
import Paginator from 'primevue/paginator'
import ProductCard from '@/components/ProductCard.vue'
import Checkbox from 'primevue/checkbox'

const crudOptionsStore = useCrudOptionsStore()
const productsStore = useProductsStore()
const categoriesStore = useCategoriesStore()

const route = useRoute()
const alerts = ref({})

const minPrice = ref(null)
const maxPrice = ref(null)

const handleSubmit = () => {
  if (minPrice.value && maxPrice.value) {
    productsStore.$patch((state) => {
      state.params.filter.price_range = `${minPrice.value},${maxPrice.value}`
    })
  } else if (minPrice.value) {
    productsStore.$patch((state) => {
      state.params.filter.price_range = `${minPrice.value},null`
    })
  } else if (maxPrice.value) {
    productsStore.$patch((state) => {
      state.params.filter.price_range = `null,${maxPrice.value}`
    })
  } else {
    productsStore.$patch((state) => {
      state.params.filter.price_range = null
    })
  }
  productsStore.getActiveContent()
}

onMounted(async () => {
  try {
    productsStore.$patch((state) => {
      if (route?.params?.id) {
        state.params.filter.product_category_id = route?.params?.id
      }
      if (route?.params?.search) {
        state.params.filter.name = route?.params?.search
      }
      state.params.paginate = '5'
      state.params.filter.status_id = '1'
      state.params.filter['productUrl.status_id'] = '1'
    })
    await productsStore.getActiveContent()
    categoriesStore.$patch((state) => {
      state.id = route?.params?.id
    })
    await categoriesStore.getCategory()
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="relative w-auto min-h-screen h-auto m-16 flex flex-col gap-10 box-border text-white">
    <div class="relative flex flex-col gap-8">
      <div class="relative w-auto h-auto flex gap-2.5 items-center">
        <CategoryLabel link="/" :name="'Home'" />
        <CategoryLabel
          v-if="route?.params?.id && route?.params?.category"
          :link="'/listing/' + route?.params?.id + '/' + route?.params?.category"
          :name="route?.params?.category"
        />
        <CategoryLabel
          v-if="route?.params?.search"
          :link="'/listing/' + route?.params?.search"
          :name="route?.params?.search"
        />
      </div>
      <hr class="h-px bg-white/40 border-0" />
      <CrudLabel>{{ route?.params?.category }}</CrudLabel>
    </div>
    <div class="relative w-full h-auto flex flex-row gap-5 self-start items-stretch">
      <div
        class="sticky top-24 w-1/4 h-auto bg-[#242424] rounded-lg p-5 self-start flex flex-col gap-5"
      >
        <span class="text-nowrap font-bold text-lg tracking-wider">Filter By</span>
        <button
          @click="handleSubmit"
          class="py-3.5 rounded-lg font-bold tracking-wider bg-[#F36F3F]"
        >
          Apply Filters
        </button>
        <div class="w-auto h-auto mx-1 flex flex-col gap-5">
          <div class="w-full h-auto flex flex-col gap-4">
            <div class="flex flex-col gap-3.5">
              <span class="font-semibold tracking-wider">Brands</span>
              <hr class="w-full border-white/40" />
            </div>
            <div>
              <div
                v-for="brand of categoriesStore?.category?.brand"
                :key="brand.key"
                class="flex items-center pb-5"
              >
                <Checkbox
                  v-model="productsStore.product_brand_id"
                  :inputId="brand.key"
                  name="category"
                  :value="brand.id"
                />
                <label :for="brand.key" class="pl-2.5 text-sm tracking-wider">{{
                  brand.name
                }}</label>
              </div>
            </div>
          </div>
          <div class="w-full h-auto flex flex-col gap-4 overflow-hidden">
            <div class="flex flex-col gap-3.5">
              <span class="font-semibold tracking-wider">Price</span>
              <hr class="w-full border-white/40" />
            </div>
            <div class="w-full flex gap-5 items-center">
              <InputText
                id="minPrice"
                size="small"
                class="w-full px-3 py-2 text-sm border border-solid border-white/40 focus:outline-[#F36F3F]"
                v-model="minPrice"
                placeholder="0"
                autocomplete="off"
              />
              <span>to</span>
              <InputText
                id="maxPrice"
                size="small"
                class="w-full px-3 py-2 text-sm border border-solid border-white/40 focus:outline-[#F36F3F]"
                v-model="maxPrice"
                placeholder="0"
                autocomplete="off"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="w-full h-auto flex flex-col gap-5">
        <div
          class="w-full h-auto bg-[#242424] rounded-lg p-5 flex gap-5 items-center justify-between"
        >
          <div>
            <span class="w-fit tracking-wider"
              >Products per page:&nbsp;
              <InputText
                id="paginate"
                size="small"
                v-model="productsStore.params.paginate"
                @update:modelValue="productsStore.getActiveContent()"
                class="max-w-12 px-3 py-1 text-sm border border-solid border-white/40 focus:outline-[#F36F3F]"
                maxlength="2"
                autocomplete="off"
            /></span>
          </div>
        </div>
        <div
          v-if="productsStore?.activeContent?.productCount > 0"
          class="w-full h-fit flex flex-wrap gap-5"
        >
          <ProductCard
            v-for="product in productsStore?.activeContent?.data?.filter(
              (product) => product?.productUrlCount > 0
            )"
            :product="product"
            :key="product?.id"
          />
        </div>
        <div v-else class="w-full h-auto bg-[#242424] rounded-lg p-5">
          <span>This category doesn't have any products...</span>
        </div>
        <div
          class="relative w-full bg-[#F7926E] flex justify-between items-center px-3.5 py-2 rounded-lg"
        >
          <Paginator
            v-model:first="crudOptionsStore.page"
            @update:first="productsStore.getActiveContent()"
            :rows="1"
            :totalRecords="productsStore?.products?.meta?.last_page"
            template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
          />
          <span class="text-black"
            >Showing {{ productsStore?.products?.meta?.from ?? '0' }} -
            {{ productsStore?.products?.meta?.to ?? '0' }} of
            {{ productsStore?.products?.meta?.total ?? '0' }} records</span
          >
        </div>
      </div>
    </div>
    <hr class="h-px my-8 bg-white/40 border-0" />
    <AddProduct />
  </div>
</template>
