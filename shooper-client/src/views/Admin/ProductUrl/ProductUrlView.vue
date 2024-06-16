<script setup>
import { onMounted, ref } from 'vue'
import { useProductUrlsStore } from '@/store/useProductUrlsCrud'
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

const store = useProductUrlsStore()
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
      store.url = null
      store.product = {}
    },
    'Url Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.putContent(id)
      store.url = null
      store.status = {}
      store.product = {}
    },
    'Url Edited Successfully!',
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
    'Url Deleted Successfully!',
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
    'Url Restored Successfully!',
    visible,
    disable
  )
}

const forceDeleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.forceDeleteContent(id)
    },
    'Url Force Deleted Successfully!',
    visible,
    disable
  )
}

//#endregion

onMounted(async () => {
  try {
    store.$reset()
    store.$patch((state) => {
      state.params.paginate = '5'
    })
    await Promise.all([
      store.getActiveContent(),
      store.getArchivedContent(),
      store.getStatus(),
      store.getCategories()
    ])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <CrudLayout iconName="b-icon-link">
    <template #header>Product Url's</template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Product Url's</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" name="product url" />
          <ModData label="Add" type="Add" name="Product Url" @handle-submit="addContent">
            <template #header>Url</template>
            <template #subhead>Add a url to a product.</template>
            <template #main>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="url" class="font-semibold w-6rem"
                  >Url <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="url"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.url"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="url" class="font-semibold w-6rem"
                  >Product <span class="text-[#F36F3F]">*</span></label
                >
                <Dropdown
                  v-model="store.product"
                  :options="store.categories.filter((category) => category.items.length > 0)"
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
              </div>
            </template>
          </ModData>
        </template>
        <template #table>
          <Table
            :storeMeta="store.activeContent?.meta"
            :storeData="store.activeContent?.data"
            tableDataPr="13"
            @changePage="store.getActiveContent()"
          >
            <template #header>
              <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">Product</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Store</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Url</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="7">Scratched Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="8">Status</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="9">Created By</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="10">Created At</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="11">Updated At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="12"></th>
              <th align="left" class="px-5 py-5" data-priority="13"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-link class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.product) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.store.name) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.url) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.price ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.scratchedPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.status) }}
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.createdBy) }}
              </td>
              <td class="px-5 py-5 text-nowrap">
                {{ checkIfNull(data.createdAt) }}
              </td>
              <td class="px-5 py-5 text-nowrap">
                {{ checkIfNull(data.updatedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20 align-middle" align="center">
                <ModData
                  name="Product Url"
                  @handle-submit="editContent"
                  iconName="b-icon-pencil-square"
                  :id="data.id"
                  label="Edit"
                >
                  <template #subhead
                    >Edit <span class="underline underline-offset-2">{{ data.url }}</span
                    >.</template
                  >
                  <template #main>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="url" class="font-semibold w-6rem">Url</label>
                      <InputText
                        id="url"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.url"
                        @update:modelValue="store.url = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="status" class="font-semibold w-6rem">Status</label>
                      <Dropdown
                        v-model="store.status"
                        :options="store.statuses"
                        optionLabel="name"
                        :placeholder="data?.status"
                        @update:modelValue="store.status = $event"
                        class="w-full p-3 border border-solid border-white/40"
                      />
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5">
                <ModData
                  @handle-submit="deleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Product Url"
                  type="Delete"
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
          <CrudLabel>Archived Product Url's</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="archived" name="product url" />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archivedContent?.meta"
            :storeData="store.archivedContent?.data"
            tableDataPr="9"
            @changePage="store.getArchivedContent()"
          >
            <template #header>
              <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">Url</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Scratched Price</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">Store</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="7">Deleted At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="8"></th>
              <th align="left" class="px-5 py-5" data-priority="9"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-link class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.url) }}
              </td>
              <td class="px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.price ?? 0).toFixed(2) }} €
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ parseFloat(data.scratchedPrice ?? 0).toFixed(2) }} €
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.store.name) }}
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.deletedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  @handle-submit="restoreContent"
                  iconName="b-icon-arrow-repeat"
                  :id="data.id"
                  name="Product Url"
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
                  name="Url"
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
