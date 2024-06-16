<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import Alerts from '@/components/Alerts.vue'
import { useCategoriesStore } from '@/store/useCategoriesCrud'
import { useFeatureProductStore } from '@/store/useFeatureProduct'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import AddProduct from '@/components/AddProduct.vue'
import Button1 from '@/components/Button1.vue'
import ProductCard from '@/components/ProductCard.vue'

const categoriesStore = useCategoriesStore()
const featureProductStore = useFeatureProductStore()
const alerts = ref({})

const filteredProducts = computed((data) => {
  return data?.product?.filter((product) => product?.productUrlCount > 0)
})

onMounted(async () => {
  try {
    categoriesStore.$reset()
    await categoriesStore.getActiveContent()

    categoriesStore.$patch((state) => {
      state.params.paginate = null
      state.params.include = 'product,productCount,productUrl,productUrlCount'
      state.params.filter['product.status_id'] = '1'
      state.params.filter['productUrl.status_id'] = '1'
    })

    await Promise.all([
      categoriesStore.getCustomerContent(),
      featureProductStore.getFeatureProduct()
    ])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})

onUnmounted(() => {
  try {
    categoriesStore.$reset()
    featureProductStore.$reset()
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div
    class="relative w-auto min-h-screen h-auto flex flex-col m-16 text-white font-montserrat box-border gap-10"
  >
    <div
      class="w-full h-auto p-7 bg-[#242424] flex flex-col box-border gap-3.5 rounded-xl overflow-hidden"
    >
      <CrudLabel>Categories</CrudLabel>
      <div class="w-auto h-auto overflow-x-scroll">
        <div class="w-fit h-auto overflow-hidden flex gap-5">
          <RouterLink
            v-for="data in categoriesStore.activeContent.data"
            :to="'/listing/' + data?.id + '/' + data?.name"
            :key="data?.id"
            class="w-fit h-auto py-5 px-10 mb-5 flex flex-col items-center justify-center gap-2.5 rounded-xl hover:bg-[#F7926E] transition-all duration-300 cursor-pointer"
          >
            <img :src="data?.filename" alt="Illustration of an oven." class="w-32 max-w-72" />
            <span class="tracking-wider font-bold text-sm text-wrap text-center">{{
              data?.name
            }}</span>
          </RouterLink>
        </div>
      </div>
    </div>
    <hr class="h-px my-6 bg-white/40 border-0" />
    <CrudLabel>Product of the day</CrudLabel>
    <div
      v-if="featureProductStore?.featureProduct?.product"
      class="w-full h-auto flex rounded-xl overflow-hidden"
    >
      <div class="max-w-96 w-full h-auto p-2 bg-[#F36F3F] flex items-center justify-center">
        <RouterLink
          :to="
            '/product/' +
            featureProductStore?.featureProduct?.product?.id +
            '/' +
            featureProductStore?.featureProduct?.product?.name
          "
          class="relative -rotate-45 rounded-lg cursor-pointer z-[99]"
        >
          <img class="w-92" :src="featureProductStore?.featureProduct?.product?.filename" alt="" />
        </RouterLink>
      </div>
      <div class="relative w-full h-auto bg-[#F36F3F]">
        <div
          class="relative w-full h-full bg-[#242424] flex flex-col gap-7 items-end justify-center px-10 py-14 rounded-bl-full z-[100]"
        >
          <div class="max-w-xl w-full h-auto text-right flex flex-col items-end gap-2.5">
            <span class="text-3xl font-bold text-wrap">{{
              featureProductStore?.featureProduct?.product?.name
            }}</span>
            <span class="underline text-xl text-[#6694FE]">
              {{ featureProductStore?.featureProduct?.product?.productUrlCount ?? 0 }}
              <span v-if="featureProductStore?.featureProduct?.product?.productUrlCount != 1"
                >Shops</span
              ><span v-else>Shop</span>
            </span>
            <div class="w-auto h-auto flex items-end gap-3.5">
              <span class="text-xl tracking-wider text-white/40">Best Price</span>
              <hr class="w-px h-full bg-white/40 border-0" />
              <span class="text-4xl text-[#F7926E] font-bold tracking-wider"
                >{{
                  parseFloat(featureProductStore?.featureProduct?.product?.minPrice).toFixed(2)
                }}
                €</span
              >
            </div>
          </div>
          <RouterLink
            :to="
              '/product/' +
              featureProductStore?.featureProduct?.product?.id +
              '/' +
              featureProductStore?.featureProduct?.product?.name
            "
          >
            <Button1>See Product</Button1>
          </RouterLink>
        </div>
      </div>
    </div>
    <div v-else class="w-auto h-auto px-12 py-6 bg-[#242424] rounded-lg">
      <span class="tracking-wider">Cannot show product of the day right now.</span>
    </div>
    <hr class="h-px my-6 bg-white/40 border-0" />
    <div class="w-full h-auto flex flex-col gap-8 overflow-hidden">
      <CrudLabel>Top Categories</CrudLabel>
      <div
        v-if="categoriesStore?.filteredCategories?.categoriesCount > 0"
        class="flex flex-col gap-5 m-5"
        v-for="data in categoriesStore?.filterUnauCategories"
      >
        <CrudLabel>{{ data.name }}</CrudLabel>
        <div class="w-full h-auto overflow-x-auto flex gap-3.5">
          <ProductCard
            v-for="product in data?.product?.filter((product) => product?.productUrlCount > 0)"
            :product="product"
            :key="product?.id"
          />
          <RouterLink
            :to="'/listing/' + data?.id + '/' + data?.name"
            class="w-full max-w-72 min-h-80 h-auto mb-2.5 bg-[#242424] flex flex-col items-center justify-center gap-2.5 rounded-xl transition-all duration-300 cursor-pointer hover:bg-[#F7926E]"
          >
            <b-icon-arrow-right-circle-fill class="text-white text-5xl" />
            <span class="text-lg font-bold tracking-wider">See all</span>
          </RouterLink>
        </div>
      </div>
      <div v-else class="w-auto h-auto px-12 py-6 bg-[#242424] rounded-lg">
        <span class="tracking-wider">Cannot show categories right now.</span>
      </div>
    </div>
    <hr class="h-px my-6 bg-white/40 border-0" />
    <AddProduct />
  </div>
</template>
