<script setup>
import { onMounted, ref } from 'vue'
import { useProductBrandsStore } from '@/store/useProductBrandsCrud'
import Alerts from '@/components/Alerts.vue'
import CrudLayout from '@/layouts/Views/CrudLayout.vue'
import CrudStatusLayout from '@/layouts/Views/CrudStatusLayout.vue'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import Table from '@/components/Table.vue'
import SearchBar from '@/components/SearchBar.vue'
import ModData from '@/components/ModData.vue'
import InputText from 'primevue/inputtext'
import checkIfNull from '@/resources/checkIfNull.js'
import changeDialogState from '@/resources/changeDialogState.js'

const store = useProductBrandsStore()
const alerts = ref({})

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
      store.slogan = null
    },
    'Brand Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.patchContent(id)
      store.name = null
      store.slogan = null
    },
    'Brand Edited Successfully!',
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
    'Brand Deleted Successfully!',
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
    'Brand Restored Successfully!',
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
    'Brand Force Deleted Successfully!',
    visible,
    disable
  )
}

onMounted(async () => {
  try {
    store.$reset()
    store.$patch((state) => {
      state.params.paginate = '5'
    })
    await Promise.all([store.getActiveContent(), store.getArchivedContent()])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <CrudLayout iconName="b-icon-tags-fill">
    <template #header>Brands</template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Brands</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="active" name="brand" />
          <ModData label="Add" type="Add" name="Brand" @handle-submit="addContent">
            <template #header>Brand</template>
            <template #subhead>Add a new brand.</template>
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
                <label for="slogan" class="font-semibold w-6rem">Slogan</label>
                <InputText
                  id="slogan"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.slogan"
                  autocomplete="off"
                />
              </div>
            </template>
          </ModData>
        </template>
        <template #table>
          <Table
            :storeMeta="store.activeContent?.meta"
            :storeData="store.activeContent?.data"
            tableDataPr="7"
            @changePage="store.getActiveContent()"
          >
            <template #header>
              <th
                align="left"
                class="w-fit px-5 py-5 text-nowrap border-r border-white/20"
                data-priority="1"
              ></th>
              <th align="left" class="w-full p-5 text-nowrap" data-priority="2">Name</th>
              <th align="left" class="w-full p-5 text-nowrap" data-priority="3">Slogan</th>
              <th align="left" class="w-full p-5 text-nowrap" data-priority="4">Created At</th>
              <th align="left" class="w-full p-5 text-nowrap" data-priority="5">Updated At</th>
              <th align="left" class="w-fit p-5 border-l border-white/20" data-priority="6"></th>
              <th align="left" class="w-fit p-5" data-priority="7"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-tags-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.slogan) }}</td>
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
                      <label for="slogan" class="font-semibold w-6rem">Slogan</label>
                      <InputText
                        id="slogan"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.slogan"
                        @update:modelValue="store.slogan = $event"
                        autocomplete="off"
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
          <CrudLabel>Archived Brands</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="archived" name="brand" />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archivedContent?.meta"
            :storeData="store.archivedContent?.data"
            tableDataPr="6"
            @changePage="store.getArchivedContent()"
          >
            <template #header>
              <th align="left" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="2">Name</th>
              <th align="left" class="px-5 py-5 text-nowrap" data-priority="3">Slogan</th>
              <th align="left" class="px-5 py-5" data-priority="4">Deleted At</th>
              <th align="left" class="px-5 py-5 border-l border-white/20" data-priority="5"></th>
              <th align="left" class="px-5 py-5" data-priority="6"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-box-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.slogan) }}</td>
              <td class="w-full px-5 py-5 whitespace-nowrap">
                {{ checkIfNull(data.deletedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  @handle-submit="restoreContent"
                  iconName="b-icon-arrow-repeat"
                  :id="data.id"
                  name="Brand"
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
                  name="Brand"
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
