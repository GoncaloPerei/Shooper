<script setup>
import { onMounted, ref } from 'vue'
import { useCategoriesStore } from '@/store/useCategoriesCrud'
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

const store = useCategoriesStore()
const alerts = ref({})

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
      store.desc = null
      store.filename = null
    },
    'Category Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.putContent(id)
      store.id = null
      store.name = null
      store.desc = null
      store.filename = null
    },
    'Category Edited Successfully!',
    visible,
    disable
  )
}

const deleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.deleteContent(id)
      store.id = null
    },
    'Category Deleted Successfully!',
    visible,
    disable
  )
}

const restoreContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.restoreContent(id)
      store.id = null
    },
    'Category Restored Successfully!',
    visible,
    disable,
    id
  )
}

const forceDeleteContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.forceDeleteContent(id)
      store.id = null
    },
    'Category Restored Successfully!',
    visible,
    disable,
    id
  )
}

//#endregion

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
  <CrudLayout iconName="b-icon-grid-fill">
    <template #header> Product Categories </template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Categories</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="active" name="category" />
          <ModData label="Add" type="Add" name="Category" @handle-submit="addContent">
            <template #header>Category</template>
            <template #subhead>Add a new category.</template>
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
                <label for="desc" class="font-semibold w-6rem">Description</label>
                <InputText
                  id="desc"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.desc"
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
            </template>
          </ModData>
        </template>
        <template #table>
          <Table
            :storeMeta="store.activeContent?.meta"
            :storeData="store.activeContent?.data"
            tableDataPr="8"
            @changePage="store.getActiveContent()"
          >
            <template #header>
              <th align="center" class="px-5 py-5 border-r border-white/20" data-priority="1"></th>
              <th align="left" class="w-fit px-5 py-5 text-nowrap" data-priority="2">File</th>
              <th align="left" class="w-fit px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="4">
                Description
              </th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="5">
                Created At
              </th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="6">
                Updated At
              </th>
              <th
                align="left"
                class="w-full px-5 py-5 border-l border-white/20"
                data-priority="7"
              ></th>
              <th align="left" class="w-fit px-5 py-5" data-priority="8"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-grid-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-auto p-1 max-w-xs truncate">
                <img :src="data.filename" class="w-max rounded-lg" />
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">
                {{ checkIfNull(data.desc) }}
              </td>
              <td class="w-full px-5 py-5 text-nowrap">
                {{ checkIfNull(data.createdAt) }}
              </td>
              <td class="w-full px-5 py-5 text-nowrap">
                {{ checkIfNull(data.updatedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  name="Category"
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
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="desc" class="font-semibold w-6rem">Description</label>
                      <InputText
                        id="desc"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.desc"
                        @update:modelValue="store.desc = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="file" class="font-semibold w-6rem">File</label>
                      <InputText
                        id="file"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.filename"
                        @update:modelValue="store.file = $event"
                        autocomplete="off"
                      />
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5" align="center">
                <ModData
                  @handle-submit="deleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Category"
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
          <CrudLabel>Archived Categories</CrudLabel>
        </template>
        <template #actions>
          <SearchBar @handle-submit="" aditionalvalue="archived" name="category" />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archivedContent?.meta"
            :storeData="store.archivedContent?.data"
            tableDataPr="7"
            @changePage="store.getArchivedContent()"
          >
            <template #header>
              <th
                align="left"
                class="w-fit px-5 py-5 border-r border-white/20"
                data-priority="1"
              ></th>
              <th align="left" class="w-fit px-5 py-5 text-nowrap" data-priority="2">File</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="3">Name</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="4">
                Description
              </th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="5">
                Deleted At
              </th>
              <th
                align="left"
                class="w-fit px-5 py-5 border-l border-white/20"
                data-priority="6"
              ></th>
              <th align="left" class="w-fit px-5 py-5" data-priority="7"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-grid-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-auto p-1 max-w-xs truncate">
                <img :src="data.filename" class="w-max rounded-lg" />
              </td>
              <td class="w-full px-5 py-5 max-w-xs text-nowrap">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs text-nowrap">
                {{ checkIfNull(data.desc) }}
              </td>
              <td class="w-full px-5 py-5 text-nowrap">
                {{ checkIfNull(data.deletedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  @handle-submit="restoreContent"
                  iconName="b-icon-arrow-repeat"
                  :id="data.id"
                  name="Category"
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
                  name="Category"
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
