<script setup>
import { onMounted, ref } from 'vue'
import { useProductsStoresStore } from '@/store/useProductStoresCrud'
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
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'

const store = useProductsStoresStore()
const alerts = ref({})

const visible = ref(false)

//#region Requests

const handleRequest = async (operation, successMessage, visible, disable, id = null) => {
  try {
    await operation(id)
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
      store.website_url = null
      store.filename = null
      store.title_tag = null
      store.price_tag = null
      store.scratched_price_tag = null
    },
    'Store Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.putContent(id)
      store.name = null
      store.desc = null
      store.filename = null
      store.title_tag = null
      store.price_tag = null
      store.scratched_price_tag = null
      store.status = null
    },
    'Store Edited Successfully!',
    visible,
    disable
  )
}

const deleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.deleteContent(id)
    },
    'Store Deleted Successfully!',
    visible,
    disable
  )
}

const restoreContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.restoreContent(id)
    },
    'Store Restored Successfully!',
    visible,
    disable
  )
}

const forceDeleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.forceDeleteContent(id)
    },
    'Store Force Deleted Successfully!',
    visible,
    disable
  )
}

//#endregion

onMounted(async () => {
  try {
    store.$reset()
    await Promise.all([store.getActiveContent(), store.getArchivedContent(), store.getStatus()])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <CrudLayout iconName="b-icon-shop-window">
    <template #header>Stores</template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Stores</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" name="store" />
          <ModData label="Add" type="Add" name="Store" @handle-submit="addContent">
            <template #header>Store</template>
            <template #subhead>Add a new store.</template>
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
                <label for="websiteUrl" class="font-semibold w-6rem"
                  >Website Url <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="websiteUrl"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.website_url"
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
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="titleTag" class="font-semibold w-6rem"
                  >Title Tag <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="titleTag"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.title_tag"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="priceTag" class="font-semibold w-6rem"
                  >Price Tag <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="priceTag"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.price_tag"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="scratchedTag" class="font-semibold w-6rem"
                  >Scratched Price Tag <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="scratchedTag"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.scratched_price_tag"
                  autocomplete="off"
                />
              </div>
            </template>
          </ModData>
          <Dialog
            v-model:visible="visible"
            modal
            :draggable="false"
            dismissableMask
            header="Test Store"
            class="font-montserrat tracking-wider overflow-hidden"
            :style="{ width: '25rem' }"
          >
            <form @submit.prevent="">
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="testUrl" class="font-semibold w-6rem">Test Url</label>
                <div class="flex gap-2.5">
                  <InputText
                    id="testUrl"
                    class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                    v-model="store.scratched_price_tag"
                    autocomplete="off"
                  />
                  <Button
                    type="button"
                    class="font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
                    label="Test"
                    @click=""
                  ></Button>
                </div>
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label class="font-semibold w-6rem">Test Results</label>
                <span>Name: </span>
                <span>Price: </span>
                <span>Scratched Price: </span>
              </div>
              <div class="mt-2.5 flex w-full justify-end gap-5">
                <Button
                  type="submit"
                  class="font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
                  label="Ok"
                  @click="visible = false"
                ></Button>
              </div>
            </form>
          </Dialog>
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
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">File</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Website</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Title Tag</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">Price Tag</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="7">
                Scratched Price Tag
              </th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="8">Status</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="9">Created At</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="10">Updated At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="11"></th>
              <th align="left" class="px-5 py-5" data-priority="12"></th>
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
                <img :src="data.filename" class="w-max" />
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.name) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.websiteUrl) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productTitleTag) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productPriceTag) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productScratchedTag) }}
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.status) }}
              </td>
              <td class="px-5 py-5 text-nowrap">
                {{ checkIfNull(data.createdAt) }}
              </td>
              <td class="px-5 py-5 text-nowrap">
                {{ checkIfNull(data.updatedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20 align-middle" align="center">
                <ModData
                  name="Store"
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
                      <label for="websiteUrl" class="font-semibold w-6rem">Website Url</label>
                      <InputText
                        id="websiteUrl"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.websiteUrl"
                        @update:modelValue="store.website_url = $event"
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
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="titleTag" class="font-semibold w-6rem">Title Tag</label>
                      <InputText
                        id="titleTag"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.productTitleTag"
                        @update:modelValue="store.title_tag = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="priceTag" class="font-semibold w-6rem">Price Tag</label>
                      <InputText
                        id="priceTag"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.productPriceTag"
                        @update:modelValue="store.price_tag = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="scratchedTag" class="font-semibold w-6rem"
                        >Scratched Price Tag</label
                      >
                      <InputText
                        id="scratchedTag"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.productScratchedTag"
                        @update:modelValue="store.scratched_price_tag = $event"
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
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5">
                <ModData
                  @handle-submit="deleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Store"
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
          <CrudLabel>Archived Stores</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" name="store" />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archivedContent?.meta"
            :storeData="store.archivedContent?.data"
            tableDataPr="10"
            @changePage="store.getArchivedContent()"
          >
            <template #header>
              <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Website</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="4">Title Tag</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="5">Price Tag</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="6">
                Scratched Price Tag
              </th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="7">Status</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="8">Deleted At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="9"></th>
              <th align="left" class="px-5 py-5" data-priority="10"></th>
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
                {{ checkIfNull(data.name) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.websiteUrl) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productTitleTag) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productPriceTag) }}
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.productScratchedTag) }}
              </td>
              <td class="px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.status) }}
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
