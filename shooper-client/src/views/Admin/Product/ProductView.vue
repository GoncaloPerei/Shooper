<script setup>
import { onMounted, ref } from 'vue'
import { useProductsStore } from '@/store/useProductsCrud'
import Alerts from '@/components/Alerts.vue'
import CrudLayout from '@/layouts/Views/CrudLayout.vue'
import CrudStatusLayout from '@/layouts/Views/CrudStatusLayout.vue'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import Table from '@/components/Table.vue'
import SearchBar from '@/components/SearchBar.vue'
import ModData from '@/components/ModData.vue'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import checkIfNull from '@/resources/checkIfNull.js'
import changeDialogState from '@/resources/changeDialogState.js'

const store = useProductsStore()
const alerts = ref({})

//#region Requests

const handleRequest = async (operation, successMessage, visible, disable) => {
  try {
    await operation()
    await store.getActiveContent()
    if (operation !== store.postContent) {
      await store.getArchivedContent()
    }
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Successfully!', successMessage)
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const addContent = async (visible, disable) => {
  await handleRequest(
    async () => {
      await store.postContent()
      store.name = null
      store.filename = null
      store.category = {}
    },
    'Product Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.putContent(id)
      store.name = null
      store.filename = null
      store.category = {}
      store.status = {}
    },
    'Product Edited Successfully!',
    visible,
    disable,
    id
  )
}

const deleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.deleteContent(id)
    },
    'Product Deleted Successfully!',
    visible,
    disable,
    id
  )
}

const restoreContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.restoreContent(id)
    },
    'Product Restored Successfully!',
    visible,
    disable,
    id
  )
}

const forceDeleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.forceDeleteContent(id)
    },
    'Product Force Deleted Successfully!',
    visible,
    disable
  )
}

//#endregion

onMounted(async () => {
  try {
    store.$reset()
    await Promise.all([
      store.getActiveContent(),
      store.getArchivedContent(),
      store.getStatus(),
      store.getCategories(),
      store.getBrands()
    ])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <CrudLayout iconName="b-icon-box-fill">
    <template #header>Products</template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Products</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="active" name="product" />
          <ModData label="Add" type="Add" name="Product" @handle-submit="addContent">
            <template #header>Product</template>
            <template #subhead>Add a new product.</template>
            <template #main>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="name" class="font-semibold w-6rem"
                  >Name <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="name"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.name"
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
                  v-model="store.filename"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-5">
                <label for="categories" class="font-semibold w-6rem"
                  >Category <span class="text-[#F36F3F]">*</span></label
                >
                <Dropdown
                  id="categories"
                  v-model="store.category"
                  :options="store.categories"
                  optionLabel="name"
                  class="w-full p-3 border border-solid border-white/40"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-5">
                <label for="brand" class="font-semibold w-6rem"
                  >Brand <span class="text-[#F36F3F]">*</span></label
                >
                <Dropdown
                  id="brand"
                  v-model="store.brand"
                  :options="store.brands"
                  optionLabel="name"
                  class="w-full p-3 border border-solid border-white/40"
                />
              </div>
            </template>
          </ModData>
        </template>
        <template #table>
          <Table
            :storeMeta="store.activeContent?.meta"
            :storeData="store.activeContent?.data"
            tableDataPr="12"
          >
            <template #header>
              <th
                align="left"
                class="w-fit px-5 py-5 text-nowrap border-r border-white/20"
                data-priority="1"
              ></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">File</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="4">Avg Price</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="5">Min Price</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="6">Category</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="7">Status</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="8">
                Created By
              </th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="9">
                Created At
              </th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="10">
                Updated At
              </th>
              <th
                align="left"
                class="w-fit px-5 py-5 border-l border-white/20"
                data-priority="11"
              ></th>
              <th align="left" class="w-fit px-5 py-5" data-priority="12"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-box-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full p-1 max-w-xs truncate">
                <img :src="data.filename" class="w-max rounded-lg" />
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.avgPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.minPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.category) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.status) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.createdBy) }}
              </td>
              <td class="w-full px-5 py-5 whitespace-nowrap">
                {{ checkIfNull(data.createdAt) }}
              </td>
              <td class="w-full px-5 py-5 whitespace-nowrap">
                {{ checkIfNull(data.updatedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center" valign="middle">
                <ModData
                  name="Product"
                  @handle-submit="editContent"
                  iconName="b-icon-pencil-square"
                  :id="data.id"
                  label="Edit"
                >
                  <template #subhead
                    >Edit <span class="underline underline-offset-2">{{ data.name }}</span
                    >.</template
                  >
                  <template #main>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="name" class="font-semibold w-6rem">Name</label>
                      <InputText
                        id="name"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.name"
                        @update:modelValue="store.name = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="file" class="font-semibold w-6rem">File</label>
                      <InputText
                        id="file"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.filename"
                        @update:modelValue="store.filename = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="status" class="font-semibold w-6rem">Status</label>
                      <Dropdown
                        v-model="store.status"
                        :options="store.statuses"
                        optionLabel="name"
                        @update:modelValue="store.status = $event"
                        class="w-full p-3 border border-solid border-white/40"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="category" class="font-semibold w-6rem">Category</label>
                      <Dropdown
                        v-model="store.category"
                        :options="store.categories"
                        optionLabel="name"
                        @update:modelValue="store.category = $event"
                        class="w-full p-3 border border-solid border-white/40"
                      />
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5" align="center" valign="middle">
                <ModData
                  @handle-submit="deleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Product"
                  label="Delete"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to delete this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
            </template>
          </Table>
        </template>
      </CrudStatusLayout>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Archived Products</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="archived" name="product" />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archivedContent?.meta"
            :storeData="store.archivedContent?.data"
            tableDataPr="9"
          >
            <template #header>
              <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">File</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Avg Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Min Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">Category</th>
              <th align="left" class="px-5 py-5" data-priority="7">Deleted At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="8"></th>
              <th align="left" class="px-5 py-5" data-priority="9"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-box-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full p-1 max-w-xs truncate">
                <img :src="data.filename" class="w-max rounded-lg" />
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.avgPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.minPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.category) }}
              </td>
              <td class="w-full px-5 py-5 whitespace-nowrap">
                {{ checkIfNull(data.deletedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  @handle-submit="restoreContent"
                  iconName="b-icon-arrow-repeat"
                  :id="data.id"
                  name="Product"
                  label="Restore"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to restore this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5" align="center">
                <ModData
                  @handle-submit="forceDeleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Product"
                  label="Force Delete"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to force delete this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
            </template>
          </Table>
        </template>
      </CrudStatusLayout>
    </template>
  </CrudLayout>
</template>
