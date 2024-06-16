<script setup>
import { onMounted, ref } from 'vue'
import Alerts from '@/components/Alerts.vue'
import AdminCard from '@/components/AdminCard.vue'
import { useAuthStore } from '@/store/auth'
const authStore = useAuthStore()
import { useUsersStore } from '@/store/useUsersCrud'
const usersStore = useUsersStore()
import { useProductBrandsStore } from '@/store/useProductBrandsCrud'
const productBrandsStore = useProductBrandsStore()
import { useProductsStore } from '@/store/useProductsCrud'
const productsStore = useProductsStore()
import { useProductUrlsStore } from '@/store/useProductUrlsCrud'
const productUrlsStore = useProductUrlsStore()
import { useCategoriesStore } from '@/store/useCategoriesCrud'
const categoriesStore = useCategoriesStore()
import { useProductsStoresStore } from '@/store/useProductStoresCrud'
const storesStore = useProductsStoresStore()
import DashboardTables from '@/views/Admin/Dashboard/DasboardTables.vue'

const alerts = ref({})

onMounted(async () => {
  try {
    const stores = [
      usersStore,
      categoriesStore,
      productsStore,
      productUrlsStore,
      storesStore,
      productBrandsStore
    ]

    stores.forEach((store) => store.$reset())

    await Promise.all(stores.map((store) => store.getActiveContent()))
  } catch (error) {
    alerts.value.error('An error occurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="w-auto min-h-screen h-fit flex flex-col gap-10 m-16 text-white font-montserrat">
    <div class="relative w-full h-auto px-10 py-16 rounded-xl bg-[#242424]">
      <span class="text-3xl font-bold text-[#F36F3F] tracking-wider"
        >Welcome back, {{ authStore.user?.name }}!<br /><span class="font-normal text-xl text-white"
          ><q>Make each day your masterpiece.</q> — John Wooden</span
        ></span
      >
      <img
        class="absolute w-96 top-1/2 transform -translate-y-1/2 right-0"
        src="@/assets/images/person_working.png"
        alt="Person Working Picture"
      />
    </div>
    <div class="w-auto flex flex-col gap-14 mx-32 px-32">
      <div class="w-auto flex flex-col gap-10">
        <div class="w-auto flex gap-20">
          <AdminCard iconName="b-icon-people-fill" link="/admin/user"
            ><template #count>{{ usersStore?.users?.usersCount }}</template>
            <template #name>Users</template></AdminCard
          >
          <AdminCard iconName="b-icon-grid-fill" link="/admin/category">
            <template #count>{{ categoriesStore?.activeContent?.categoriesCount }}</template>
            <template #name>Categories</template>
          </AdminCard>
        </div>
        <div class="w-auto flex gap-20">
          <AdminCard iconName="b-icon-tags-fill" link="/admin/brand">
            <template #count>{{ productBrandsStore?.activeContent?.productBrandsCount }}</template>
            <template #name>Brands</template>
          </AdminCard>
          <AdminCard iconName="b-icon-box-fill" link="/admin/product">
            <template #count>{{ productsStore?.activeContent?.productCount }}</template>
            <template #name>Products</template>
          </AdminCard>
        </div>
        <div class="w-auto flex gap-20">
          <AdminCard iconName="b-icon-link" link="/admin/product/url">
            <template #count>{{ productUrlsStore?.activeContent?.productUrlsCount }}</template>
            <template #name>Product Url's</template>
          </AdminCard>
          <AdminCard iconName="b-icon-shop-window" link="/admin/store">
            <template #count>{{ storesStore?.activeContent?.storesCount }}</template>
            <template #name>Stores</template>
          </AdminCard>
        </div>
      </div>
      <DashboardTables />
    </div>
  </div>
</template>
