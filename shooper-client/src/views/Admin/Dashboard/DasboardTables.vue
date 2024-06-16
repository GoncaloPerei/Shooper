<script setup>
import { onMounted, onUpdated, ref } from 'vue'
import Alerts from '@/components/Alerts.vue'
import Table from '@/components/Table.vue'
import checkIfNull from '@/resources/checkIfNull.js'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import ModData from '@/components/ModData.vue'
import { useProductsStore } from '@/store/useProductsCrud'
import { useProductUrlsStore } from '@/store/useProductUrlsCrud'
import changeDialogState from '@/resources/changeDialogState.js'

const productsStore = useProductsStore()
const productUrlsStore = useProductUrlsStore()

const alerts = ref({})

const updateStoreStatus = async (store, statusId, id, visible, disable, successMessage) => {
  try {
    store.$patch((state) => {
      state.status = { id: statusId }
    })
    await store.putContent(id)
    await store.getFilteredContent()
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Successfully!', successMessage)
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const acceptProductUrl = (visible, disable, id) => {
  updateStoreStatus(productUrlsStore, 1, id, visible, disable, 'Url Accepted Successfully!')
}

const denyProductUrl = (visible, disable, id) => {
  updateStoreStatus(productUrlsStore, 3, id, visible, disable, 'Url Denied Successfully!')
}

const acceptProduct = (visible, disable, id) => {
  updateStoreStatus(productsStore, 1, id, visible, disable, 'Product Accepted Successfully!')
}

const denyProduct = (visible, disable, id) => {
  updateStoreStatus(productsStore, 3, id, visible, disable, 'Product Denied Successfully!')
}

onMounted(async () => {
  try {
    const stores = [productsStore, productUrlsStore]
    stores.forEach((store) =>
      store.$patch((state) => {
        state.params.filter.status_id = '2'
        state.params.paginate = '5'
      })
    )
    await Promise.all(stores.map((store) => store.getFilteredContent()))
  } catch (error) {
    alerts.value.error('An error occurred!', error)
  }
})

onUpdated(() => {
  try {
    const stores = [productsStore, productUrlsStore]
    stores.forEach((store) =>
      store.$patch((state) => {
        state.params.paginate = '5'
        state.params.filter.status_id = '2'
      })
    )
  } catch (error) {
    alerts.value.error('An error occurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="flex flex-col gap-5">
    <div class="w-full h-auto flex justify-between items-center">
      <CrudLabel>Pending Products</CrudLabel>
      <RouterLink class="underline underline-offset-4" :to="'/admin/product'"
        >See all...</RouterLink
      >
    </div>
    <Table
      :storeMeta="productsStore.filteredContent?.meta"
      :storeData="productsStore.filteredContent?.data"
      tableDataPr="7"
    >
      <template #header>
        <th
          align="left"
          class="w-fit px-5 py-5 text-nowrap border-r border-white/20"
          data-priority="1"
        ></th>
        <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="2">Name</th>
        <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="3">Category</th>
        <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="4">Created By</th>
        <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="5">Created At</th>
        <th align="left" class="w-fit px-5 py-5 border-l border-white/20" data-priority="6"></th>
        <th align="left" class="w-fit px-5 py-5" data-priority="7"></th>
      </template>
      <template #body="{ data }">
        <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
          <div class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5">
            <b-icon-box-fill class="text-[#242424] text-lg" />
          </div>
        </td>
        <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
        <td class="w-full px-5 py-5 max-w-xs truncate">
          {{ checkIfNull(data.category) }}
        </td>
        <td class="w-full px-5 py-5 max-w-xs truncate">
          {{ checkIfNull(data.createdBy) }}
        </td>
        <td class="w-full px-5 py-5 whitespace-nowrap">
          {{ checkIfNull(data.createdAt) }}
        </td>
        <td class="w-fit px-5 py-5 border-l border-white/20" align="center" valign="middle">
          <ModData
            @handle-submit="acceptProduct"
            iconName="b-icon-check-lg"
            :id="data.id"
            name="Product"
            label="Accept"
          >
            <template #main>
              <div class="flex items-center gap-5 mb-3">
                <b-icon-exclamation-circle-fill class="text-3xl" />
                <span>Are you sure you want to accept this record?</span>
              </div>
            </template>
          </ModData>
        </td>
        <td class="w-fit px-5 py-5" align="center" valign="middle">
          <ModData
            @handle-submit="denyProduct"
            iconName="b-icon-x-lg"
            :id="data.id"
            name="Product"
            label="Deny"
          >
            <template #main>
              <div class="flex items-center gap-5 mb-3">
                <b-icon-exclamation-circle-fill class="text-3xl" />
                <span>Are you sure you want to deny this record?</span>
              </div>
            </template>
          </ModData>
        </td>
      </template>
    </Table>
  </div>
  <div class="flex flex-col gap-5">
    <div class="w-full h-auto flex justify-between items-center">
      <CrudLabel>Pending Product Url's</CrudLabel>
      <RouterLink class="underline underline-offset-4" :to="'/admin/product/url'"
        >See all...</RouterLink
      >
    </div>
    <Table
      :storeMeta="productUrlsStore.filteredContent?.meta"
      :storeData="productUrlsStore.filteredContent?.data"
      tableDataPr="10"
      @changePage="store.getActiveContent()"
    >
      <template #header>
        <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">Url</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Name</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Normal Price</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Scratched Price</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">Store</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="7">Created By</th>
        <th align="left" class="px-5 py-5 text-nowrap" data-priority="8">Created At</th>
        <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="9"></th>
        <th align="left" class="px-5 py-5" data-priority="10"></th>
      </template>
      <template #body="{ data }">
        <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
          <div class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5">
            <b-icon-link class="text-[#242424] text-lg" />
          </div>
        </td>
        <td class="w-full px-5 py-5 max-w-xs truncate">
          {{ checkIfNull(data.url) }}
        </td>
        <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
        <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.price) }} €</td>
        <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.scratchedPrice) }} €</td>
        <td class="w-full px-5 py-5 max-w-xs truncate">
          {{ checkIfNull(data.store.name) }}
        </td>
        <td class="px-5 py-5 max-w-xs truncate">
          {{ checkIfNull(data.createdBy) }}
        </td>
        <td class="px-5 py-5 text-nowrap">
          {{ checkIfNull(data.createdAt) }}
        </td>
        <td class="w-fit px-5 py-5 border-l border-white/20">
          <ModData
            @handle-submit="acceptProductUrl"
            iconName="b-icon-check-lg"
            :id="data.id"
            name="Product Url"
            label="Accept"
          >
            <template #main>
              <div class="flex items-center gap-5 mb-3">
                <b-icon-exclamation-circle-fill class="text-3xl" />
                <span>Are you sure you want to accept this record?</span>
              </div>
            </template>
          </ModData>
        </td>
        <td class="w-fit px-5 py-5" align="center" valign="middle">
          <ModData
            @handle-submit="denyProductUrl"
            iconName="b-icon-x-lg"
            :id="data.id"
            name="Product Url"
            label="Deny"
          >
            <template #main>
              <div class="flex items-center gap-5 mb-3">
                <b-icon-exclamation-circle-fill class="text-3xl" />
                <span>Are you sure you want to deny this record?</span>
              </div>
            </template>
          </ModData>
        </td>
      </template>
    </Table>
  </div>
</template>
